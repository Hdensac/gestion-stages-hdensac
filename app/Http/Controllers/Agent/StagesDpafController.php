<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\Structure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StagesDpafController extends Controller
{
    /**
     * Afficher la liste de tous les stages en cours pour la DPAF
     */
    public function index(Request $request)
    {
        // Récupérer le filtre de statut depuis la requête
        $statut = $request->input('statut', 'all');
        $structureId = $request->input('structure_id', 'all');
        $perPage = 10;
        $query = Stage::with([
            'structure',
            'stagiaire.user',
            'affectationsMaitreStage' => function($q) {
                $q->orderByDesc('date_affectation');
            },
            'affectationsMaitreStage.maitreStage',
            'demandeStage',
        ])
        ->join('demande_stages', 'stages.demande_stage_id', '=', 'demande_stages.id')
        ->whereColumn('stages.stagiaire_id', 'demande_stages.stagiaire_id')
        ->orderByDesc('stages.updated_at')
        ->select('stages.*');

        if ($statut !== 'all') {
            $query->where('stages.statut', $statut);
        }
        if ($structureId !== 'all') {
            $query->where('stages.structure_id', $structureId);
        }

        $paginated = $query->paginate($perPage)->withQueryString();

        // On mappe les items pour la vue
        $paginated->getCollection()->transform(function ($stage) {
            $maitre = $stage->affectationsMaitreStage->first()?->maitreStage;
            return [
                'id' => $stage->id,
                'date_debut' => $stage->date_debut,
                'date_fin' => $stage->date_fin,
                'statut' => $stage->statut,
                'type' => $stage->type,
                'structure' => $stage->structure,
                'stagiaire' => $stage->stagiaire,
                'maitre_stage' => $maitre ? [
                    'nom' => $maitre->nom,
                    'prenom' => $maitre->prenom,
                    'email' => $maitre->email,
                ] : null,
            ];
        });

        // Récupérer toutes les structures pour le filtre
        $structures = \App\Models\Structure::whereNull('parent_id')->orderBy('libelle')->get(['id', 'libelle']);

        return Inertia::render('Agent/StagesDpafIndex', [
            'stages' => $paginated,
            'structures' => $structures,
            'filters' => [
                'statut' => $statut,
                'structure_id' => $structureId,
            ]
        ]);
    }

    public function show($id)
    {
        $stage = \App\Models\Stage::with([
            'structure',
            'themeStage',
            'stagiaire.user',
            'demandeStage.membres.user.stagiaire',
            'affectationsMaitreStage' => function($q) {
                $q->orderByDesc('date_affectation');
            },
            'affectationsMaitreStage.maitreStage',
            'evaluation',
        ])->findOrFail($id);

        // Récupérer les informations du maître de stage actuel (statut "En cours" ou "Acceptée")
        $affectationActuelle = $stage->affectationsMaitreStage
            ->whereIn('statut', ['En cours', 'Acceptée'])
            ->first();
        $maitreStageActuel = $affectationActuelle?->maitreStage;
        // Chercher la structure dont ce MS est responsable (via l'id de l'agent)
        $structureMS = null;
        if ($maitreStageActuel) {
            $agent = \App\Models\Agent::where('user_id', $maitreStageActuel->id)->first();
            if ($agent) {
                $structureMS = \App\Models\Structure::where('responsable_id', $agent->id)->first();
            }
        }

        // Récupérer les membres du groupe avec leurs stages et évaluations
        $membresGroupe = [];
        if ($stage->demandeStage && $stage->demandeStage->nature === 'Groupe') {
            $membresGroupe = $stage->demandeStage->membres->map(function($membre) use ($stage) {
                $stagiaire = \App\Models\Stagiaire::where('user_id', $membre->user_id)->first();
                $stageMembre = null;
                $evaluationMembre = null;

                if ($stagiaire) {
                    $stageMembre = \App\Models\Stage::where('demande_stage_id', $stage->demande_stage_id)
                        ->where('stagiaire_id', $stagiaire->id_stagiaire)
                        ->with('evaluation')
                        ->first();

                    if ($stageMembre) {
                        $evaluationMembre = $stageMembre->evaluation;
                    }
                }

                return [
                    'user' => $membre->user,
                    'stage' => $stageMembre,
                    'evaluation' => $evaluationMembre,
                ];
            });
        }

        return inertia('Agent/StagesDpafShow', [
            'stage' => $stage,
            'maitre_stage' => $maitreStageActuel ? array_merge($maitreStageActuel->toArray(), [
                'structure' => $structureMS ? [
                    'id' => $structureMS->id,
                    'libelle' => $structureMS->libelle,
                    'sigle' => $structureMS->sigle,
                ] : null
            ]) : null,
            'membres_groupe' => $membresGroupe,
        ]);
    }
} 