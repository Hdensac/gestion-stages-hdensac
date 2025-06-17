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
use Barryvdh\DomPDF\Facade\Pdf;

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
            
            // Filtrer pour n'afficher que le stage du demandeur principal pour les demandes de groupe
            $stages = $query->get();
            $filteredStages = $stages->filter(function ($stage) {
                $demande = $stage->demandeStage;
                if (!$demande) return false;
                // Si ce n'est pas une demande de groupe, on garde le stage
                if ($demande->nature !== 'Groupe') return true;
                // Pour une demande de groupe, on ne garde que le stage du demandeur principal
                return $stage->stagiaire_id == $demande->stagiaire_id;
            });
            // Paginer manuellement
            $perPage = 10;
            $page = request()->get('page', 1);
            $pagedStages = $filteredStages->forPage($page, $perPage);
            $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
                $pagedStages,
                $filteredStages->count(),
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            
            // Préparer les données pour la vue
            $paginated->getCollection()->transform(function ($stage) {
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
                'stages' => $paginated,
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
                'themeStage',
                'evaluation',
                'demandeStage.membres.user',
            ]);
            
            // Préparer les membres du groupe avec leur stage et évaluation
            $membresGroupe = [];
            if ($stage->demandeStage && $stage->demandeStage->nature === 'Groupe') {
                $membresGroupe = $stage->demandeStage->membres->map(function($membre) use ($stage) {
                    $stagiaire = \App\Models\Stagiaire::where('user_id', $membre->user_id)->first();
                    $stageMembre = $stagiaire ? \App\Models\Stage::where('demande_stage_id', $stage->demande_stage_id)->where('stagiaire_id', $stagiaire->id_stagiaire)->first() : null;
                    return [
                        'user' => $membre->user,
                        'stagiaire' => $stagiaire,
                        'stage' => $stageMembre,
                        'evaluation' => $stageMembre ? $stageMembre->evaluation : null,
                    ];
                });
            }
            
            return Inertia::render('Agent/RS/Stages/Show', [
                'stage' => $stage,
                'structure' => $structure,
                'membres_groupe' => $membresGroupe,
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

    /**
     * Affecter un maître de stage à un stage
     */
    public function affecterMaitreStage(Request $request, Stage $stage)
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

            // Vérifier que le stage appartient à la structure du RS ou à une sous-structure
            $subStructureIds = $this->getAllSubStructureIds($structure->id);
            $allStructureIds = array_merge([$structure->id], $subStructureIds);
            if (!in_array($stage->structure_id, $allStructureIds)) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à affecter un maître de stage à ce stage.');
            }

            // Valider les données
            $validated = $request->validate([
                'maitre_stage_id' => 'required|exists:agents,id'
            ]);

            // Récupérer le maître de stage
            $maitreStage = Agent::findOrFail($validated['maitre_stage_id']);

            // Vérifier que le maître de stage a le rôle MS
            if ($maitreStage->role_agent !== 'MS') {
                return redirect()->back()->with('error', 'L\'agent sélectionné n\'est pas un maître de stage.');
            }

            // Vérifier que le maître de stage appartient à la structure ou à une sous-structure
            if (!in_array($maitreStage->structure_id, $allStructureIds)) {
                return redirect()->back()->with('error', 'Le maître de stage sélectionné n\'appartient pas à votre structure ou à ses sous-structures.');
            }

            // Créer l'affectation
            $affectation = AffectationMaitreStage::create([
                'stage_id' => $stage->id,
                'maitre_stage_id' => $maitreStage->user_id,
                'agent_affectant_id' => $agent->id,
                'date_affectation' => now(),
                'statut' => 'En cours'
            ]);

            // Mettre à jour le statut du stage
            $stage->update([
                'statut' => 'En cours',
                'updated_at' => now()
            ]);

            Log::info('Maître de stage affecté au stage', [
                'stage_id' => $stage->id,
                'maitre_stage_id' => $maitreStage->id,
                'affectation_id' => $affectation->id
            ]);

            return redirect()->back()->with('success', 'Maître de stage affecté avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affectation du maître de stage', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'maitre_stage_id' => $request->maitre_stage_id,
                'stack' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'affectation du maître de stage.');
        }
    }

    /**
     * Générer l'attestation de stage (PDF ou HTML)
     */
    public function attestation(Stage $stage)
    {
        $stage->load(['stagiaire.user', 'structure']);
        $demande = $stage->demandeStage;
        $stagiairePrincipal = $demande ? $demande->stagiaire : $stage->stagiaire;
        $structure = $stage->structure;
        $responsable = $structure && $structure->responsable ? $structure->responsable : null;
        $nom_rs = $responsable ? ($responsable->user->nom . ' ' . $responsable->user->prenom) : '';
        $libelle_structure = $structure ? $structure->libelle : '';
        $sigle_structure = $structure ? $structure->sigle : '';
        $pdf = Pdf::loadView('attestation', [
            'stage' => $stage,
            'stagiaire' => $stage->stagiaire,
            'structure' => $structure,
            'stagiaire_principal' => $stagiairePrincipal,
            'nom_rs' => $nom_rs,
            'libelle_structure' => $libelle_structure,
            'sigle_structure' => $sigle_structure,
        ]);
        return $pdf->stream('attestation_stage_'.$stage->id.'.pdf');
    }
}
