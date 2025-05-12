<?php

namespace App\Http\Controllers\Agent\MS;

use App\Http\Controllers\Controller;
use App\Models\AffectationMaitreStage;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;


class DashboardController extends Controller
{
    public function __construct()
    {
        $user = Auth::user();
        if (!$user || !$user->agent || $user->agent->role_agent !== 'MS') {
            abort(403, 'Accès réservé aux Maîtres de Stage.');
        }
    }

    public function index()
    {
        $user = Auth::user();
        $agent = $user->agent;
        $stats = [
            'stagesEnCours' => 0,
            'stagesTermines' => 0,
            'totalStagiaires' => 0,
        ];

        try {
            // Récupérer les affectations du maître de stage avec toutes les relations nécessaires
            $affectations = AffectationMaitreStage::where('maitre_stage_id', $user->id)
                ->with([
                    'stage.demandeStage.stagiaire.user',
                    'stage.structure',
                    'maitreStage'
                ])
                ->get();

            // Vérifier si les affectations ont bien été récupérées
            if ($affectations->isEmpty()) {
                Log::warning('Aucune affectation trouvée pour ce maître de stage', [
                    'user_id' => $user->id,
                    'agent_id' => $agent->id
                ]);
            }

            // Journaliser les données pour le débogage
            Log::info('Affectations récupérées', [
                'count' => $affectations->count(),
                'affectations' => $affectations->map(function ($affectation) {
                    return [
                        'id' => $affectation->id,
                        'stage_id' => $affectation->stage_id,
                        'maitre_stage_id' => $affectation->maitre_stage_id,
                        'stage' => $affectation->stage ? [
                            'id' => $affectation->stage->id,
                            'demande_stage_id' => $affectation->stage->demande_stage_id,
                            'demande_stage' => $affectation->stage->demandeStage ? [
                                'id' => $affectation->stage->demandeStage->id,
                                'stagiaire_id' => $affectation->stage->demandeStage->stagiaire_id,
                                'stagiaire' => $affectation->stage->demandeStage->stagiaire ? [
                                    'id' => $affectation->stage->demandeStage->stagiaire->id_stagiaire,
                                    'user_id' => $affectation->stage->demandeStage->stagiaire->user_id,
                                    'user' => $affectation->stage->demandeStage->stagiaire->user ? [
                                        'id' => $affectation->stage->demandeStage->stagiaire->user->id,
                                        'nom' => $affectation->stage->demandeStage->stagiaire->user->nom,
                                        'prenom' => $affectation->stage->demandeStage->stagiaire->user->prenom,
                                    ] : null
                                ] : null
                            ] : null
                        ] : null
                    ];
                })->toArray()
            ]);

            // Calculer les statistiques
            $stats['stagesEnCours'] = $affectations->filter(function ($affectation) {
                return $affectation->stage && $affectation->stage->statut === 'En cours';
            })->count();

            $stats['stagesTermines'] = $affectations->filter(function ($affectation) {
                return $affectation->stage && $affectation->stage->statut === 'Terminé';
            })->count();

            // Compter le nombre total de stagiaires (en tenant compte des stages de groupe)
            $totalStagiaires = 0;
            foreach ($affectations as $affectation) {
                if ($affectation->stage && $affectation->stage->demandeStage) {
                    $demande = $affectation->stage->demandeStage;
                    if ($demande->nature === 'Groupe') {
                        $totalStagiaires += $demande->membres->count() + 1; // +1 pour le stagiaire principal
                    } else {
                        $totalStagiaires += 1;
                    }
                }
            }
            $stats['totalStagiaires'] = $totalStagiaires;

            // Récupérer les stages affectés avec toutes les relations nécessaires
            $derniersStages = $affectations->sortByDesc(function ($affectation) {
                return $affectation->date_affectation;
            })->take(5)->map(function ($affectation) {
                $stage = $affectation->stage;

                // S'assurer que toutes les relations sont chargées
                if ($stage) {
                    // Charger manuellement les relations si elles ne sont pas déjà chargées
                    if (!$stage->relationLoaded('demandeStage') ||
                        ($stage->demandeStage && !$stage->demandeStage->relationLoaded('stagiaire')) ||
                        ($stage->demandeStage && $stage->demandeStage->stagiaire && !$stage->demandeStage->stagiaire->relationLoaded('user'))) {

                        $stage->load(['demandeStage.stagiaire.user', 'structure']);
                    }
                }

                return $stage;
            })->filter(); // Filtrer les valeurs null

            // Journaliser les stages pour le débogage
            Log::info('Derniers stages récupérés', [
                'count' => $derniersStages->count(),
                'stages' => $derniersStages->map(function ($stage) {
                    return [
                        'id' => $stage->id,
                        'demande_stage_id' => $stage->demande_stage_id,
                        'structure' => $stage->structure ? [
                            'id' => $stage->structure->id,
                            'libelle' => $stage->structure->libelle,
                        ] : null,
                        'demande_stage' => $stage->demandeStage ? [
                            'id' => $stage->demandeStage->id,
                            'stagiaire' => $stage->demandeStage->stagiaire ? [
                                'id' => $stage->demandeStage->stagiaire->id_stagiaire,
                                'user' => $stage->demandeStage->stagiaire->user ? [
                                    'id' => $stage->demandeStage->stagiaire->user->id,
                                    'nom' => $stage->demandeStage->stagiaire->user->nom,
                                    'prenom' => $stage->demandeStage->stagiaire->user->prenom,
                                ] : null
                            ] : null
                        ] : null
                    ];
                })->toArray()
            ]);

            // Vérifier si les données du stagiaire sont correctement chargées
            foreach ($derniersStages as $stage) {
                if ($stage->demandeStage && $stage->demandeStage->stagiaire && $stage->demandeStage->stagiaire->user) {
                    Log::info('Données du stagiaire trouvées', [
                        'stage_id' => $stage->id,
                        'demande_id' => $stage->demande_stage_id,
                        'stagiaire_id' => $stage->demandeStage->stagiaire->id_stagiaire,
                        'user_id' => $stage->demandeStage->stagiaire->user->id,
                        'nom' => $stage->demandeStage->stagiaire->user->nom,
                        'prenom' => $stage->demandeStage->stagiaire->user->prenom
                    ]);
                } else {
                    Log::warning('Données du stagiaire manquantes', [
                        'stage_id' => $stage->id,
                        'demande_id' => $stage->demande_stage_id,
                        'has_demande' => $stage->demandeStage ? 'oui' : 'non',
                        'has_stagiaire' => $stage->demandeStage && $stage->demandeStage->stagiaire ? 'oui' : 'non',
                        'has_user' => $stage->demandeStage && $stage->demandeStage->stagiaire && $stage->demandeStage->stagiaire->user ? 'oui' : 'non'
                    ]);
                }
            }

            // Sérialiser explicitement les données des stages avant de les envoyer à la vue
            $stagesSerialises = $derniersStages->map(function ($stage) {
                return [
                    'id' => $stage->id,
                    'demande_stage_id' => $stage->demande_stage_id,
                    'structure' => $stage->structure ? [
                        'id' => $stage->structure->id,
                        'libelle' => $stage->structure->libelle,
                        'sigle' => $stage->structure->sigle,
                    ] : null,
                    'date_debut' => $stage->date_debut,
                    'date_fin' => $stage->date_fin,
                    'statut' => $stage->statut,
                    'type' => $stage->type,
                    'note' => $stage->note,
                    'demandeStage' => $stage->demandeStage ? [
                        'id' => $stage->demandeStage->id,
                        'stagiaire' => $stage->demandeStage->stagiaire ? [
                            'id_stagiaire' => $stage->demandeStage->stagiaire->id_stagiaire,
                            'niveau_etude' => $stage->demandeStage->stagiaire->niveau_etude,
                            'universite' => $stage->demandeStage->stagiaire->universite,
                            'filiere' => $stage->demandeStage->stagiaire->filiere,
                            'user' => $stage->demandeStage->stagiaire->user ? [
                                'id' => $stage->demandeStage->stagiaire->user->id,
                                'nom' => $stage->demandeStage->stagiaire->user->nom,
                                'prenom' => $stage->demandeStage->stagiaire->user->prenom,
                                'email' => $stage->demandeStage->stagiaire->user->email,
                                'telephone' => $stage->demandeStage->stagiaire->user->telephone,
                            ] : null
                        ] : null
                    ] : null
                ];
            });

            // Journaliser les données sérialisées pour le débogage
            Log::info('Données sérialisées envoyées à la vue', [
                'count' => $stagesSerialises->count(),
                'stages' => $stagesSerialises->toArray()
            ]);

            // Récupérer la structure dont l'agent est responsable
            $structureResponsable = \App\Models\Structure::where('responsable_id', $agent->id)->first();

            return Inertia::render('Agent/MS/Dashboard', [
                'stats' => $stats,
                'derniersStages' => $stagesSerialises,
                'agent' => $agent->load('user'),
                'structureResponsable' => $structureResponsable,
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement du dashboard MS', [
                'error' => $e->getMessage(),
                'agent_id' => $agent->id
            ]);

            // Récupérer la structure dont l'agent est responsable même en cas d'erreur
            $structureResponsable = \App\Models\Structure::where('responsable_id', $agent->id)->first();

            return Inertia::render('Agent/MS/Dashboard', [
                'stats' => $stats,
                'derniersStages' => [],
                'agent' => $agent->load('user'),
                'structureResponsable' => $structureResponsable,
                'error' => 'Une erreur est survenue lors du chargement des données. ' . $e->getMessage()
            ]);
        }
    }
}
