<?php

namespace App\Http\Controllers\Agent\RS;

use App\Http\Controllers\Controller;
use App\Models\Structure;
use App\Models\Agent;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class OrganigrammeController extends Controller
{
    public function __construct()
    {
        $user = Auth::user();
        if (!$user || !$user->agent || $user->agent->role_agent !== 'RS') {
            abort(403, 'Accès réservé aux Responsables de Structure.');
        }
    }

    public function index()
    {
        try {
            $user = Auth::user();
            $agent = $user->agent;

            // Vérifier si l'agent est responsable d'une structure
            $structureResponsable = Structure::where('responsable_id', $agent->id)->first();

            if (!$structureResponsable) {
                // Si l'agent n'est pas responsable d'une structure, afficher un message d'erreur
                return Inertia::render('Agent/RS/OrganigrammeHierarchique', [
                    'structures' => [],
                    'agent' => $agent->load('user'),
                    'error' => 'Vous n\'êtes pas responsable d\'une structure. Veuillez contacter l\'administrateur.',
                    'agents' => [],
                ]);
            }

            // Récupérer la structure principale dont l'agent est responsable
            $structurePrincipale = $structureResponsable;

            // Si c'est une sous-structure, remonter à la structure principale
            if ($structureResponsable->parent_id) {
                $structurePrincipale = Structure::find($structureResponsable->parent_id);

                // Si la structure parente n'existe pas, utiliser la structure responsable
                if (!$structurePrincipale) {
                    $structurePrincipale = $structureResponsable;
                }
            }

            // Récupérer la structure principale et toutes ses sous-structures (tous niveaux)
            // D'abord, récupérer la structure principale
            $structurePrincipaleObj = Structure::with(['responsable.user'])
                ->where('id', $structurePrincipale->id)
                ->first();

            // Ensuite, récupérer toutes les sous-structures (tous niveaux)
            $sousStructures = Structure::with(['responsable.user'])
                ->where('id', '!=', $structurePrincipale->id)
                ->where(function ($query) use ($structurePrincipale) {
                    $query->where('parent_id', $structurePrincipale->id)
                          ->orWhereIn('parent_id', function ($subQuery) use ($structurePrincipale) {
                              $subQuery->select('id')
                                      ->from('structures')
                                      ->where('parent_id', $structurePrincipale->id);
                          })
                          ->orWhereIn('parent_id', function ($subQuery) use ($structurePrincipale) {
                              $subQuery->select('id')
                                      ->from('structures')
                                      ->whereIn('parent_id', function ($innerSubQuery) use ($structurePrincipale) {
                                          $innerSubQuery->select('id')
                                                  ->from('structures')
                                                  ->where('parent_id', $structurePrincipale->id);
                                      });
                          });
                })
                ->orderBy('parent_id', 'asc')
                ->orderBy('libelle', 'asc')
                ->get();

            // Combiner les résultats
            $structures = collect([$structurePrincipaleObj])->merge($sousStructures);

            // Ajouter des informations supplémentaires pour chaque structure
            $structures->each(function ($structure) {
                // Compter le nombre d'agents dans cette structure
                $structure->nombre_agents = Agent::where('structure_id', $structure->id)->count();

                // Compter le nombre de sous-structures
                $structure->nombre_sous_structures = Structure::where('parent_id', $structure->id)->count();
            });

            Log::info('Structures récupérées pour l\'organigramme', [
                'agent_id' => $agent->id,
                'structure_responsable_id' => $structureResponsable->id,
                'structure_principale_id' => $structurePrincipale->id,
                'nombre_structures' => $structures->count(),
                'structures' => $structures->pluck('id', 'libelle')
            ]);

            // Récupérer les agents qui peuvent être affectés
            $agents = Agent::with('user')
                ->whereNull('structure_id')
                ->orWhere('structure_id', $structureResponsable->id)
                ->get();

            return Inertia::render('Agent/RS/OrganigrammeHierarchique', [
                'structures' => $structures,
                'agent' => $agent->load('user'),
                'structureResponsable' => $structureResponsable,
                'agents' => $agents,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement de l\'organigramme', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return Inertia::render('Agent/RS/OrganigrammeHierarchique', [
                'structures' => [],
                'agent' => $agent->load('user'),
                'error' => 'Une erreur est survenue lors du chargement des données: ' . $e->getMessage(),
                'agents' => [],
            ]);
        }
    }

    public function create()
    {
        $user = Auth::user();
        $agent = $user->agent;

        // Vérifier si l'agent est responsable d'une structure
        $structureResponsable = Structure::where('responsable_id', $agent->id)->first();

        if (!$structureResponsable) {
            return redirect()->route('agent.rs.organigramme.index')
                ->with('error', 'Vous n\'êtes pas responsable d\'une structure. Vous ne pouvez pas créer de sous-structure.');
        }

        $parentId = request('parent_id');
        $parentStructure = null;

        // Si un parent_id est fourni, vérifier qu'il s'agit bien de la structure dont l'agent est responsable
        // ou d'une structure parente de celle-ci
        if ($parentId) {
            $parentStructure = Structure::findOrFail($parentId);

            // Vérifier si l'agent est responsable de cette structure ou d'une structure parente
            $isAuthorized = $parentStructure->id === $structureResponsable->id;

            if (!$isAuthorized && $structureResponsable->parent_id) {
                // Si l'agent est responsable d'une sous-structure, vérifier si le parent_id est la structure parente
                $isAuthorized = $parentStructure->id === $structureResponsable->parent_id;
            }

            if (!$isAuthorized) {
                return redirect()->route('agent.rs.organigramme.index')
                    ->with('error', 'Vous n\'êtes pas autorisé à créer des sous-structures pour cette structure.');
            }
        } else {
            // Si aucun parent_id n'est fourni, utiliser la structure dont l'agent est responsable
            $parentStructure = $structureResponsable;
        }

        return Inertia::render('Agent/RS/StructureForm', [
            'parentStructure' => $parentStructure,
            'agents' => Agent::with('user')
                ->where('role_agent', 'RS')
                ->where(function ($query) use ($parentStructure) {
                    $query->whereDoesntHave('structureResponsable')
                        ->orWhere('id', $parentStructure->responsable_id);
                })
                ->get()
        ]);
    }

    public function store()
    {
        $user = Auth::user();
        $agent = $user->agent;

        // Vérifier si l'agent est responsable d'une structure
        $structureResponsable = Structure::where('responsable_id', $agent->id)->first();

        if (!$structureResponsable) {
            return redirect()->route('agent.rs.organigramme.index')
                ->with('error', 'Vous n\'êtes pas responsable d\'une structure. Vous ne pouvez pas créer de sous-structure.');
        }

        $data = request()->validate([
            'libelle' => 'required|string|max:255',
            'sigle' => 'required|string|max:50',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:structures,id',
            'responsable_id' => 'nullable|exists:agents,id'
        ]);

        // Autoriser la création de sous-structures à n'importe quel niveau
        // Vérifier que le parent_id existe dans la base de données
        if (isset($data['parent_id'])) {
            $parentStructure = Structure::find($data['parent_id']);

            if (!$parentStructure) {
                return redirect()->route('agent.rs.organigramme.index')
                    ->with('error', 'La structure parente spécifiée n\'existe pas.');
            }

            // Vérifier si la structure parente fait partie de l'arborescence de la structure dont l'agent est responsable
            $isAuthorized = false;

            // Si l'agent est responsable de la structure principale
            if ($structureResponsable->parent_id === null) {
                // Vérifier si le parent_id est la structure principale ou une de ses sous-structures
                $isAuthorized = $parentStructure->id === $structureResponsable->id;

                if (!$isAuthorized) {
                    // Vérifier si c'est une sous-structure de la structure principale
                    $isAuthorized = Structure::where('id', $parentStructure->id)
                        ->where(function ($query) use ($structureResponsable) {
                            $query->where('parent_id', $structureResponsable->id)
                                ->orWhereIn('parent_id', function ($subQuery) use ($structureResponsable) {
                                    $subQuery->select('id')
                                        ->from('structures')
                                        ->where('parent_id', $structureResponsable->id);
                                });
                        })
                        ->exists();
                }
            } else {
                // Si l'agent est responsable d'une sous-structure
                $isAuthorized = $parentStructure->id === $structureResponsable->id;
            }

            if (!$isAuthorized) {
                return redirect()->route('agent.rs.organigramme.index')
                    ->with('error', 'Vous n\'êtes pas autorisé à créer des sous-structures pour cette structure.');
            }
        }

        $newStructure = Structure::create($data);

        if (isset($data['responsable_id']) && $data['responsable_id']) {
            $responsableAgent = Agent::find($data['responsable_id']);
            if ($responsableAgent) {
                // Mettre à jour le rôle de l'agent si nécessaire
                if ($responsableAgent->role_agent !== 'RS') {
                    $responsableAgent->update(['role_agent' => 'RS']);
                }
            }
        }

        Log::info('Structure créée', [
            'structure_id' => $newStructure->id,
            'libelle' => $newStructure->libelle,
            'parent_id' => $newStructure->parent_id,
            'agent_id' => $agent->id
        ]);

        return redirect()->route('agent.rs.organigramme.index')
            ->with('success', 'Structure créée avec succès');
    }

    public function edit(Structure $structure)
    {
        $user = Auth::user();
        $agent = $user->agent;

        // Vérifier si l'agent est responsable d'une structure
        $structureResponsable = Structure::where('responsable_id', $agent->id)->first();

        if (!$structureResponsable) {
            return redirect()->route('agent.rs.organigramme.index')
                ->with('error', 'Vous n\'êtes pas responsable d\'une structure. Vous ne pouvez pas modifier de structure.');
        }

        // Vérifier que la structure est bien celle dont l'agent est responsable
        // ou une sous-structure de celle-ci
        $isAuthorized = $structure->id === $structureResponsable->id;

        if (!$isAuthorized) {
            // Vérifier si c'est une sous-structure de la structure dont l'agent est responsable
            if ($structureResponsable->parent_id === null) {
                $isAuthorized = $structure->parent_id === $structureResponsable->id;
            } else {
                // Si l'agent est responsable d'une sous-structure, il ne peut modifier que cette sous-structure
                $isAuthorized = false;
            }
        }

        if (!$isAuthorized) {
            return redirect()->route('agent.rs.organigramme.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette structure.');
        }

        // Récupérer les agents RS qui ne sont pas déjà responsables d'une structure
        // ou qui sont déjà responsables de cette structure
        $agents = Agent::with('user')
            ->where('role_agent', 'RS')
            ->where(function ($query) use ($structure) {
                $query->whereDoesntHave('structureResponsable')
                    ->orWhere('id', $structure->responsable_id);
            })
            ->get();

        return Inertia::render('Agent/RS/StructureForm', [
            'structure' => $structure,
            'agents' => $agents
        ]);
    }

    public function update(Structure $structure)
    {
        $user = Auth::user();
        $agent = $user->agent;

        // Vérifier si l'agent est responsable d'une structure
        $structureResponsable = Structure::where('responsable_id', $agent->id)->first();

        if (!$structureResponsable) {
            return redirect()->route('agent.rs.organigramme.index')
                ->with('error', 'Vous n\'êtes pas responsable d\'une structure. Vous ne pouvez pas modifier de structure.');
        }

        // Vérifier que la structure est bien celle dont l'agent est responsable
        // ou une sous-structure de celle-ci
        $isAuthorized = $structure->id === $structureResponsable->id;

        if (!$isAuthorized) {
            // Vérifier si c'est une sous-structure de la structure dont l'agent est responsable
            if ($structureResponsable->parent_id === null) {
                $isAuthorized = $structure->parent_id === $structureResponsable->id;
            } else {
                // Si l'agent est responsable d'une sous-structure, il ne peut modifier que cette sous-structure
                $isAuthorized = false;
            }
        }

        if (!$isAuthorized) {
            return redirect()->route('agent.rs.organigramme.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette structure.');
        }

        $data = request()->validate([
            'libelle' => 'required|string|max:255',
            'sigle' => 'required|string|max:50',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:structures,id',
            'responsable_id' => 'nullable|exists:agents,id'
        ]);

        // Si le responsable a changé
        if (isset($data['responsable_id']) && $data['responsable_id'] != $structure->responsable_id) {
            // Si l'ancien responsable existe, mettre à jour son rôle si nécessaire
            if ($structure->responsable_id) {
                $oldResponsable = Agent::find($structure->responsable_id);
                // Vérifier si l'agent n'est pas responsable d'une autre structure
                if ($oldResponsable && !Structure::where('responsable_id', $oldResponsable->id)
                    ->where('id', '!=', $structure->id)
                    ->exists()) {
                    // Optionnel: changer le rôle de l'ancien responsable
                    // $oldResponsable->update(['role_agent' => 'SR']);
                }
            }

            // Si un nouveau responsable est assigné
            if ($data['responsable_id']) {
                $newResponsable = Agent::find($data['responsable_id']);
                if ($newResponsable && $newResponsable->role_agent !== 'RS') {
                    $newResponsable->update(['role_agent' => 'RS']);
                }
            }
        }

        $structure->update($data);

        Log::info('Structure mise à jour', [
            'structure_id' => $structure->id,
            'libelle' => $structure->libelle,
            'agent_id' => $agent->id
        ]);

        return redirect()->route('agent.rs.organigramme.index')
            ->with('success', 'Structure mise à jour avec succès');
    }

    public function destroy(Structure $structure)
    {
        $user = Auth::user();
        $agent = $user->agent;

        // Vérifier si l'agent est responsable d'une structure
        $structureResponsable = Structure::where('responsable_id', $agent->id)->first();

        if (!$structureResponsable) {
            return redirect()->route('agent.rs.organigramme.index')
                ->with('error', 'Vous n\'êtes pas responsable d\'une structure. Vous ne pouvez pas supprimer de structure.');
        }

        // Vérifier que la structure est bien celle dont l'agent est responsable
        // ou une sous-structure de celle-ci
        $isAuthorized = $structure->id === $structureResponsable->id;

        if (!$isAuthorized) {
            // Vérifier si c'est une sous-structure de la structure dont l'agent est responsable
            if ($structureResponsable->parent_id === null) {
                $isAuthorized = $structure->parent_id === $structureResponsable->id;
            } else {
                // Si l'agent est responsable d'une sous-structure, il ne peut supprimer que cette sous-structure
                $isAuthorized = false;
            }
        }

        if (!$isAuthorized) {
            return redirect()->route('agent.rs.organigramme.index')
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer cette structure.');
        }

        // Vérifier si la structure a des sous-structures
        if (Structure::where('parent_id', $structure->id)->exists()) {
            return redirect()->route('agent.rs.organigramme.index')
                ->with('error', 'Impossible de supprimer cette structure car elle contient des sous-structures');
        }

        // Vérifier si la structure a des agents
        if (Agent::where('structure_id', $structure->id)->exists()) {
            return redirect()->route('agent.rs.organigramme.index')
                ->with('error', 'Impossible de supprimer cette structure car elle contient des agents');
        }

        // Vérifier si c'est la structure principale dont l'agent est responsable
        if ($structure->id === $structureResponsable->id && $structure->parent_id === null) {
            return redirect()->route('agent.rs.organigramme.index')
                ->with('error', 'Impossible de supprimer la structure principale dont vous êtes responsable');
        }

        $structure->delete();

        Log::info('Structure supprimée', [
            'structure_id' => $structure->id,
            'libelle' => $structure->libelle,
            'agent_id' => $agent->id
        ]);

        return redirect()->route('agent.rs.organigramme.index')
            ->with('success', 'Structure supprimée avec succès');
    }

    public function affecter($structureId)
    {
        $user = Auth::user();
        $agent = $user->agent;

        // Vérifier si l'agent est responsable d'une structure
        $structureResponsable = Structure::where('responsable_id', $agent->id)->first();

        if (!$structureResponsable) {
            return redirect()->route('agent.rs.organigramme.index')
                ->with('error', 'Vous n\'êtes pas responsable d\'une structure. Vous ne pouvez pas affecter d\'agents.');
        }

        $structure = Structure::findOrFail($structureId);

        // Vérifier que la structure est bien celle dont l'agent est responsable
        // ou une sous-structure de celle-ci
        $isAuthorized = $structure->id === $structureResponsable->id;

        if (!$isAuthorized) {
            // Vérifier si c'est une sous-structure de la structure dont l'agent est responsable
            if ($structureResponsable->parent_id === null) {
                $isAuthorized = $structure->parent_id === $structureResponsable->id;
            } else {
                // Si l'agent est responsable d'une sous-structure, il ne peut affecter des agents qu'à cette sous-structure
                $isAuthorized = false;
            }
        }

        if (!$isAuthorized) {
            return redirect()->route('agent.rs.organigramme.index')
                ->with('error', 'Vous n\'êtes pas autorisé à affecter des agents à cette structure.');
        }

        // Récupérer les agents qui peuvent être affectés à cette structure
        $agents = Agent::with('user')
            ->whereNull('structure_id')
            ->orWhere('structure_id', '!=', $structureId)
            ->get();

        // Récupérer les agents déjà affectés à cette structure
        $agentsAffectes = Agent::with('user')
            ->where('structure_id', $structureId)
            ->get();

        return Inertia::render('Agent/RS/AffecterAgents', [
            'structure' => $structure,
            'agents' => $agents,
            'agentsAffectes' => $agentsAffectes,
            'structureResponsable' => $structureResponsable
        ]);
    }

    public function affecterAgent($structureId)
    {
        $user = Auth::user();
        $agent = $user->agent;

        // Vérifier si l'agent est responsable d'une structure
        $structureResponsable = Structure::where('responsable_id', $agent->id)->first();

        if (!$structureResponsable) {
            return redirect()->route('agent.rs.organigramme.index')
                ->with('error', 'Vous n\'êtes pas responsable d\'une structure. Vous ne pouvez pas affecter d\'agents.');
        }

        $data = request()->validate([
            'agent_id' => 'required|exists:agents,id',
        ]);

        $structure = Structure::findOrFail($structureId);

        // Vérifier que la structure est bien celle dont l'agent est responsable
        // ou une sous-structure de celle-ci
        $isAuthorized = $structure->id === $structureResponsable->id;

        if (!$isAuthorized) {
            // Vérifier si c'est une sous-structure de la structure dont l'agent est responsable
            if ($structureResponsable->parent_id === null) {
                $isAuthorized = $structure->parent_id === $structureResponsable->id;
            } else {
                // Si l'agent est responsable d'une sous-structure, il ne peut affecter des agents qu'à cette sous-structure
                $isAuthorized = false;
            }
        }

        if (!$isAuthorized) {
            return redirect()->route('agent.rs.organigramme.index')
                ->with('error', 'Vous n\'êtes pas autorisé à affecter des agents à cette structure.');
        }

        $agentToAffect = Agent::findOrFail($data['agent_id']);

        $agentToAffect->update(['structure_id' => $structureId]);

        Log::info('Agent affecté à une structure', [
            'agent_id' => $agentToAffect->id,
            'structure_id' => $structureId,
            'responsable_id' => $agent->id
        ]);

        return redirect()->route('agent.rs.structures.affecter', $structureId)
            ->with('success', 'Agent affecté avec succès');
    }

    public function desaffecterAgent($structureId, $agentId)
    {
        $user = Auth::user();
        $currentAgent = $user->agent;

        // Vérifier si l'agent est responsable d'une structure
        $structureResponsable = Structure::where('responsable_id', $currentAgent->id)->first();

        if (!$structureResponsable) {
            return redirect()->route('agent.rs.organigramme.index')
                ->with('error', 'Vous n\'êtes pas responsable d\'une structure. Vous ne pouvez pas désaffecter d\'agents.');
        }

        $structure = Structure::findOrFail($structureId);

        // Vérifier que la structure est bien celle dont l'agent est responsable
        // ou une sous-structure de celle-ci
        $isAuthorized = $structure->id === $structureResponsable->id;

        if (!$isAuthorized) {
            // Vérifier si c'est une sous-structure de la structure dont l'agent est responsable
            if ($structureResponsable->parent_id === null) {
                $isAuthorized = $structure->parent_id === $structureResponsable->id;
            } else {
                // Si l'agent est responsable d'une sous-structure, il ne peut désaffecter des agents que de cette sous-structure
                $isAuthorized = false;
            }
        }

        if (!$isAuthorized) {
            return redirect()->route('agent.rs.organigramme.index')
                ->with('error', 'Vous n\'êtes pas autorisé à désaffecter des agents de cette structure.');
        }

        $agentToDesaffect = Agent::findOrFail($agentId);

        // Vérifier si l'agent est le responsable de la structure
        if ($structure->responsable_id == $agentToDesaffect->id) {
            return redirect()->route('agent.rs.structures.affecter', $structureId)
                ->with('error', 'Impossible de désaffecter le responsable de la structure');
        }

        $agentToDesaffect->update(['structure_id' => null]);

        Log::info('Agent désaffecté d\'une structure', [
            'agent_id' => $agentToDesaffect->id,
            'structure_id' => $structureId,
            'responsable_id' => $currentAgent->id
        ]);

        return redirect()->route('agent.rs.structures.affecter', $structureId)
            ->with('success', 'Agent désaffecté avec succès');
    }
}
