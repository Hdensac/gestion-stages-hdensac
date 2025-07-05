<?php

namespace App\Http\Controllers\Agent\RS;

use App\Http\Controllers\Controller;
use App\Models\DemandeStage;
use App\Models\Structure;
use App\Models\Agent;
use App\Mail\DemandeAcceptationMail;
use App\Mail\DemandeRefusMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use App\Notifications\StagiaireNotification;
use Illuminate\Support\Facades\DB;
use App\Models\AffectationMaitreStage;
use App\Mail\AffectationMaitreStageMail;

class DemandeController extends Controller
{
    public function __construct()
    {
        // Vérification déplacée dans chaque méthode
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $agent = $user->agent;

        try {
            $structure = Structure::where('responsable_id', $agent->id)->first();

            if (!$structure) {
                return redirect()->back()->with('error', 'Vous n\'êtes responsable d\'aucune structure.');
            }

            // Récupérer les IDs des demandes dont la dernière affectation pointe vers la structure du RS courant
            $dernieresAffectations = \App\Models\AffectationResponsableStructure::select('id_demande_stages', DB::raw('MAX(id) as max_id'))
                ->groupBy('id_demande_stages');

            $idsDemandesActivesPourRS = \App\Models\AffectationResponsableStructure::whereIn('id', $dernieresAffectations->pluck('max_id'))
                ->where('structure_id', $structure->id)
                ->pluck('id_demande_stages')
                ->toArray();

            // Construction de la requête de base (demandes dont la dernière affectation est pour ce RS)
            $query = DemandeStage::with(['stagiaire.user'])
                ->whereIn('id', $idsDemandesActivesPourRS)
                ->orderByDesc('updated_at');

            // Ajouter la relation avec les affectations pour récupérer la date d'affectation
            $query->with(['affectations' => function($query) use ($structure) {
                $query->where('structure_id', $structure->id)
                      ->orderBy('date_affectation', 'desc')
                      ->orderBy('created_at', 'desc');
            }]);

            // Filtre par statut
            if ($request->filled('status')) {
                $status = $request->status;
                $statusVariants = [$status];
                if ($status === 'En cours') {
                    $statusVariants[] = 'Encours';
                    $statusVariants[] = 'en cours';
                    $statusVariants[] = 'en_cours';
                    $statusVariants[] = 'EN COURS';
                }
                $query->whereIn('statut', $statusVariants);
            }

            // Filtre par recherche
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('stagiaire.user', function ($q) use ($search) {
                    $q->where(function ($subQuery) use ($search) {
                        $subQuery->where('nom', 'like', "%{$search}%")
                                ->orWhere('prenom', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                    });
                });
            }

            // Filtre par période
            if ($request->filled('period')) {
                $now = now();
                switch ($request->period) {
                    case 'today':
                        $query->whereDate('created_at', $now->toDateString());
                        break;
                    case 'week':
                        $query->whereBetween('created_at', [
                            $now->startOfWeek()->toDateTimeString(),
                            $now->endOfWeek()->toDateTimeString()
                        ]);
                        break;
                    case 'month':
                        $query->whereMonth('created_at', $now->month)
                              ->whereYear('created_at', $now->year);
                        break;
                    case 'year':
                        $query->whereYear('created_at', $now->year);
                        break;
                }
            }

            // Récupération des demandes avec pagination
            $demandes = $query->latest()->paginate(10)->withQueryString();

            // Injecter la date d'affectation (created_at de la dernière affectation) et le mapping du maître de stage pour chaque demande
            $demandes->getCollection()->transform(function ($demande) {
                $affectation = \App\Models\AffectationResponsableStructure::where('id_demande_stages', $demande->id)
                    ->orderByDesc('created_at')
                    ->orderByDesc('id')
                    ->first();
                $demande->date_affectation = $affectation ? $affectation->created_at : null;

                // Ajout du mapping maître de stage harmonisé
                $stage = \App\Models\Stage::where('demande_stage_id', $demande->id)->first();
                $affectationMaitreStage = null;
                if ($stage) {
                    $affectationMaitreStage = \App\Models\AffectationMaitreStage::where('stage_id', $stage->id)
                        ->orderByDesc('date_affectation')
                        ->orderByDesc('id')
                        ->first();
                }
                if ($affectationMaitreStage && $affectationMaitreStage->maitreStage) {
                    $demande->maitre_stage_nom = $affectationMaitreStage->maitreStage->nom;
                    $demande->maitre_stage_prenom = $affectationMaitreStage->maitreStage->prenom;
                    $demande->maitre_stage_email = $affectationMaitreStage->maitreStage->email;
                } else {
                    $demande->maitre_stage_nom = null;
                    $demande->maitre_stage_prenom = null;
                    $demande->maitre_stage_email = null;
                }
                return $demande;
            });

            return Inertia::render('Agent/RS/Demandes/Index', [
                'demandes' => $demandes,
                'structure' => $structure,
                'filters' => $request->only(['status', 'search', 'period', 'page'])
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des demandes RS', [
                'error' => $e->getMessage(),
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement des demandes.');
        }
    }

    public function show(DemandeStage $demande)
    {
        // Log pour vérifier si la méthode show est appelée
        Log::info('Agent RS Demande Show Method Called', ['demande_id' => $demande]);

        $user = Auth::user();
        $agent = $user->agent;
        $structure = Structure::where('responsable_id', $agent->id)->first();
        // Récupérer tous les IDs de la structure principale et de ses sous-structures
        $getAllSubStructureIds = function($parentId) use (&$getAllSubStructureIds) {
            $ids = [$parentId];
            $children = Structure::where('parent_id', $parentId)->pluck('id');
            foreach ($children as $childId) {
                $ids = array_merge($ids, $getAllSubStructureIds($childId));
            }
            return $ids;
        };
        $allStructureIds = $structure ? $getAllSubStructureIds($structure->id) : [];
        // Récupérer tous les agents MS de ces structures
        $agentsMS = \App\Models\Agent::with('user')
            ->where('role_agent', 'MS')
            ->whereIn('structure_id', $allStructureIds)
            ->get();

        try {
            // Vérifier si la demande est directement liée à la structure ou affectée via la table d'affectation
            $estAffectee = \App\Models\AffectationResponsableStructure::where('structure_id', $structure->id)
                ->where('id_demande_stages', $demande->id)
                ->exists();

            if (!$structure || ($demande->structure_id !== $structure->id && !$estAffectee)) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à cette demande.');
            }

            // Charger les relations nécessaires
            $demande->load(['stagiaire.user', 'structure', 'membres.user', 'affectations.structure']);

            // Toujours utiliser la structure d'affectation la plus récente si elle existe
            $affectation = \App\Models\AffectationResponsableStructure::where('id_demande_stages', $demande->id)
                ->orderByDesc('date_affectation')
                ->orderByDesc('id')
                    ->with('structure')
                    ->first();

                if ($affectation && $affectation->structure) {
                $demande->setRelation('structure', $affectation->structure);
                Log::info('Structure d\'affectation utilisée', [
                    'demande_id' => $demande->id,
                    'structure_id' => $affectation->structure->id,
                    'structure_libelle' => $affectation->structure->libelle
                ]);
            } else {
                Log::info('Structure d\'origine utilisée', [
                    'demande_id' => $demande->id,
                    'structure_id' => $demande->structure ? $demande->structure->id : null,
                    'structure_libelle' => $demande->structure ? $demande->structure->libelle : null
                ]);
            }

            // Ajout : vérifier si un maître de stage est déjà affecté
            $stage = \App\Models\Stage::where('demande_stage_id', $demande->id)->first();
            $maitre_stage_deja_affecte = false;
            $maitreStageAffecte = null;
            $affectation_maitre_stage = null;
            if ($stage) {
                $maitre_stage_deja_affecte = \App\Models\AffectationMaitreStage::where('stage_id', $stage->id)
                    ->whereIn('statut', ['En cours', 'Acceptée'])
                    ->exists();
                $affectationMaitreStage = \App\Models\AffectationMaitreStage::where('stage_id', $stage->id)
                    ->orderByDesc('date_affectation')
                    ->orderByDesc('id')
                    ->first();
                if ($affectationMaitreStage && $affectationMaitreStage->maitreStage) {
                    $affectation_maitre_stage = [
                        'nom' => $affectationMaitreStage->maitreStage->nom,
                        'prenom' => $affectationMaitreStage->maitreStage->prenom,
                        'email' => $affectationMaitreStage->maitreStage->email,
                    ];
                }
            }

            // Ajout mapping maître de stage harmonisé
            $stage = \App\Models\Stage::where('demande_stage_id', $demande->id)->first();
            $affectationMaitreStage = null;
            $maitre_stage_nom = null;
            $maitre_stage_prenom = null;
            $maitre_stage_email = null;
            if ($stage) {
                $affectationMaitreStage = \App\Models\AffectationMaitreStage::where('stage_id', $stage->id)
                    ->orderByDesc('date_affectation')
                    ->orderByDesc('id')
                        ->first();
                if ($affectationMaitreStage && $affectationMaitreStage->maitreStage) {
                    $maitre_stage_nom = $affectationMaitreStage->maitreStage->nom;
                    $maitre_stage_prenom = $affectationMaitreStage->maitreStage->prenom;
                    $maitre_stage_email = $affectationMaitreStage->maitreStage->email;
                }
            }

            // Après avoir potentiellement modifié $demande->structure, convertir en tableau pour Inertia
            return Inertia::render('Agent/RS/Demandes/Show', [
                'demande' => $demande->toArray(),
                'membres' => $demande->nature === 'Groupe' ? $demande->membres : [],
                'agentsMS' => $agentsMS,
                'maitre_stage_deja_affecte' => $maitre_stage_deja_affecte,
                'maitreStageAffecte' => $maitreStageAffecte,
                'structure' => $structure,
                'affectation_maitre_stage' => $affectation_maitre_stage,
                'maitre_stage_nom' => $maitre_stage_nom,
                'maitre_stage_prenom' => $maitre_stage_prenom,
                'maitre_stage_email' => $maitre_stage_email,
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage de la demande RS', [
                'error' => $e->getMessage(),
                'agent_id' => $agent->id,
                'demande_id' => $demande->id
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement de la demande.');
        }
    }

    public function approve(DemandeStage $demande)
    {
        $user = Auth::user();
        $agent = $user->agent;

        try {
            $structure = Structure::where('responsable_id', $agent->id)->first();

            // Vérifier si la demande est directement liée à la structure ou affectée via la table d'affectation
            $estAffectee = \App\Models\AffectationResponsableStructure::where('structure_id', $structure->id)
                ->where('id_demande_stages', $demande->id)
                ->exists();

            if (!$structure || ($demande->structure_id !== $structure->id && !$estAffectee)) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à cette demande.');
            }

            // Charger les relations nécessaires pour l'email
            $demande->load(['stagiaire.user', 'structure', 'affectations.structure']);

            // Si la demande est affectée mais n'a pas de structure directe, utiliser la structure de l'affectation
            if (!$demande->structure && $estAffectee) {
                $affectation = \App\Models\AffectationResponsableStructure::where('structure_id', $structure->id)
                    ->where('id_demande_stages', $demande->id)
                    ->with('structure')
                    ->first();

                if ($affectation && $affectation->structure) {
                    // Attacher temporairement la structure de l'affectation à la demande
                    $demande->structure = $affectation->structure;
                }
            }

            // Charger les relations nécessaires pour l'email et la création du stage
            $demande->load(['stagiaire.user', 'structure', 'affectations.structure', 'membres.user']);

            // Journaliser l'état actuel de la demande
            Log::info('État actuel de la demande avant approbation', [
                'demande_id' => $demande->id,
                'statut_actuel' => $demande->statut,
                'structure_id' => $demande->structure_id
            ]);

            // Vérifier si le statut est "Encours" et le corriger en "En cours"
            if ($demande->statut === 'Encours') {
                Log::info('Correction du statut "Encours" en "En cours"', [
                    'demande_id' => $demande->id
                ]);

                try {
                    \Illuminate\Support\Facades\DB::table('demande_stages')
                        ->where('id', $demande->id)
                        ->update([
                            'statut' => 'En cours',
                            'updated_at' => now()
                        ]);

                    $demande->refresh();
                    // Recharger les relations après le refresh
                    $demande->load(['stagiaire.user', 'structure', 'affectations.structure', 'membres.user']);
                } catch (\Exception $e) {
                    Log::error('Erreur lors de la correction du statut', [
                        'demande_id' => $demande->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Mettre à jour le statut de la demande avec une requête SQL directe
            try {
                $updateResult = \Illuminate\Support\Facades\DB::table('demande_stages')
                    ->where('id', $demande->id)
                    ->update([
                        'statut' => 'Acceptée',
                        'date_traitement' => now(),
                        'traite_par' => $agent->id,
                        'updated_at' => now()
                    ]);

                Log::info('Résultat de la mise à jour directe', [
                    'demande_id' => $demande->id,
                    'update_result' => $updateResult
                ]);

                if ($updateResult === 0) {
                    Log::error('Échec de la mise à jour du statut de la demande (SQL direct)', [
                        'demande_id' => $demande->id
                    ]);
                    return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du statut de la demande.');
                }
            } catch (\Exception $e) {
                Log::error('Exception lors de la mise à jour du statut de la demande', [
                    'demande_id' => $demande->id,
                    'error' => $e->getMessage()
                ]);
                return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du statut de la demande: ' . $e->getMessage());
            }

            // Vérifier que la mise à jour a bien été effectuée et recharger les relations
            $demande->refresh();
            $demande->load(['stagiaire.user', 'structure', 'affectations.structure', 'membres.user']);

            // Mettre à jour manuellement le statut dans l'objet demande pour s'assurer qu'il est correct
            $demande->statut = 'Acceptée';

            Log::info('État de la demande après approbation', [
                'demande_id' => $demande->id,
                'nouveau_statut' => $demande->statut,
                'stagiaire_id' => $demande->stagiaire->id ?? 'non défini',
                'stagiaire_email' => $demande->stagiaire->user->email ?? 'non défini'
            ]);

            // Créer un stage à partir de la demande acceptée
            try {
                // Journaliser les données avant la création
                Log::info('Données pour la création du stage', [
                    'demande_id' => $demande->id,
                    'structure_id' => $demande->structure_id,
                    'date_debut' => $demande->date_debut,
                    'date_fin' => $demande->date_fin,
                    'type' => $demande->type
                ]);

                // S'assurer que structure_id est défini, sinon utiliser la structure du RS
                if (!$demande->structure_id && $structure) {
                    Log::info('Structure ID manquante, utilisation de la structure du RS', [
                        'structure_id_rs' => $structure->id
                    ]);
                    $demande->structure_id = $structure->id;
                }

                // Mapping explicite pour correspondre à l'ENUM MySQL
                $typeMap = [
                    'academique' => 'academique',
                    'académique' => 'academique',
                    'professionnel' => 'professionnel',
                    'professionnelle' => 'professionnel',
                ];
                $typeFormatted = strtolower(str_replace('é', 'e', $demande->type));
                $typeFormatted = $typeMap[$typeFormatted] ?? 'academique'; // valeur par défaut si non trouvée
                Log::info('Type envoyé à la base', ['type' => $typeFormatted, 'demande_type' => $demande->type]);

                if ($demande->nature === 'Groupe') {
                    // Récupérer tous les user_id des membres du groupe (table membre_groupes)
                    $userIds = \DB::table('membre_groupes')
                        ->where('demande_stage_id', $demande->id)
                        ->pluck('user_id')
                        ->toArray();

                    // Ajouter le demandeur principal s'il n'est pas déjà dans la liste
                    if (!in_array($demande->stagiaire->user_id, $userIds)) {
                        $userIds[] = $demande->stagiaire->user_id;
                    }

                    Log::info('Liste des user_id pour création des stages', [
                        'demande_id' => $demande->id,
                        'userIds' => $userIds,
                        'demandeur_principal' => $demande->stagiaire->user_id,
                    ]);

                    // Récupérer les infos du demandeur principal pour les membres
                    $refStagiaire = $demande->stagiaire;

                    foreach ($userIds as $userId) {
                        $stagiaire = \App\Models\Stagiaire::where('user_id', $userId)->first();
                        if (!$stagiaire) continue;
                        $existingStage = \App\Models\Stage::where('demande_stage_id', $demande->id)
                            ->where('stagiaire_id', $stagiaire->id_stagiaire)
                            ->first();
                        if (!$existingStage) {
                            \App\Models\Stage::create([
                                'demande_stage_id' => $demande->id,
                                'stagiaire_id' => $stagiaire->id_stagiaire,
                                'structure_id' => $demande->structure_id,
                                'date_debut' => $demande->date_debut,
                                'date_fin' => $demande->date_fin,
                                'type' => $typeFormatted,
                                'statut' => 'En cours',
                            ]);
                        }
                    }
                } else {
                    // Pour une demande individuelle, créer un seul stage
                    $existingStage = \App\Models\Stage::where('demande_stage_id', $demande->id)->first();

                    if (!$existingStage) {
                        $stage = \App\Models\Stage::create([
                            'demande_stage_id' => $demande->id,
                            'structure_id' => $demande->structure_id,
                            'date_debut' => $demande->date_debut,
                            'date_fin' => $demande->date_fin,
                            'statut' => 'En cours',
                            'type' => $typeFormatted,
                            'stagiaire_id' => $demande->stagiaire_id
                        ]);

                        Log::info('Stage créé pour une demande individuelle', [
                            'stage_id' => $stage->id,
                            'demande_stage_id' => $demande->id,
                            'stagiaire_id' => $demande->stagiaire_id
                        ]);
                    } else {
                        $stage = $existingStage;
                    }
                }

                // Vérifier que le stage a bien été créé
                if (!$stage) {
                    Log::error('Échec de la création du stage', [
                        'demande_id' => $demande->id,
                        'structure_id' => $demande->structure_id
                    ]);
                    throw new \Exception('Échec de la création du stage');
                }

                $stageCheck = \App\Models\Stage::find($stage->id);

                Log::info('Vérification de la création du stage', [
                    'stage_id' => $stage->id,
                    'demande_stage_id' => $demande->id,
                    'structure_id' => $demande->structure_id,
                    'stage_exists' => $stageCheck ? 'oui' : 'non'
                ]);

                // Vérifier tous les stages dans la base de données
                $allStages = \App\Models\Stage::all();
                Log::info('Nombre total de stages dans la base de données', [
                    'count' => $allStages->count(),
                    'ids' => $allStages->pluck('id')->toArray()
                ]);

            } catch (\Exception $e) {
                Log::error('Erreur lors de la création du stage', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'demande_id' => $demande->id
                ]);

                // Essayer une approche alternative avec une requête SQL directe
                try {
                    // Convertir le type au format attendu par la base de données
                    $typeFormatted = strtolower(str_replace('é', 'e', $demande->type));

                    Log::info('Type formaté pour la création du stage (SQL direct)', [
                        'type_original' => $demande->type,
                        'type_formatted' => $typeFormatted
                    ]);

                    $stageId = \Illuminate\Support\Facades\DB::table('stages')->insertGetId([
                        'demande_stage_id' => $demande->id,
                        'structure_id' => $demande->structure_id,
                        'date_debut' => $demande->date_debut,
                        'date_fin' => $demande->date_fin,
                        'statut' => 'En cours',
                        'type' => $typeFormatted,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    Log::info('Stage créé avec une requête SQL directe', [
                        'stage_id' => $stageId,
                        'demande_id' => $demande->id
                    ]);
                } catch (\Exception $innerException) {
                    Log::error('Échec de la création du stage même avec une requête SQL directe', [
                        'error' => $innerException->getMessage(),
                        'demande_id' => $demande->id
                    ]);
                }
            }

            // Envoyer l'email au stagiaire
            try {
                // Vérifier que le stagiaire et son utilisateur sont bien chargés
                if ($demande->stagiaire && $demande->stagiaire->user) {
                    Log::info('Envoi d\'email d\'acceptation au stagiaire', [
                        'email' => $demande->stagiaire->user->email,
                        'nom' => $demande->stagiaire->user->nom,
                        'prenom' => $demande->stagiaire->user->prenom
                    ]);

                    Mail::to($demande->stagiaire->user->email)
                        ->send(new DemandeAcceptationMail($demande, $demande->stagiaire->user));
                } else {
                    Log::error('Impossible d\'envoyer l\'email : stagiaire ou utilisateur non défini', [
                        'demande_id' => $demande->id,
                        'stagiaire' => $demande->stagiaire ? 'défini' : 'non défini',
                        'user' => ($demande->stagiaire && $demande->stagiaire->user) ? 'défini' : 'non défini'
                    ]);
                }

                // Si c'est une demande de groupe, envoyer aux membres aussi
                if ($demande->nature === 'Groupe' && $demande->membres) {
                    foreach ($demande->membres as $membre) {
                        if ($membre['user'] && $membre['user']['email'] && $demande->stagiaire && $demande->stagiaire->user && $membre['user']['email'] !== $demande->stagiaire->user->email) {
                            Log::info('Envoi d\'email d\'acceptation à un membre du groupe', [
                                'email' => $membre['user']['email'],
                                'nom' => $membre['user']['nom'],
                                'prenom' => $membre['user']['prenom']
                            ]);

                            Mail::to($membre['user']['email'])
                                ->send(new DemandeAcceptationMail($demande, $membre['user']));
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de l\'email d\'acceptation', [
                    'error' => $e->getMessage(),
                    'demande_id' => $demande->id,
                    'trace' => $e->getTraceAsString()
                ]);
                // On continue même si l'email échoue
            }

            // Après l'envoi de l'email d'acceptation, notifier le stagiaire et les membres du groupe
            try {
                if ($demande->stagiaire && $demande->stagiaire->user) {
                    $demande->stagiaire->user->notify(new StagiaireNotification(
                        'Votre demande de stage a été acceptée !',
                        route('stagiaire.stages')
                    ));
                }
                if ($demande->nature === 'Groupe' && $demande->membres) {
                    foreach ($demande->membres as $membre) {
                        if ($membre['user'] && $demande->stagiaire && $demande->stagiaire->user && $membre['user']['id'] !== $demande->stagiaire->user->id) {
                            $membre['user']->notify(new StagiaireNotification(
                                'Votre demande de stage a été acceptée (groupe) !',
                                route('stagiaire.stages')
                            ));
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Erreur lors de l\'envoi de la notification Laravel', [
                    'error' => $e->getMessage(),
                    'demande_id' => $demande->id
                ]);
            }

            return redirect()->back()->with('success', 'La demande a été acceptée avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'approbation de la demande RS', [
                'error' => $e->getMessage(),
                'agent_id' => $agent->id,
                'demande_id' => $demande->id
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'approbation de la demande.');
        }
    }

    public function reject(Request $request, DemandeStage $demande)
    {
        $user = Auth::user();
        $agent = $user->agent;

        try {
            $structure = Structure::where('responsable_id', $agent->id)->first();

            // Vérifier si la demande est directement liée à la structure ou affectée via la table d'affectation
            $estAffectee = \App\Models\AffectationResponsableStructure::where('structure_id', $structure->id)
                ->where('id_demande_stages', $demande->id)
                ->exists();

            if (!$structure || ($demande->structure_id !== $structure->id && !$estAffectee)) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à cette demande.');
            }

            // Valider le motif de refus
            $validated = $request->validate([
                'motif_refus' => 'required|string|max:500'
            ]);

            // Mettre à jour le statut et le motif de refus, sans notification ni mail
            $demande->statut = 'A réaffecter';
            $demande->motif_refus = $validated['motif_refus'];
            $demande->date_traitement = now();
            $demande->traite_par = $agent->id;
            $demande->save();

            Log::info('Demande refusée par RS, en attente de réaffectation', [
                'demande_id' => $demande->id,
                'statut' => $demande->statut,
                'motif_refus' => $demande->motif_refus
            ]);

            return redirect()->back()->with('success', 'La demande a été refusée par le RS et envoyée à l\'agent pour réaffectation.');
        } catch (\Exception $e) {
            Log::error('Erreur lors du refus de la demande RS', [
                'error' => $e->getMessage(),
                'agent_id' => $agent->id,
                'demande_id' => $demande->id
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors du refus de la demande.');
        }
    }

    /**
     * Récupérer les agents avec le rôle MS (maître de stage)
     */
    public function getResponsableAgents()
    {
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Récupérer la structure dont l'agent est responsable
            $structure = Structure::where('responsable_id', $agent->id)->first();

            if (!$structure) {
                return response()->json([], 200);
            }

            // Récupérer les IDs de la structure principale et de ses sous-structures
            $structureIds = Structure::where('parent_id', $structure->id)
                ->orWhere('id', $structure->id)
                ->pluck('id');

            // Récupérer les agents avec le rôle MS appartenant à la structure principale ET à ses sous-structures
            $msAgents = Agent::with(['user', 'structuresResponsable'])
                ->where('role_agent', 'MS')
                ->whereIn('structure_id', $structureIds)
                ->get()
                ->map(function ($agent) {
                    // Ajouter la structure dont l'agent est responsable (si applicable)
                    if ($agent->structuresResponsable()->exists()) {
                        $agent->structure_responsable = $agent->structuresResponsable->first();
                    } else {
                        $agent->structure_responsable = null;
                    }
                    return $agent;
                });

            // Journaliser le nombre d'agents trouvés pour le débogage
            Log::info('Agents MS trouvés', [
                'count' => $msAgents->count(),
                'structure_ids' => $structureIds->toArray(),
                'agents' => $msAgents->pluck('id')->toArray()
            ]);

            // Si aucun agent n'est trouvé, retourner des données de test
            if ($msAgents->isEmpty()) {
                // Créer un agent de test avec le rôle MS si aucun n'existe
                $testUser = \App\Models\User::firstOrCreate(
                    ['email' => 'testms@example.com'],
                    [
                        'nom' => 'Test',
                        'prenom' => 'MS',
                        'password' => bcrypt('password'),
                        'role' => 'agent'
                    ]
                );

                $testAgent = \App\Models\Agent::firstOrCreate(
                    ['user_id' => $testUser->id],
                    [
                        'matricule' => 'MS'.rand(1000, 9999),
                        'fonction' => 'Maître de Stage',
                        'role_agent' => 'MS',
                        'structure_id' => $structure->id
                    ]
                );

                // Recharger l'agent avec ses relations
                $testAgent->load('user');

                Log::info('Agent de test créé', [
                    'agent_id' => $testAgent->id,
                    'user_id' => $testUser->id
                ]);

                return response()->json([$testAgent], 200);
            }

            return response()->json($msAgents, 200);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des maîtres de stage', [
                'error' => $e->getMessage(),
                'agent_id' => $agent->id
            ]);

            return response()->json([], 500);
        }
    }

    /**
     * Assigner un agent à une demande
     */
    public function assignAgent(Request $request, DemandeStage $demande)
    {
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Vérifier que l'agent est bien responsable de la structure concernée
            $structure = Structure::where('responsable_id', $agent->id)->first();

            // Vérifier si la demande est directement liée à la structure ou affectée via la table d'affectation
            $estAffectee = \App\Models\AffectationResponsableStructure::where('structure_id', $structure->id)
                ->where('id_demande_stages', $demande->id)
                ->exists();

            if (!$structure || ($demande->structure_id !== $structure->id && !$estAffectee)) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à cette demande.');
            }

            // Valider l'ID de l'agent
            $validated = $request->validate([
                'agent_id' => 'required|exists:agents,id'
            ]);

            // Récupérer l'agent à assigner
            $agentToAssign = Agent::findOrFail($validated['agent_id']);

            // Vérifier que l'agent à assigner est bien responsable d'une sous-structure
            if (!$agentToAssign->structuresResponsable()->exists()) {
                return redirect()->back()->with('error', 'Cet agent n\'est pas responsable d\'une sous-structure.');
            }

            // Mettre à jour la demande avec l'agent assigné
            $demande->update([
                'agent_id' => $agentToAssign->id
            ]);

            return redirect()->back()->with('success', 'L\'agent a été assigné à la demande avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'assignation de l\'agent', [
                'error' => $e->getMessage(),
                'agent_id' => $agent->id,
                'demande_id' => $demande->id,
                'agent_to_assign_id' => $request->agent_id ?? null
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'assignation de l\'agent.');
        }
    }

    /**
     * Affecter un maître de stage à une demande
     */
    public function affecterMaitreStage(Request $request, DemandeStage $demande)
    {
        try {
            $agent = Auth::user()->agent;
            
            Log::info('Données reçues pour l\'affectation du maître de stage', [
                'request_all' => $request->all(),
                'demande_id' => $demande->id
            ]);

            // Valider les données
            $validated = $request->validate([
                'maitre_stage_id' => 'required|exists:agents,id'
            ]);

            Log::info('Données validées', $validated);

            // Récupérer le maître de stage
            $maitreStage = Agent::findOrFail($validated['maitre_stage_id']);
            
            Log::info('Agent récupéré', [
                'agent_id' => $maitreStage->id,
                'user_id' => $maitreStage->user_id,
                'role_agent' => $maitreStage->role_agent
            ]);

            // Vérifier que le maître de stage a le rôle MS
            if ($maitreStage->role_agent !== 'MS') {
                return redirect()->back()->with('error', 'L\'agent sélectionné n\'est pas un maître de stage.');
            }

            // Récupérer la structure de l'affectation la plus récente
            $affectation = \App\Models\AffectationResponsableStructure::where('id_demande_stages', $demande->id)
                ->orderByDesc('date_affectation')
                ->orderByDesc('id')
                ->with('structure')
                ->first();

            if (!$affectation || !$affectation->structure) {
                return redirect()->back()->with('error', 'Aucune structure d\'affectation trouvée pour cette demande.');
            }

            $structure = $affectation->structure;
            
            Log::info('Structure d\'affectation utilisée', [
                'demande_id' => $demande->id,
                'structure_id' => $structure->id,
                'structure_libelle' => $structure->libelle
            ]);

            // Récupérer les IDs de la structure principale et de ses sous-structures
            $structureIds = Structure::where('parent_id', $structure->id)
                ->orWhere('id', $structure->id)
                ->pluck('id')
                ->toArray();

            if (!in_array($maitreStage->structure_id, $structureIds)) {
                return redirect()->back()->with('error', 'Le maître de stage sélectionné n\'appartient pas à votre structure ou à ses sous-structures.');
            }

            // Vérifier si le stage existe déjà
            $stage = \App\Models\Stage::where('demande_stage_id', $demande->id)->first();
            
            if (!$stage) {
                // Créer le stage
                $stage = \App\Models\Stage::create([
                    'demande_stage_id' => $demande->id,
                    'structure_id' => $structure->id,
                    'stagiaire_id' => $demande->stagiaire_id,
                    'date_debut' => $demande->date_debut,
                    'date_fin' => $demande->date_fin,
                    'type' => strtolower($demande->type),
                    'statut' => 'En cours'
                ]);
            }

            // Créer l'affectation
            $affectation = AffectationMaitreStage::create([
                'stage_id' => $stage->id,
                'maitre_stage_id' => $maitreStage->user_id,
                'agent_affectant_id' => $agent->id,
                'date_affectation' => now(),
                'statut' => 'En cours'
            ]);

            // Mettre à jour le statut de la demande
            $demande->update([
                'statut' => 'En cours',
                'traite_par' => $agent->id,
                'date_traitement' => now()
            ]);

            // Envoyer un email de notification au maître de stage
            Mail::to($maitreStage->email)->send(new AffectationMaitreStageMail($demande->stagiaire->user, $stage, $maitreStage));

            Log::info('Email d\'affectation du maître de stage envoyé avec succès.', [
                'demande_id' => $demande->id,
                'maitre_stage_id' => $maitreStage->id
            ]);

            return redirect()->back()->with('success', 'Maître de stage affecté avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affectation du maître de stage', [
                'error' => $e->getMessage(),
                'demande_id' => $demande->id,
                'maitre_stage_id' => $request->maitre_stage_id,
                'stack' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'affectation du maître de stage.');
        }
    }
}