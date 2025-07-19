<?php

namespace App\Http\Controllers\Agent\MS;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\AffectationMaitreStage;
use App\Models\ThemeStage;
use App\Models\Evaluation;
use App\Mail\ThemeProposeMail;
use App\Mail\EvaluationNotifieeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Notifications\StagiaireNotification;

class StageController extends Controller
{
    /**
     * Vérification du rôle avant chaque action
     */
    private function checkMSRole()
    {
        $user = Auth::user();
        if (!$user || !$user->agent || $user->agent->role_agent !== 'MS') {
            abort(403, 'Accès réservé aux Maîtres de Stage.');
        }
    }

    /**
     * Afficher la liste des stages du maître de stage
     */
    public function index()
    {
        $this->checkMSRole();
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Récupérer tous les stages affectés à ce maître de stage (actifs et réaffectés)
            $affectations = AffectationMaitreStage::where('maitre_stage_id', $user->id)
                ->with([
                    'stage.demandeStage.stagiaire.user',
                    'stage.structure',
                    'stage.themeStage',
                    // Charger les autres affectations pour le même stage
                    'stage.affectationsMaitreStage.maitreStage',
                ])
                ->get();

            // Extraire les stages des affectations et s'assurer que toutes les relations sont chargées
            $stages = $affectations->map(function ($affectation) {
                $stage = $affectation->stage;

                // S'assurer que le stage existe
                if (!$stage) {
                    return null;
                }

                // Forcer le chargement des relations si elles ne sont pas déjà chargées
                if (!$stage->relationLoaded('demandeStage') ||
                    !$stage->demandeStage ||
                    !$stage->demandeStage->relationLoaded('stagiaire')) {
                    $stage->load('demandeStage.stagiaire.user');
                }

                return $stage;
            })->filter(); // Filtrer les valeurs null

            // Vérifier que les données des stagiaires sont bien présentes
            $stagesWithStagiaires = $stages->map(function ($stage) use ($user) {
                // Ajouter un attribut pour faciliter le débogage
                $stage->stagiaire_info = [
                    'has_demande' => $stage->demandeStage ? true : false,
                    'has_stagiaire' => $stage->demandeStage && $stage->demandeStage->stagiaire ? true : false,
                    'has_user' => $stage->demandeStage && $stage->demandeStage->stagiaire && $stage->demandeStage->stagiaire->user ? true : false,
                    'nom' => $stage->demandeStage && $stage->demandeStage->stagiaire && $stage->demandeStage->stagiaire->user ? $stage->demandeStage->stagiaire->user->nom : null,
                    'prenom' => $stage->demandeStage && $stage->demandeStage->stagiaire && $stage->demandeStage->stagiaire->user ? $stage->demandeStage->stagiaire->user->prenom : null,
                    'email' => $stage->demandeStage && $stage->demandeStage->stagiaire && $stage->demandeStage->stagiaire->user ? $stage->demandeStage->stagiaire->user->email : null,
                ];

                // Vérifier si ce stage a été réaffecté
                $affectation = $stage->affectationsMaitreStage()
                    ->where('maitre_stage_id', $user->id)
                    ->first();

                $stage->est_reaffecte = $affectation && $affectation->statut === 'Réaffectée';
                $stage->est_actif = $affectation && in_array($affectation->statut, ['En cours', 'Acceptée']);

                // Si le stage a été réaffecté, ajouter les informations sur le nouveau maître de stage
                if ($stage->est_reaffecte) {
                    // Trouver l'affectation active pour ce stage
                    $nouvelleAffectation = $stage->affectationsMaitreStage()
                        ->where('maitre_stage_id', '!=', $user->id)
                        ->whereIn('statut', ['En cours', 'Acceptée'])
                        ->with(['maitreStage'])
                        ->first();

                    if ($nouvelleAffectation && $nouvelleAffectation->maitreStage) {
                        $nouveauMS = $nouvelleAffectation->maitreStage;

                            // Récupérer la structure dont l'agent est responsable
                        $structure = \App\Models\Structure::where('responsable_id', $nouveauMS->id)->first();

                            $stage->reaffectation_info = [
                                'nouveau_ms_id' => $nouveauMS->id,
                                'nouveau_ms_nom' => $nouveauMS->nom,
                                'nouveau_ms_prenom' => $nouveauMS->prenom,
                                'structure_id' => $structure ? $structure->id : null,
                                'structure_libelle' => $structure ? $structure->libelle : 'Non spécifiée',
                                'structure_sigle' => $structure ? $structure->sigle : '',
                                'date_reaffectation' => $nouvelleAffectation->date_affectation,
                            ];
                    }
                }

                return $stage;
            });

            // Trier les stages par date de modification décroissante
            $stagesWithStagiaires = $stagesWithStagiaires->sortByDesc(function ($stage) {
                return $stage->updated_at;
            });

            // Log pour débogage
            Log::info('Stages avec informations stagiaires', [
                'count' => $stagesWithStagiaires->count(),
                'stages_info' => $stagesWithStagiaires->map(function ($stage) {
                    return [
                        'id' => $stage->id,
                        'stagiaire_info' => $stage->stagiaire_info
                    ];
                })
            ]);

            // Convertir en tableau indexé numériquement pour s'assurer que JavaScript le traite comme un tableau
            $stagesArray = array_values($stagesWithStagiaires->map(function ($stage) {
                $stageArray = $stage->toArray();
                $stageArray['themeStage'] = $stage->themeStage ? $stage->themeStage->toArray() : null;
                return $stageArray;
            })->toArray());

            return Inertia::render('Agent/MS/Stages/Index', [
                'stages' => $stagesArray,
                'notifications' => $user->notifications()->latest()->take(20)->get(),
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des stages', [
                'error' => $e->getMessage(),
                'agent_id' => $agent->id
            ]);

            return Inertia::render('Agent/MS/Stages/Index', [
                'stages' => [], // Tableau vide en cas d'erreur
                'error' => 'Une erreur est survenue lors du chargement des stages.'
            ]);
        }
    }

    /**
     * Afficher les détails d'un stage
     */
    public function show(Request $request, Stage $stage)
    {
        $this->checkMSRole();
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Vérifier que l'utilisateur est bien le maître de stage assigné à ce stage (actif ou réaffecté)
            $affectation = AffectationMaitreStage::where('stage_id', $stage->id)
                ->where('maitre_stage_id', $user->id)
                ->first();

            if (!$affectation) {
                return redirect()->route('agent.ms.stages')->with('error', 'Vous n\'êtes pas autorisé à accéder à ce stage.');
            }

            // Vérifier si l'affectation est active ou réaffectée
            $stage->est_actif = in_array($affectation->statut, ['En cours', 'Acceptée']);
            $stage->est_reaffecte = $affectation->statut === 'Réaffectée';

            // Si le stage a été réaffecté, ajouter les informations sur le nouveau maître de stage
            if ($stage->est_reaffecte) {
                // Trouver l'affectation active pour ce stage
                $nouvelleAffectation = $stage->affectationsMaitreStage()
                    ->where('maitre_stage_id', '!=', $user->id)
                    ->whereIn('statut', ['En cours', 'Acceptée'])
                    ->with(['maitreStage'])
                    ->first();

                if ($nouvelleAffectation && $nouvelleAffectation->maitreStage) {
                    $nouveauMS = $nouvelleAffectation->maitreStage;

                        // Récupérer la structure dont l'agent est responsable
                    $structure = \App\Models\Structure::where('responsable_id', $nouveauMS->id)->first();

                        $stage->reaffectation_info = [
                            'nouveau_ms_id' => $nouveauMS->id,
                            'nouveau_ms_nom' => $nouveauMS->nom,
                            'nouveau_ms_prenom' => $nouveauMS->prenom,
                            'structure_id' => $structure ? $structure->id : null,
                            'structure_libelle' => $structure ? $structure->libelle : 'Non spécifiée',
                            'structure_sigle' => $structure ? $structure->sigle : '',
                            'date_reaffectation' => $nouvelleAffectation->date_affectation,
                        ];
                }
            }

            // Charger les relations nécessaires
            $stage->load([
                'demandeStage.stagiaire.user',
                'demandeStage.membres.user',
                'structure',
                'themeStage',
                'evaluation'
            ]);

            // Log pour vérifier la présence de la relation themeStage
            \Log::info('DEBUG_STAGE_SHOW', [
                'id' => $stage->id,
                'theme_stage_id' => $stage->theme_stage_id,
                'themeStage' => $stage->themeStage
            ]);

            // Forcer le chargement des relations si elles ne sont pas déjà chargées
            if (!$stage->relationLoaded('demandeStage') ||
                !$stage->demandeStage ||
                !$stage->demandeStage->relationLoaded('stagiaire')) {
                $stage->load([
                    'demandeStage.stagiaire.user',
                    'affectationsMaitreStage' => function($query) {
                        $query->orderBy('date_affectation', 'desc');
                    },
                    'affectationsMaitreStage.maitreStage',
                    'affectationsMaitreStage.agentAffectant.user',
                    'themeStage'
                ]);
            }

            // Ajouter les informations du stagiaire directement dans l'objet stage
            $stage->stagiaire_info = [
                'has_demande' => $stage->demandeStage ? true : false,
                'has_stagiaire' => $stage->demandeStage && $stage->demandeStage->stagiaire ? true : false,
                'has_user' => $stage->demandeStage && $stage->demandeStage->stagiaire && $stage->demandeStage->stagiaire->user ? true : false,
                'nom' => $stage->demandeStage && $stage->demandeStage->stagiaire && $stage->demandeStage->stagiaire->user ? $stage->demandeStage->stagiaire->user->nom : null,
                'prenom' => $stage->demandeStage && $stage->demandeStage->stagiaire && $stage->demandeStage->stagiaire->user ? $stage->demandeStage->stagiaire->user->prenom : null,
                'email' => $stage->demandeStage && $stage->demandeStage->stagiaire && $stage->demandeStage->stagiaire->user ? $stage->demandeStage->stagiaire->user->email : null,
                'telephone' => $stage->demandeStage && $stage->demandeStage->stagiaire && $stage->demandeStage->stagiaire->user ? $stage->demandeStage->stagiaire->user->telephone : null,
                'niveau_etude' => $stage->demandeStage && $stage->demandeStage->stagiaire ? $stage->demandeStage->stagiaire->niveau_etude : null,
                'universite' => $stage->demandeStage && $stage->demandeStage->stagiaire ? $stage->demandeStage->stagiaire->universite : null,
                'filiere' => $stage->demandeStage && $stage->demandeStage->stagiaire ? $stage->demandeStage->stagiaire->filiere : null,
            ];

            // Log pour débogage
            Log::info('Stage avec informations stagiaire', [
                'stage_id' => $stage->id,
                'stagiaire_info' => $stage->stagiaire_info
            ]);

            return Inertia::render('Agent/MS/Stages/Show', [
                'stage' => array_merge($stage->toArray(), [
                    'themeStage' => $stage->themeStage ? $stage->themeStage->toArray() : null,
                    'demandeStage' => array_merge(
                        $stage->demandeStage ? $stage->demandeStage->toArray() : [],
                        [
                            'membres' => $stage->demandeStage && $stage->demandeStage->relationLoaded('membres')
                                ? $stage->demandeStage->membres->map(function($m) use ($stage) {
                                    $stagiaire = \App\Models\Stagiaire::where('user_id', $m->user_id)->first();
                                    $statutStage = null;
                                    $stageId = null;
                                    $evaluationMembre = null;
                                    if ($stagiaire) {
                                        $stageMembre = \App\Models\Stage::where('demande_stage_id', $stage->demande_stage_id)
                                            ->where('stagiaire_id', $stagiaire->id_stagiaire)
                                            ->first();
                                        $statutStage = $stageMembre ? $stageMembre->statut : null;
                                        $stageId = $stageMembre ? $stageMembre->id : null;
                                        if ($stageMembre) {
                                            $evaluationMembre = \App\Models\Evaluation::where('stage_id', $stageMembre->id)
                                                ->where('agent_id', \Auth::user()->agent->id)
                                                ->first();
                                        }
                                    }
                                    return array_merge($m->toArray(), [
                                        'user' => $m->relationLoaded('user') ? $m->user->toArray() : null,
                                        'statutStage' => $statutStage,
                                        'stage_id' => $stageId,
                                        'evaluationMembre' => $evaluationMembre ? $evaluationMembre->toArray() : null
                                    ]);
                                })->toArray()
                                : []
                        ]
                    ),
                    'evaluation' => $stage->evaluation ? $stage->evaluation->toArray() : null
                ]),
                'openContact' => $request->boolean('openContact'),
                'success' => session('success'),
                'error' => session('error'),
                'notifications' => $user->notifications()->latest()->take(20)->get(),
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des détails du stage', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'agent_id' => $agent->id
            ]);

            return redirect()->route('agent.ms.stages')->with('error', 'Une erreur est survenue lors du chargement des détails du stage.');
        }
    }

    /**
     * Mettre à jour le statut d'un stage
     */
    public function updateStatus(Request $request, Stage $stage)
    {
        $this->checkMSRole();
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Vérifier que l'utilisateur est bien le maître de stage assigné à ce stage
            $affectation = AffectationMaitreStage::where('stage_id', $stage->id)
                ->where('maitre_stage_id', $user->id)
                ->whereIn('statut', ['En cours', 'Acceptée']) // Vérifier que l'affectation est active
                ->first();

            if (!$affectation) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à modifier ce stage.');
            }

            // Valider le statut
            $validated = $request->validate([
                'statut' => 'required|in:En cours,Terminé,En attente'
            ]);

            // Mettre à jour le statut du stage
            $stage->update([
                'statut' => $validated['statut']
            ]);

            Log::info('Statut du stage mis à jour', [
                'stage_id' => $stage->id,
                'ancien_statut' => $stage->getOriginal('statut'),
                'nouveau_statut' => $validated['statut'],
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('success', 'Le statut du stage a été mis à jour avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du statut du stage', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du statut du stage.');
        }
    }

    /**
     * Créer ou modifier le thème d'un stage
     */
    public function storeTheme(Request $request, Stage $stage)
    {
        $this->checkMSRole();
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Vérifier que l'utilisateur est bien le maître de stage assigné à ce stage
            $affectation = AffectationMaitreStage::where('stage_id', $stage->id)
                ->where('maitre_stage_id', $user->id)
                ->whereIn('statut', ['En cours', 'Acceptée'])
                ->first();

            if (!$affectation) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à modifier ce stage.');
            }

            // Validation des données
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'description' => 'required|string|max:2000',
                'etat' => 'required|in:Proposé,Modifié,Validé,Refusé',
                'commentaire' => 'nullable|string|max:1000'
            ]);

            // Préparer les mots-clés à partir du titre et de la description
            $motsCles = $this->generateMotsCles($validated['titre'], $validated['description']);

            // Rafraîchir la relation themeStage sur le modèle stage
            $stage->load('themeStage');
            $theme = $stage->themeStage;

            if ($stage->themeStage) {
                // Mettre à jour le thème existant
                $stage->themeStage->update([
                    'intitule' => $validated['titre'],
                    'description' => $validated['description'],
                    'etat' => $validated['etat'],
                    'mots_cles' => $motsCles,
                    'user_id' => $user->id, // Indiquer que c'est le MS qui a modifié le thème
                ]);

                // Envoyer un mail au stagiaire si le thème est proposé ou modifié
                if (in_array($validated['etat'], ['Proposé', 'Modifié'])) {
                    try {
                        if ($stage->stagiaire_info && $stage->stagiaire_info->email) {
                            Mail::to($stage->stagiaire_info->email)
                                ->send(new ThemeProposeMail($stage->stagiaire_info, $stage, $theme));
                        } elseif ($stage->demandeStage && $stage->demandeStage->stagiaire && $stage->demandeStage->stagiaire->user) {
                            Mail::to($stage->demandeStage->stagiaire->user->email)
                                ->send(new ThemeProposeMail($stage->demandeStage->stagiaire, $stage, $theme));
                        }
                    } catch (\Exception $e) {
                        Log::error('Erreur lors de l\'envoi du mail de notification du thème', [
                            'error' => $e->getMessage(),
                            'stage_id' => $stage->id
                        ]);
                    }
                }

                // Envoyer un mail à tous les membres du groupe et au MS
                if ($stage->demandeStage) {
                    $membres = $stage->demandeStage->membres;
                    foreach ($membres as $membre) {
                        if ($membre->user && $membre->user->email) {
                            Mail::to($membre->user->email)
                                ->send(new ThemeProposeMail($membre, $stage, $theme));
                        }
                    }
                    // Envoyer un mail au MS
                    $msUser = $stage->affectationsMaitreStage->whereIn('statut', ['En cours', 'Acceptée'])->first()?->maitreStage?->user;
                    if ($msUser && $msUser->email) {
                        Mail::to($msUser->email)
                            ->send(new ThemeProposeMail($msUser, $stage, $theme));
                    }
                }

                // Après l'envoi de l'email lors de la création ou modification du thème
                if ($stage->demandeStage && $stage->demandeStage->stagiaire && $stage->demandeStage->stagiaire->user) {
                    $stagiaireUser = $stage->demandeStage->stagiaire->user;
                    $etat = $validated['etat'] ?? null;
                    if ($etat === 'Proposé') {
                        $stagiaireUser->notify(new \App\Notifications\StagiaireNotification(
                            'Un nouveau thème de stage a été proposé pour votre stage.',
                            route('stagiaire.stages.show', $stage->id)
                        ));
                    } elseif ($etat === 'Modifié') {
                        $stagiaireUser->notify(new \App\Notifications\StagiaireNotification(
                            'Le thème de votre stage a été modifié.',
                            route('stagiaire.stages.show', $stage->id)
                        ));
                    } elseif ($etat === 'Validé') {
                        $stagiaireUser->notify(new \App\Notifications\StagiaireNotification(
                            'Le thème de votre stage a été validé !',
                            route('stagiaire.stages.show', $stage->id)
                        ));
                    }
                }

                Log::info('Thème du stage mis à jour', [
                    'stage_id' => $stage->id,
                    'theme_id' => $theme->id,
                    'agent_id' => $agent->id
                ]);

                return redirect()->back()->with('success', 'Le thème du stage a été mis à jour avec succès.');
            } else {
                // Créer un nouveau thème
                $theme = ThemeStage::create([
                    'intitule' => $validated['titre'],
                    'description' => $validated['description'],
                    'etat' => $validated['etat'],
                    'mots_cles' => $motsCles,
                    'user_id' => $user->id, // Indiquer que c'est le MS qui a proposé le thème
                ]);

                // Associer le thème au stage
                $stage->update([
                    'theme_stage_id' => $theme->id
                ]);

                // Envoyer un mail au stagiaire si le thème est proposé
                if ($validated['etat'] === 'Proposé') {
                    try {
                        if ($stage->stagiaire_info && $stage->stagiaire_info->email) {
                            Mail::to($stage->stagiaire_info->email)
                                ->send(new ThemeProposeMail($stage->stagiaire_info, $stage, $theme));
                        } elseif ($stage->demandeStage && $stage->demandeStage->stagiaire && $stage->demandeStage->stagiaire->user) {
                            Mail::to($stage->demandeStage->stagiaire->user->email)
                                ->send(new ThemeProposeMail($stage->demandeStage->stagiaire, $stage, $theme));
                        }
                    } catch (\Exception $e) {
                        Log::error('Erreur lors de l\'envoi du mail de notification du thème', [
                            'error' => $e->getMessage(),
                            'stage_id' => $stage->id
                        ]);
                    }
                }

                // Envoyer un mail à tous les membres du groupe et au MS
                if ($stage->demandeStage) {
                    $membres = $stage->demandeStage->membres;
                    foreach ($membres as $membre) {
                        if ($membre->user && $membre->user->email) {
                            Mail::to($membre->user->email)
                                ->send(new ThemeProposeMail($membre, $stage, $theme));
                        }
                    }
                    // Envoyer un mail au MS
                    $msUser = $stage->affectationsMaitreStage->whereIn('statut', ['En cours', 'Acceptée'])->first()?->maitreStage?->user;
                    if ($msUser && $msUser->email) {
                        Mail::to($msUser->email)
                            ->send(new ThemeProposeMail($msUser, $stage, $theme));
                    }
                }

                // Après l'envoi de l'email lors de la création ou modification du thème
                if ($stage->demandeStage && $stage->demandeStage->stagiaire && $stage->demandeStage->stagiaire->user) {
                    $stagiaireUser = $stage->demandeStage->stagiaire->user;
                    $etat = $validated['etat'] ?? null;
                    if ($etat === 'Proposé') {
                        $stagiaireUser->notify(new \App\Notifications\StagiaireNotification(
                            'Un nouveau thème de stage a été proposé pour votre stage.',
                            route('stagiaire.stages.show', $stage->id)
                        ));
                    } elseif ($etat === 'Modifié') {
                        $stagiaireUser->notify(new \App\Notifications\StagiaireNotification(
                            'Le thème de votre stage a été modifié.',
                            route('stagiaire.stages.show', $stage->id)
                        ));
                    } elseif ($etat === 'Validé') {
                        $stagiaireUser->notify(new \App\Notifications\StagiaireNotification(
                            'Le thème de votre stage a été validé !',
                            route('stagiaire.stages.show', $stage->id)
                        ));
                    }
                }

                Log::info('Nouveau thème créé pour le stage', [
                    'stage_id' => $stage->id,
                    'theme_id' => $theme->id,
                    'agent_id' => $agent->id
                ]);

                return redirect()->back()->with('success', 'Le thème du stage a été créé avec succès.');
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création/modification du thème du stage', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement du thème.');
        }
    }

    /**
     * Générer des mots-clés à partir du titre et de la description
     */
    private function generateMotsCles($titre, $description)
    {
        // Mots vides à exclure
        $motsVides = ['le', 'la', 'les', 'un', 'une', 'des', 'du', 'de', 'et', 'ou', 'avec', 'pour', 'dans', 'sur', 'par', 'à', 'au', 'aux'];

        // Combiner titre et description
        $texte = strtolower($titre . ' ' . $description);

        // Nettoyer et extraire les mots
        $mots = preg_split('/[^a-zA-ZÀ-ÿ0-9]+/', $texte);
        $mots = array_filter($mots, function($mot) use ($motsVides) {
            return strlen($mot) > 2 && !in_array(strtolower($mot), $motsVides);
        });

        // Prendre les mots les plus fréquents (max 10)
        $motsCles = array_unique($mots);
        $motsCles = array_slice($motsCles, 0, 10);

        return implode(', ', $motsCles);
    }

    /**
     * Valider le thème d'un stage
     */
    public function validerTheme(Stage $stage)
    {
        $this->checkMSRole();
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Vérifier que l'utilisateur est bien le maître de stage assigné à ce stage
            $affectation = AffectationMaitreStage::where('stage_id', $stage->id)
                ->where('maitre_stage_id', $user->id)
                ->whereIn('statut', ['En cours', 'Acceptée']) // Vérifier que l'affectation est active
                ->first();

            if (!$affectation) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à modifier ce stage.');
            }

            // Vérifier que le stage a un thème
            if (!$stage->themeStage) {
                return redirect()->back()->with('error', 'Ce stage n\'a pas de thème à valider.');
            }

            // Mettre à jour l'état du thème
            $stage->themeStage->update([
                'etat' => 'Validé'
            ]);

            // Lors du refus du thème
            if ($stage->demandeStage && $stage->demandeStage->stagiaire && $stage->demandeStage->stagiaire->user) {
                $stagiaireUser = $stage->demandeStage->stagiaire->user;
                $stagiaireUser->notify(new \App\Notifications\StagiaireNotification(
                    'Le thème de votre stage a été refusé.',
                    route('stagiaire.stages.show', $stage->id)
                ));
            }

            Log::info('Thème du stage validé', [
                'stage_id' => $stage->id,
                'theme_id' => $stage->themeStage->id,
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('success', 'Le thème du stage a été validé avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la validation du thème du stage', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la validation du thème du stage.');
        }
    }

    /**
     * Refuser le thème d'un stage
     */
    public function refuserTheme(Request $request, Stage $stage)
    {
        $this->checkMSRole();
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Vérifier que l'utilisateur est bien le maître de stage assigné à ce stage
            $affectation = AffectationMaitreStage::where('stage_id', $stage->id)
                ->where('maitre_stage_id', $user->id)
                ->whereIn('statut', ['En cours', 'Acceptée']) // Vérifier que l'affectation est active
                ->first();

            if (!$affectation) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à modifier ce stage.');
            }

            // Vérifier que le stage a un thème
            if (!$stage->themeStage) {
                return redirect()->back()->with('error', 'Ce stage n\'a pas de thème à refuser.');
            }

            // Valider le motif de refus
            $validated = $request->validate([
                'motif_refus' => 'required|string|max:500'
            ]);

            // Mettre à jour l'état du thème
            $stage->themeStage->update([
                'etat' => 'Refusé',
                'motif_refus' => $validated['motif_refus']
            ]);

            // Lors du refus du thème
            if ($stage->demandeStage && $stage->demandeStage->stagiaire && $stage->demandeStage->stagiaire->user) {
                $stagiaireUser = $stage->demandeStage->stagiaire->user;
                $stagiaireUser->notify(new \App\Notifications\StagiaireNotification(
                    'Le thème de votre stage a été refusé.',
                    route('stagiaire.stages.show', $stage->id)
                ));
            }

            Log::info('Thème du stage refusé', [
                'stage_id' => $stage->id,
                'theme_id' => $stage->themeStage->id,
                'motif_refus' => $validated['motif_refus'],
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('success', 'Le thème du stage a été refusé avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors du refus du thème du stage', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors du refus du thème du stage.');
        }
    }

    /**
     * Noter un stage
     */
    public function noter(Request $request, Stage $stage)
    {
        $this->checkMSRole();
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Log des données reçues pour debug
            \Log::info('DEBUG_EVALUATION_SUBMIT', [
                'stage_id' => $stage->id,
                'demande_stage_id' => $stage->demande_stage_id,
                'request_all' => $request->all(),
                'route_params' => $request->route()->parameters(),
                'user_id' => $user->id,
                'agent_id' => $agent->id,
            ]);

            // Si on veut évaluer un membre du groupe (membre_id fourni)
            $membreUserId = $request->input('membre_id');
            if ($membreUserId) {
                // Chercher le stage du membre dans le même groupe
                $stagiaireMembre = \App\Models\Stagiaire::where('user_id', $membreUserId)->first();
                $stageMembre = Stage::where('demande_stage_id', $stage->demande_stage_id)
                    ->where('stagiaire_id', $stagiaireMembre ? $stagiaireMembre->id_stagiaire : null)
                    ->first();
                \Log::info('DEBUG_EVAL_MAPPING', [
                    'demande_stage_id' => $stage->demande_stage_id,
                    'membre_user_id' => $membreUserId,
                    'stagiaire_id' => $stagiaireMembre ? $stagiaireMembre->id_stagiaire : null,
                    'stage_membre_id' => $stageMembre ? $stageMembre->id : null,
                ]);
                if (!$stageMembre) {
                    return response()->json(['success' => false, 'message' => 'Aucun stage trouvé pour ce membre.'], 404);
                }
                // Vérifier que le MS est bien affecté à un stage du groupe (même demande_stage_id)
                $affectation = AffectationMaitreStage::whereIn('stage_id', function($query) use ($stageMembre) {
                        $query->select('id')
                            ->from('stages')
                            ->where('demande_stage_id', $stageMembre->demande_stage_id);
                    })
                    ->where('maitre_stage_id', $user->id)
                    ->whereIn('statut', ['En cours', 'Acceptée'])
                    ->first();
                if (!$affectation) {
                    return response()->json(['success' => false, 'message' => 'Non autorisé.'], 403);
                }
                // Vérifier que le stage est terminé
                if ($stageMembre->statut !== 'Terminé') {
                    return response()->json(['success' => false, 'message' => 'Vous ne pouvez noter que les stages terminés.'], 403);
                }
                // Vérifier si une évaluation existe déjà
                $evaluationExistante = Evaluation::where('stage_id', $stageMembre->id)
                    ->where('agent_id', $agent->id)
                    ->first();
                if ($evaluationExistante) {
                    return response()->json(['success' => false, 'message' => 'Déjà évalué.'], 409);
                }
                // Valider les critères
                $validated = $request->validate([
                    'ponctualite' => 'required|numeric|min:0|max:2',
                    'motivation' => 'required|numeric|min:0|max:2',
                    'capacite_apprendre' => 'required|numeric|min:0|max:2',
                    'qualite_travail' => 'required|numeric|min:0|max:2',
                    'rapidite_execution' => 'required|numeric|min:0|max:2',
                    'jugement' => 'required|numeric|min:0|max:2',
                    'esprit_motivation' => 'required|numeric|min:0|max:2',
                    'esprit_collaboration' => 'required|numeric|min:0|max:2',
                    'sens_responsabilite' => 'required|numeric|min:0|max:2',
                    'communication' => 'required|numeric|min:0|max:2',
                    'note_totale' => 'required|numeric|min:0|max:20',
                    'commentaire_general' => 'nullable|string|max:1000',
                ]);
                $evaluation = Evaluation::updateOrCreate(
                    [
                        'stage_id' => $stageMembre->id,
                        'agent_id' => $agent->id
                    ],
                    array_merge($validated, [
                        'date_evaluation' => now()
                    ])
                );
                // Envoyer un mail à tous les stagiaires du groupe (demandeur et membres) pour notifier l'évaluation
                $stages = \App\Models\Stage::where('demande_stage_id', $stageMembre->demande_stage_id)->get();
                foreach ($stages as $s) {
                    if ($s->stagiaire && $s->stagiaire->user) {
                        try {
                            \Mail::to($s->stagiaire->user->email)
                                ->send(new \App\Mail\EvaluationNotifieeMail($s->stagiaire, $s, $evaluation));
                        } catch (\Exception $e) {
                            \Log::error('Erreur lors de l\'envoi du mail d\'évaluation', [
                                'error' => $e->getMessage(),
                                'stage_id' => $s->id
                            ]);
                        }
                    }
                }
                // (Notifier le stagiaire via la notification interne si nécessaire)
                if ($stageMembre->stagiaire && $stageMembre->stagiaire->user) {
                    $stagiaireUser = $stageMembre->stagiaire->user;
                    $stagiaireUser->notify(new \App\Notifications\StagiaireNotification(
                        'Votre stage a été évalué par votre maître de stage.',
                        route('stagiaire.stages.show', $stageMembre->id)
                    ));
                }
                return response()->json(['success' => true, 'message' => 'Évaluation enregistrée.']);
            }
            // ... sinon, comportement classique (stagiaire principal)
            // Vérifier que le MS est bien affecté à un stage du groupe (même demande_stage_id)
            $affectation = AffectationMaitreStage::whereIn('stage_id', function($query) use ($stage) {
                    $query->select('id')
                        ->from('stages')
                        ->where('demande_stage_id', $stage->demande_stage_id);
                })
                ->where('maitre_stage_id', $user->id)
                ->whereIn('statut', ['En cours', 'Acceptée'])
                ->first();

            if (!$affectation) {
                return response()->json(['success' => false, 'message' => 'Vous n\'êtes pas autorisé à noter ce stage.'], 403);
            }

            // Vérifier que le stage est terminé
            if ($stage->statut !== 'Terminé') {
                return response()->json(['success' => false, 'message' => 'Vous ne pouvez noter que les stages terminés.'], 403);
            }

            // Vérifier si une évaluation existe déjà pour ce stage et cet agent
            $evaluationExistante = Evaluation::where('stage_id', $stage->id)
                ->where('agent_id', $agent->id)
                ->first();
            if ($evaluationExistante) {
                return response()->json(['success' => false, 'message' => 'Vous avez déjà saisi une évaluation pour ce stage. La modification n\'est pas autorisée.'], 409);
            }

            // Valider tous les critères d'évaluation
            $validated = $request->validate([
                'ponctualite' => 'required|numeric|min:0|max:2',
                'motivation' => 'required|numeric|min:0|max:2',
                'capacite_apprendre' => 'required|numeric|min:0|max:2',
                'qualite_travail' => 'required|numeric|min:0|max:2',
                'rapidite_execution' => 'required|numeric|min:0|max:2',
                'jugement' => 'required|numeric|min:0|max:2',
                'esprit_motivation' => 'required|numeric|min:0|max:2',
                'esprit_collaboration' => 'required|numeric|min:0|max:2',
                'sens_responsabilite' => 'required|numeric|min:0|max:2',
                'communication' => 'required|numeric|min:0|max:2',
                'note_totale' => 'required|numeric|min:0|max:20',
                'commentaire_general' => 'nullable|string|max:1000',
            ]);

            // Créer ou mettre à jour l'évaluation pour ce stage et cet agent
            $evaluation = Evaluation::updateOrCreate(
                [
                    'stage_id' => $stage->id,
                    'agent_id' => $agent->id
                ],
                array_merge($validated, [
                    'date_evaluation' => now()
                ])
            );

            // Envoyer un mail à tous les stagiaires du groupe (demandeur et membres) pour notifier l'évaluation
            $stages = \App\Models\Stage::where('demande_stage_id', $stage->demande_stage_id)->get();
            foreach ($stages as $s) {
                if ($s->stagiaire && $s->stagiaire->user) {
                    try {
                        \Mail::to($s->stagiaire->user->email)
                            ->send(new \App\Mail\EvaluationNotifieeMail($s->stagiaire, $s, $evaluation));
                    } catch (\Exception $e) {
                        \Log::error('Erreur lors de l\'envoi du mail d\'évaluation', [
                            'error' => $e->getMessage(),
                            'stage_id' => $s->id
                        ]);
                    }
                }
            }

            // (Notifier le stagiaire via la notification interne si nécessaire)
            if ($stage->demandeStage && $stage->demandeStage->stagiaire && $stage->demandeStage->stagiaire->user) {
                $stagiaireUser = $stage->demandeStage->stagiaire->user;
                $stagiaireUser->notify(new \App\Notifications\StagiaireNotification(
                    'Votre stage a été évalué par votre maître de stage.',
                    route('stagiaire.stages.show', $stage->id)
                ));
            }

            Log::info('Évaluation enregistrée', [
                'stage_id' => $stage->id,
                'evaluation_id' => $evaluation->id,
                'agent_id' => $agent->id
            ]);

            return response()->json(['success' => true, 'message' => 'Évaluation enregistrée.']);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement de l\'évaluation', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'agent_id' => $agent->id
            ]);
            return response()->json(['success' => false, 'message' => 'Une erreur est survenue lors de l\'enregistrement de l\'évaluation.'], 500);
        }
    }

    /**
     * Récupérer les maîtres de stage des sous-structures pour la réaffectation
     */
    public function getMaitresStageSubstructures(Stage $stage)
    {
        $this->checkMSRole();
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Vérifier que l'utilisateur est bien le maître de stage assigné à ce stage
            $affectation = AffectationMaitreStage::where('stage_id', $stage->id)
                ->where('maitre_stage_id', $user->id)
                ->whereIn('statut', ['En cours', 'Acceptée']) // Vérifier que l'affectation est active
                ->first();

            if (!$affectation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à réaffecter ce stage.'
                ], 403);
            }

            // Récupérer la structure dont l'agent est responsable
            $structureResponsable = \App\Models\Structure::where('responsable_id', $agent->id)->first();

            if (!$structureResponsable) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas responsable d\'une structure.'
                ], 404);
            }

            // Récupérer les sous-structures
            $sousStructures = \App\Models\Structure::where('parent_id', $structureResponsable->id)->get();

            if ($sousStructures->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune sous-structure trouvée pour votre structure.'
                ], 404);
            }

            // Récupérer les agents MS des sous-structures
            $maitresStage = [];
            foreach ($sousStructures as $structure) {
                if ($structure->responsable && $structure->responsable->role_agent === 'MS') {
                    $maitresStage[] = [
                        'id' => $structure->responsable->user_id,
                        'nom' => $structure->responsable->user->nom,
                        'prenom' => $structure->responsable->user->prenom,
                        'structure' => [
                            'id' => $structure->id,
                            'libelle' => $structure->libelle,
                            'sigle' => $structure->sigle
                        ]
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'maitresStage' => $maitresStage
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des maîtres de stage des sous-structures', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'agent_id' => $agent->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la récupération des maîtres de stage.'
            ], 500);
        }
    }

    /**
     * Réaffecter un stage à un autre maître de stage
     */
    public function reaffecter(Request $request, Stage $stage)
    {
        $this->checkMSRole();
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Vérifier que l'utilisateur est bien le maître de stage assigné à ce stage
            $affectation = AffectationMaitreStage::where('stage_id', $stage->id)
                ->where('maitre_stage_id', $user->id)
                ->whereIn('statut', ['En cours', 'Acceptée']) // Vérifier que l'affectation est active
                ->first();

            if (!$affectation) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à réaffecter ce stage.');
            }

            // Valider la requête
            $validated = $request->validate([
                'nouveau_maitre_stage_id' => 'required|exists:users,id'
            ]);

            // Récupérer la structure dont l'agent est responsable
            $structureResponsable = \App\Models\Structure::where('responsable_id', $agent->id)->first();

            if (!$structureResponsable) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas responsable d\'une structure.');
            }

            // Récupérer les sous-structures
            $sousStructures = \App\Models\Structure::where('parent_id', $structureResponsable->id)->get();
            $sousStructureIds = $sousStructures->pluck('id')->toArray();

            // Vérifier que le nouveau maître de stage est bien responsable d'une sous-structure
            $nouveauMSAgent = \App\Models\Agent::where('user_id', $validated['nouveau_maitre_stage_id'])
                ->where('role_agent', 'MS')
                ->first();

            if (!$nouveauMSAgent) {
                return redirect()->back()->with('error', 'L\'utilisateur sélectionné n\'est pas un maître de stage.');
            }

            $structureNouveauMS = \App\Models\Structure::where('responsable_id', $nouveauMSAgent->id)->first();

            if (!$structureNouveauMS || !in_array($structureNouveauMS->id, $sousStructureIds)) {
                return redirect()->back()->with('error', 'Le maître de stage sélectionné n\'est pas responsable d\'une sous-structure valide.');
            }

            // Créer une nouvelle affectation
            $nouvelleAffectation = AffectationMaitreStage::create([
                'stage_id' => $stage->id,
                'maitre_stage_id' => $validated['nouveau_maitre_stage_id'],
                'agent_affectant_id' => $agent->id,
                'date_affectation' => now(),
                'statut' => 'Acceptée'
            ]);

            // Mettre à jour l'ancienne affectation
            $affectation->update([
                'statut' => 'Réaffectée'
            ]);

            // Après la réaffectation, notifier le stagiaire et les membres du groupe
            try {
                $stagiaire = $stage->demandeStage ? $stage->demandeStage->stagiaire : null;
                $nouveauMS = \App\Models\User::find($validated['nouveau_maitre_stage_id']);
                if ($stagiaire && $stagiaire->user && $nouveauMS) {
                    // Envoyer email de réaffectation
                    Mail::to($stagiaire->user->email)
                        ->send(new \App\Mail\AffectationMaitreStageMail($stagiaire->user, $stage, $nouveauMS, true));
                    
                    // Envoyer notification in-app
                    $stagiaire->user->notify(new \App\Notifications\AffectationMaitreStageNotification($stage, $nouveauMS, true));
                }
                $demande = $stage->demandeStage;
                if ($demande && $demande->nature === 'Groupe' && $demande->membres) {
                    foreach ($demande->membres as $membre) {
                        if ($membre->user && $membre->user->id !== $stagiaire->user->id) {
                            // Envoyer email de réaffectation
                            Mail::to($membre->user->email)
                                ->send(new \App\Mail\AffectationMaitreStageMail($membre->user, $stage, $nouveauMS, true));
                            
                            // Envoyer notification in-app
                            $membre->user->notify(new \App\Notifications\AffectationMaitreStageNotification($stage, $nouveauMS, true));
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Erreur lors de l\'envoi de l\'email et notification de réaffectation', [
                    'error' => $e->getMessage(),
                    'stage_id' => $stage->id
                ]);
            }

            Log::info('Stage réaffecté', [
                'stage_id' => $stage->id,
                'ancien_ms_id' => $user->id,
                'nouveau_ms_id' => $validated['nouveau_maitre_stage_id'],
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('success', 'Le stage a été réaffecté avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la réaffectation du stage', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la réaffectation du stage.');
        }
    }

    /**
     * Retourne toutes les propositions de thèmes pour un stage (pour le MS)
     */
    public function getThemesProposes(Stage $stage)
    {
        // Correction : s'assurer que le thème validé a bien le champ stage_id renseigné
        if ($stage->theme_stage_id) {
            $themeValide = \App\Models\ThemeStage::find($stage->theme_stage_id);
            if ($themeValide && $themeValide->stage_id !== $stage->id) {
                $themeValide->stage_id = $stage->id;
                $themeValide->save();
            }
        }
        // Récupérer tous les stages du même groupe (même demande_stage_id)
        $stageIds = \App\Models\Stage::where('demande_stage_id', $stage->demande_stage_id)->pluck('id');
        // Récupérer tous les thèmes liés à ces stages
        $themes = \App\Models\ThemeStage::whereIn('stage_id', $stageIds)
            ->with('user')
            ->orderByDesc('created_at')
            ->get();
        return response()->json(['success' => true, 'themes' => $themes]);
    }

    /**
     * Valider ou refuser une proposition de thème
     */
    public function validerOuRefuserTheme(Request $request, Stage $stage, ThemeStage $theme)
    {
        $user = Auth::user();
        $agent = $user->agent;
        // Vérifier que l'utilisateur est bien le MS du stage
        $affectation = $stage->affectationsMaitreStage()->where('maitre_stage_id', $user->id)->first();
        if (!$affectation) {
            return response()->json(['success' => false, 'message' => 'Non autorisé.'], 403);
        }
        $validated = $request->validate([
            'action' => 'required|in:valider,refuser',
        ]);
        if ($validated['action'] === 'valider') {
            $theme->etat = 'Validé';
            $theme->save();
            // Mettre à jour le thème validé pour tous les stages du groupe
            \App\Models\Stage::where('demande_stage_id', $stage->demande_stage_id)
                ->update(['theme_stage_id' => $theme->id]);
            // Envoi d'un mail à tous les stagiaires du groupe
            $stages = \App\Models\Stage::where('demande_stage_id', $stage->demande_stage_id)->get();
            foreach ($stages as $stageMembre) {
                if ($stageMembre->stagiaire && $stageMembre->stagiaire->user) {
                    \Mail::to($stageMembre->stagiaire->user->email)
                        ->send(new \App\Mail\ThemeProposeMail($stageMembre->stagiaire, $stageMembre, $theme));
                }
            }
        } else {
            $theme->etat = 'Refusé';
            $theme->save();
        }
        return response()->json(['success' => true, 'theme' => $theme]);
    }

    /**
     * Permettre au MS de proposer un thème (validé d'office)
     */
    public function proposerTheme(Request $request, Stage $stage)
    {
        $user = Auth::user();
        $agent = $user->agent;
        // Vérifier que l'utilisateur est bien le MS du stage
        $affectation = $stage->affectationsMaitreStage()->where('maitre_stage_id', $user->id)->first();
        if (!$affectation) {
            return response()->json(['success' => false, 'message' => 'Non autorisé.'], 403);
        }
        $validated = $request->validate([
            'intitule' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'mots_cles' => 'nullable|string|max:255',
        ]);
        $theme = $stage->themesProposes()->create([
            'intitule' => $validated['intitule'],
            'description' => $validated['description'],
            'etat' => 'Validé',
            'mots_cles' => $validated['mots_cles'] ?? null,
            'propose_par' => 'ms',
            'user_id' => $user->id, // Indiquer que c'est le MS qui a proposé le thème
        ]);
        // Associer ce thème comme thème validé du stage
        $stage->theme_stage_id = $theme->id;
        $stage->save();
        // Envoi d'un mail à tous les stagiaires du groupe
        $stages = \App\Models\Stage::where('demande_stage_id', $stage->demande_stage_id)->get();
        foreach ($stages as $stageMembre) {
            if ($stageMembre->stagiaire && $stageMembre->stagiaire->user) {
                \Mail::to($stageMembre->stagiaire->user->email)
                    ->send(new \App\Mail\ThemeProposeMail($stageMembre->stagiaire, $stageMembre, $theme));
            }
        }
        return response()->json(['success' => true, 'theme' => $theme]);
    }

    /**
     * Récupérer l'évaluation individuelle d'un membre du groupe pour un stage (lecture seule)
     */
    public function getEvaluationMembre(Stage $stage, $membreId)
    {
        $this->checkMSRole();
        // On cherche le stage du membre dans le même groupe
        $stageMembre = Stage::where('demande_stage_id', $stage->demande_stage_id)
            ->where('stagiaire_id', function($query) use ($membreId) {
                $query->select('id_stagiaire')
                    ->from('stagiaires')
                    ->where('user_id', $membreId)
                    ->limit(1);
            })
            ->first();
        if (!$stageMembre) {
            return response()->json(['success' => false, 'message' => 'Aucun stage trouvé pour ce membre.'], 404);
        }
        $evaluation = $stageMembre->evaluation;
        if (!$evaluation) {
            return response()->json(['success' => true, 'evaluation' => null]);
        }
        return response()->json(['success' => true, 'evaluation' => $evaluation]);
    }

    public function confirmerFinStage(Stage $stage)
    {
        Log::info('APPEL confirmerFinStage', [
            'id' => $stage->id,
            'statut' => $stage->statut,
            'date_fin' => $stage->date_fin,
            'termine_par_ms' => $stage->termine_par_ms,
            'date_confirmation_ms' => $stage->date_confirmation_ms,
        ]);

        // Vérifier que le stage est bien en cours
        if ($stage->statut !== 'En cours') {
            return response()->json([
                'success' => false,
                'message' => 'Le stage doit être en cours pour être terminé.'
            ], 400);
        }

        // Vérifier que la date de fin est atteinte
        if (now()->lt($stage->date_fin)) {
            return response()->json([
                'success' => false,
                'message' => 'La date de fin du stage n\'est pas encore atteinte.'
            ], 400);
        }

        // Mettre à jour tous les stages du même groupe (y compris celui-ci)
        \App\Models\Stage::where('demande_stage_id', $stage->demande_stage_id)
            ->update([
                'termine_par_ms' => true,
                'date_confirmation_ms' => now(),
                'statut' => 'Terminé'
            ]);

        // Envoyer un mail à tous les stagiaires du groupe
        $stages = \App\Models\Stage::where('demande_stage_id', $stage->demande_stage_id)->get();
        foreach ($stages as $stageMembre) {
            if ($stageMembre->stagiaire && $stageMembre->stagiaire->user) {
                try {
                    \Mail::to($stageMembre->stagiaire->user->email)
                        ->send(new \App\Mail\StageTermineMail($stageMembre));
                } catch (\Exception $e) {
                    \Log::error('Erreur lors de l\'envoi du mail de fin de stage', [
                        'error' => $e->getMessage(),
                        'stage_id' => $stageMembre->id
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Le stage a été marqué comme terminé avec succès.'
        ]);
    }
}
