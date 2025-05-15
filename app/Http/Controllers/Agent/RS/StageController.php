<?php

namespace App\Http\Controllers\Agent\RS;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\Structure;
use App\Models\AffectationMaitreStage;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class StageController extends Controller
{
    /**
     * Vérification du rôle avant chaque action
     */
    private function checkRSRole()
    {
        $user = Auth::user();
        if (!$user || !$user->agent || $user->agent->role_agent !== 'RS') {
            abort(403, 'Accès réservé aux Responsables de Structure.');
        }
    }

    /**
     * Afficher la liste des stages pour le RS
     */
    public function index(Request $request)
    {
        $this->checkRSRole();
        $user = Auth::user();
        $agent = $user->agent;
        
        try {
            // Récupérer la structure dont l'agent est responsable
            $structure = Structure::where('responsable_id', $agent->id)->first();
            
            if (!$structure) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas responsable d\'une structure.');
            }
            
            // Récupérer toutes les sous-structures (récursivement)
            $subStructureIds = $this->getAllSubStructureIds($structure->id);
            $allStructureIds = array_merge([$structure->id], $subStructureIds);
            
            // Construire la requête de base
            $query = Stage::with([
                'structure', 
                'demandeStage.stagiaire.user',
                'affectationsMaitreStage' => function($query) {
                    $query->orderBy('date_affectation', 'desc');
                },
                'affectationsMaitreStage.maitreStage',
                'affectationsMaitreStage.agentAffectant.user'
            ])
            ->whereIn('structure_id', $allStructureIds);
            
            // Filtres
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('statut', $request->status);
            }
            
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->whereHas('demandeStage.stagiaire.user', function($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            
            // Pagination
            $stages = $query->latest()->paginate(10)->withQueryString();
            
            // Préparer les données pour la vue
            $stages->getCollection()->transform(function ($stage) {
                // Ajouter l'historique des affectations
                $stage->historique_affectations = $stage->affectationsMaitreStage->map(function($affectation) {
                    return [
                        'id' => $affectation->id,
                        'date_affectation' => $affectation->date_affectation,
                        'statut' => $affectation->statut,
                        'maitre_stage' => $affectation->maitreStage ? [
                            'id' => $affectation->maitreStage->id,
                            'nom' => $affectation->maitreStage->nom,
                            'prenom' => $affectation->maitreStage->prenom,
                            'email' => $affectation->maitreStage->email,
                        ] : null,
                        'agent_affectant' => $affectation->agentAffectant ? [
                            'id' => $affectation->agentAffectant->id,
                            'nom' => $affectation->agentAffectant->user->nom,
                            'prenom' => $affectation->agentAffectant->user->prenom,
                        ] : null,
                    ];
                });
                
                return $stage;
            });
            
            return Inertia::render('Agent/RS/Stages/Index', [
                'stages' => $stages,
                'structure' => $structure,
                'filters' => $request->only(['status', 'search', 'page'])
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des stages RS', [
                'error' => $e->getMessage(),
                'agent_id' => $agent->id
            ]);
            
            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement des stages.');
        }
    }

    /**
     * Afficher les détails d'un stage
     */
    public function show(Stage $stage)
    {
        $this->checkRSRole();
        $user = Auth::user();
        $agent = $user->agent;
        
        try {
            // Vérifier que le stage appartient à la structure du RS ou à une sous-structure
            $structure = Structure::where('responsable_id', $agent->id)->first();
            
            if (!$structure) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas responsable d\'une structure.');
            }
            
            $subStructureIds = $this->getAllSubStructureIds($structure->id);
            $allStructureIds = array_merge([$structure->id], $subStructureIds);
            
            if (!in_array($stage->structure_id, $allStructureIds)) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à voir ce stage.');
            }
            
            // Charger les relations nécessaires
            $stage->load([
                'structure', 
                'demandeStage.stagiaire.user',
                'affectationsMaitreStage' => function($query) {
                    $query->orderBy('date_affectation', 'desc');
                },
                'affectationsMaitreStage.maitreStage',
                'affectationsMaitreStage.agentAffectant.user',
                'themeStage'
            ]);
            
            return Inertia::render('Agent/RS/Stages/Show', [
                'stage' => $stage,
                'structure' => $structure
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des détails du stage', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'agent_id' => $agent->id
            ]);
            
            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement des détails du stage.');
        }
    }

    /**
     * Récupérer récursivement tous les IDs des sous-structures
     */
    private function getAllSubStructureIds($structureId, $ids = [])
    {
        $subStructures = Structure::where('parent_id', $structureId)->get();
        
        foreach ($subStructures as $subStructure) {
            $ids[] = $subStructure->id;
            $ids = $this->getAllSubStructureIds($subStructure->id, $ids);
        }
        
        return $ids;
    }
}
