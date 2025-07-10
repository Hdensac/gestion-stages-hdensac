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
        ])->findOrFail($id);

        return inertia('Agent/StagesDpafShow', [
            'stage' => $stage,
        ]);
    }
} 