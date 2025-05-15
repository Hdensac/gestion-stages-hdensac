<?php

namespace App\Http\Controllers\Agent\MS;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\AffectationMaitreStage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

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
                    'stage.affectationsMaitreStage.maitreStage.agent.structuresResponsable'
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
                        ->with(['maitreStage', 'maitreStage.agent.structuresResponsable'])
                        ->first();

                    if ($nouvelleAffectation && $nouvelleAffectation->maitreStage) {
                        $nouveauMS = $nouvelleAffectation->maitreStage;

                        // Récupérer l'agent associé à l'utilisateur
                        $nouveauMSAgent = \App\Models\Agent::where('user_id', $nouveauMS->id)->first();

                        if ($nouveauMSAgent) {
                            // Récupérer la structure dont l'agent est responsable
                            $structure = \App\Models\Structure::where('responsable_id', $nouveauMSAgent->id)->first();

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
                }

                return $stage;
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
            $stagesArray = array_values($stagesWithStagiaires->toArray());

            return Inertia::render('Agent/MS/Stages/Index', [
                'stages' => $stagesArray
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
                    ->with(['maitreStage', 'maitreStage.agent.structuresResponsable'])
                    ->first();

                if ($nouvelleAffectation && $nouvelleAffectation->maitreStage) {
                    $nouveauMS = $nouvelleAffectation->maitreStage;

                    // Récupérer l'agent associé à l'utilisateur
                    $nouveauMSAgent = \App\Models\Agent::where('user_id', $nouveauMS->id)->first();

                    if ($nouveauMSAgent) {
                        // Récupérer la structure dont l'agent est responsable
                        $structure = \App\Models\Structure::where('responsable_id', $nouveauMSAgent->id)->first();

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
            }

            // Charger les relations nécessaires
            $stage->load([
                'demandeStage.stagiaire.user',
                'structure',
                'themeStage'
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
                'stage' => $stage,
                'openContact' => $request->boolean('openContact'),
                'success' => session('success'),
                'error' => session('error')
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
            // Vérifier que l'utilisateur est bien le maître de stage assigné à ce stage
            $affectation = AffectationMaitreStage::where('stage_id', $stage->id)
                ->where('maitre_stage_id', $user->id)
                ->whereIn('statut', ['En cours', 'Acceptée']) // Vérifier que l'affectation est active
                ->first();

            if (!$affectation) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à noter ce stage.');
            }

            // Vérifier que le stage est terminé
            if ($stage->statut !== 'Terminé') {
                return redirect()->back()->with('error', 'Vous ne pouvez noter que les stages terminés.');
            }

            // Valider la note
            $validated = $request->validate([
                'note' => 'required|numeric|min:0|max:20',
                'commentaire' => 'nullable|string|max:1000'
            ]);

            // Mettre à jour la note du stage
            $stage->update([
                'note' => $validated['note'],
                'commentaire_evaluation' => $validated['commentaire']
            ]);

            Log::info('Stage noté', [
                'stage_id' => $stage->id,
                'note' => $validated['note'],
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('success', 'Le stage a été noté avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la notation du stage', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la notation du stage.');
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
            AffectationMaitreStage::create([
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
}
