<?php

namespace App\Http\Controllers\Agent\RS;

use App\Http\Controllers\Controller;
use App\Models\DemandeStage;
use App\Models\Structure;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->agent || $user->agent->role_agent !== 'RS') {
            abort(403, 'Accès réservé aux Responsables de Structure.');
        }
    }

    public function index()
    {
        $user = Auth::user();
        $agent = $user->agent;
        $stats = [
            'demandesEnAttente' => 0,
            'demandesTraitees' => 0,
            'demandesAcceptees' => 0,
            'demandesRejetees' => 0,
        ];

        try {
            // Récupérer la structure dont l'agent est responsable
            $structure = Structure::where('responsable_id', $agent->id)->first();

            if ($structure) {
                // Récupérer les IDs des demandes affectées à cette structure via la table d'affectation
                $demandesAffecteesIds = \App\Models\AffectationResponsableStructure::where('structure_id', $structure->id)
                    ->pluck('id_demande_stages')
                    ->toArray();

                // Statistiques des demandes pour cette structure (directes + affectées)
                $demandesQuery = DemandeStage::where(function($query) use ($structure, $demandesAffecteesIds) {
                    $query->where('structure_id', $structure->id)
                          ->orWhereIn('id', $demandesAffecteesIds);
                });

                $stats['demandesEnAttente'] = (clone $demandesQuery)
                    ->where('statut', 'En cours')
                    ->count();

                $stats['demandesAcceptees'] = (clone $demandesQuery)
                    ->where('statut', 'Acceptée')
                    ->count();

                $stats['demandesRejetees'] = (clone $demandesQuery)
                    ->where('statut', 'Refusée')
                    ->count();

                $stats['demandesTraitees'] = $stats['demandesAcceptees'] + $stats['demandesRejetees'];

                // Récupérer les 5 dernières demandes (directes + affectées) avec la date d'affectation
                $dernieresDemandes = DemandeStage::with(['stagiaire.user', 'affectations'])
                    ->where(function($query) use ($structure, $demandesAffecteesIds) {
                        $query->where('structure_id', $structure->id)
                              ->orWhereIn('id', $demandesAffecteesIds);
                    })
                    ->latest()
                    ->take(5)
                    ->get()
                    ->map(function($demande) {
                        // Récupérer la dernière affectation (si elle existe)
                        $derniereAffectation = $demande->affectations->sortByDesc('created_at')->first();
                        $demande->date_affectation = $derniereAffectation ? $derniereAffectation->created_at : null;
                        return $demande;
                    });

                return Inertia::render('Agent/RS/Dashboard', [
                    'stats' => $stats,
                    'structure' => $structure,
                    'dernieresDemandes' => $dernieresDemandes,
                ]);
            }

            // Si l'agent n'est pas responsable d'une structure
            Log::warning('Agent RS sans structure assignée', [
                'agent_id' => $agent->id,
                'user_id' => $user->id
            ]);

            return Inertia::render('Agent/RS/Dashboard', [
                'stats' => $stats,
                'structure' => null,
                'dernieresDemandes' => [],
                'error' => 'Vous n\'êtes actuellement responsable d\'aucune structure.'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement du dashboard RS', [
                'error' => $e->getMessage(),
                'agent_id' => $agent->id
            ]);

            return Inertia::render('Agent/RS/Dashboard', [
                'stats' => $stats,
                'structure' => null,
                'dernieresDemandes' => [],
                'error' => 'Une erreur est survenue lors du chargement des données.'
            ]);
        }
    }
}