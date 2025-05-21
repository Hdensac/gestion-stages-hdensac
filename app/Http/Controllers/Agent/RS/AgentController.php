<?php

namespace App\Http\Controllers\Agent\RS;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{
    public function index()
    {
        // Récupérer l'agent connecté
        $user = Auth::user();
        $rs = Agent::where('user_id', $user->id)->first();

        $structure = \App\Models\Structure::where('responsable_id', $rs->id)->first();
        $agents = [];
        if ($structure) {
            // Récupérer tous les IDs de la structure principale et de ses sous-structures
            $getAllSubStructureIds = function($parentId) use (&$getAllSubStructureIds) {
                $ids = [$parentId];
                $children = \App\Models\Structure::where('parent_id', $parentId)->pluck('id');
                foreach ($children as $childId) {
                    $ids = array_merge($ids, $getAllSubStructureIds($childId));
                }
                return $ids;
            };
            $allStructureIds = $getAllSubStructureIds($structure->id);

            // Récupérer les agents de toutes ces structures
            $agents = \App\Models\Agent::with(['user', 'structuresResponsable'])
                ->where('role_agent', 'MS')
                ->whereIn('structure_id', $allStructureIds)
                ->get();

            // Transformer les données pour inclure le nom de la structure dont l'agent est responsable
            $agents->transform(function ($agent) {
                if ($agent->structuresResponsable && $agent->structuresResponsable->count() > 0) {
                    $agent->structure_responsable = $agent->structuresResponsable->first()->libelle;
                } else {
                    $agent->structure_responsable = null;
                }
                return $agent;
            });
        }
        return Inertia::render('Agent/RS/Agents/Index', ['agents' => $agents]);
    }

    public function create()
    {
        // Récupérer l'agent connecté (RS)
        $user = Auth::user();
        $rs = Agent::where('user_id', $user->id)->first();

        // Récupérer la structure principale du RS
        $rsStructure = \App\Models\Structure::where('responsable_id', $rs->id)->first();

        // Récupérer toutes les sous-structures de la structure principale, y compris les sous-sous-structures
        $structures = [];
        if ($rsStructure) {
            // Récupérer toutes les sous-structures de manière récursive
            $getAllSubStructures = function($parentId, $level = 0, $maxDepth = 10) use (&$getAllSubStructures) {
                if ($level >= $maxDepth) {
                    return collect(); // Éviter les boucles infinies en cas de structure circulaire
                }

                $directSubStructures = \App\Models\Structure::where('parent_id', $parentId)
                    ->orderBy('libelle')
                    ->get(['id', 'libelle', 'sigle', 'parent_id', 'niveau']);

                $allSubStructures = $directSubStructures;

                foreach ($directSubStructures as $subStructure) {
                    $childrenSubStructures = $getAllSubStructures($subStructure->id, $level + 1, $maxDepth);
                    $allSubStructures = $allSubStructures->concat($childrenSubStructures);
                }

                return $allSubStructures;
            };

            // Récupérer toutes les sous-structures de manière récursive
            $structures = $getAllSubStructures($rsStructure->id);

            // Les structures sont déjà récupérées
        }

        return Inertia::render('Agent/RS/Agents/Create', [
            'structures' => $structures
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'                    => 'required|string|max:255',
            'prenom'                 => 'required|string|max:255',
            'email'                  => 'required|email|unique:users,email',
            'password'               => 'required|string|min:8',
            'telephone'              => 'required|integer',
            'date_de_naissance'      => 'required|date',
            'sexe'                   => 'required|in:Homme,Femme',
            'matricule'              => 'required|string|unique:agents',
            'fonction'               => 'required|string',
            'date_embauche'          => 'nullable|date',
            'structure_id'           => 'required|exists:structures,id',
            'structure_responsable_id' => 'nullable|exists:structures,id',
        ]);

        // Créer l'utilisateur
        $newUser = User::create([
            'nom'               => $validated['nom'],
            'prenom'            => $validated['prenom'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'role'              => 'agent',
            'telephone'         => $validated['telephone'],
            'date_de_naissance' => $validated['date_de_naissance'],
            'sexe'              => $validated['sexe'],
        ]);

        // Récupérer l'agent connecté (RS)
        $user = Auth::user();
        $rs = Agent::where('user_id', $user->id)->first();

        // Récupérer la structure principale du RS
        $structure = \App\Models\Structure::where('responsable_id', $rs->id)->first();
        $structureId = $structure ? $structure->id : null;

        // Créer l'agent
        $agent = Agent::create([
            'user_id'       => $newUser->id, // Utiliser l'ID du nouvel utilisateur créé
            'role_agent'    => 'MS',
            'structure_id'  => $validated['structure_id'],
            'matricule'     => $validated['matricule'],
            'fonction'      => $validated['fonction'],
            'date_embauche' => $validated['date_embauche'] ?? null,
        ]);

        // Gérer la structure dont l'agent sera responsable
        $structureResponsableId = $validated['structure_responsable_id'] ?? null;

        // Si un ID de structure est fourni
        if ($structureResponsableId) {
            // Vérifier si un autre agent est déjà responsable de cette structure
            $structureToAssign = \App\Models\Structure::find($structureResponsableId);

            if ($structureToAssign) {
                // Si la structure a déjà un responsable
                if ($structureToAssign->responsable_id) {
                    // Notifier que l'ancien responsable a été remplacé
                    $message = "L'agent a été créé et assigné comme responsable de la structure. L'ancien responsable a été remplacé.";
                } else {
                    $message = "L'agent a été créé et assigné comme responsable de la structure.";
                }

                // Assigner l'agent comme responsable de la structure
                $structureToAssign->responsable_id = $agent->id;
                $structureToAssign->save();

                return redirect()->route('agent.rs.agents.index')->with('success', $message);
            }
        }

        return redirect()->route('agent.rs.agents.index')->with('success', 'Agent ajouté.');
    }

    public function edit(Agent $agent)
    {
        // Récupérer l'agent connecté (RS)
        $user = Auth::user();
        $rs = Agent::where('user_id', $user->id)->first();

        // Récupérer la structure principale du RS
        $rsStructure = \App\Models\Structure::where('responsable_id', $rs->id)->first();

        // Récupérer toutes les sous-structures de la structure principale, y compris les sous-sous-structures
        $structures = [];
        if ($rsStructure) {
            // Récupérer toutes les sous-structures de manière récursive
            $getAllSubStructures = function($parentId, $level = 0, $maxDepth = 10) use (&$getAllSubStructures) {
                if ($level >= $maxDepth) {
                    return collect(); // Éviter les boucles infinies en cas de structure circulaire
                }

                $directSubStructures = \App\Models\Structure::where('parent_id', $parentId)
                    ->orderBy('libelle')
                    ->get(['id', 'libelle', 'sigle', 'parent_id', 'niveau']);

                $allSubStructures = $directSubStructures;

                foreach ($directSubStructures as $subStructure) {
                    $childrenSubStructures = $getAllSubStructures($subStructure->id, $level + 1, $maxDepth);
                    $allSubStructures = $allSubStructures->concat($childrenSubStructures);
                }

                return $allSubStructures;
            };

            // Récupérer toutes les sous-structures de manière récursive
            $structures = $getAllSubStructures($rsStructure->id);

            // Les structures sont déjà récupérées
        }

        // Charger l'agent avec ses relations
        $agent->load(['user', 'structuresResponsable']);

        // Ajouter l'ID de la structure dont l'agent est responsable (s'il y en a une)
        $agent->structure_responsable_id = $agent->structuresResponsable->first() ?
            $agent->structuresResponsable->first()->id : null;

        return Inertia::render('Agent/RS/Agents/Edit', [
            'agent' => $agent,
            'structures' => $structures
        ]);
    }

    public function update(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'nom'                    => 'required|string|max:255',
            'prenom'                 => 'required|string|max:255',
            'email'                  => 'required|email|unique:users,email,' . $agent->user_id,
            'telephone'              => 'required|integer',
            'date_de_naissance'      => 'required|date',
            'sexe'                   => 'required|in:Homme,Femme',
            'matricule'              => 'required|string|unique:agents,matricule,' . $agent->id,
            'fonction'               => 'required|string',
            'date_embauche'          => 'nullable|date',
            'structure_id'           => 'required|exists:structures,id',
            'structure_responsable_id' => 'nullable|exists:structures,id',
        ]);

        $agent->user->update([
            'nom'               => $validated['nom'],
            'prenom'            => $validated['prenom'],
            'email'             => $validated['email'],
            'telephone'         => $validated['telephone'],
            'date_de_naissance' => $validated['date_de_naissance'],
            'sexe'              => $validated['sexe'],
        ]);

        $agent->update([
            'matricule'     => $validated['matricule'],
            'fonction'      => $validated['fonction'],
            'date_embauche' => $validated['date_embauche'] ?? null,
            'structure_id'  => $validated['structure_id'],
        ]);

        // Gérer la structure dont l'agent est responsable
        $structureResponsableId = $validated['structure_responsable_id'] ?? null;

        // Récupérer la structure dont l'agent est actuellement responsable (s'il y en a une)
        $structureActuelle = \App\Models\Structure::where('responsable_id', $agent->id)->first();

        // Si l'agent est déjà responsable d'une structure et que l'ID est différent ou null
        if ($structureActuelle && ($structureActuelle->id != $structureResponsableId || $structureResponsableId === null)) {
            // Retirer l'agent comme responsable de la structure actuelle
            $structureActuelle->responsable_id = null;
            $structureActuelle->save();
        }

        // Si un nouvel ID de structure est fourni
        if ($structureResponsableId) {
            // Vérifier si un autre agent est déjà responsable de cette structure
            $structure = \App\Models\Structure::find($structureResponsableId);

            if ($structure) {
                // Si la structure a déjà un responsable différent de l'agent actuel
                if ($structure->responsable_id && $structure->responsable_id != $agent->id) {
                    // Notifier que l'ancien responsable a été remplacé
                    $message = "L'agent a été assigné comme responsable de la structure. L'ancien responsable a été remplacé.";
                } else {
                    $message = "L'agent a été assigné comme responsable de la structure.";
                }

                // Assigner l'agent comme responsable de la structure
                $structure->responsable_id = $agent->id;
                $structure->save();

                return redirect()->route('agent.rs.agents.index')->with('success', 'Agent modifié. ' . $message);
            }
        }

        return redirect()->route('agent.rs.agents.index')->with('success', 'Agent modifié.');
    }

    public function destroy(Agent $agent)
    {
        $agent->user->delete();
        $agent->delete();
        return redirect()->route('agent.rs.agents.index')->with('success', 'Agent supprimé.');
    }
}