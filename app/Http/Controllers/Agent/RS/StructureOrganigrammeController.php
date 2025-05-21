<?php

namespace App\Http\Controllers\Agent\RS;

use App\Http\Controllers\Controller;
use App\Models\Structure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class StructureOrganigrammeController extends Controller
{
    // Affiche l'organigramme de la structure du RS connecté
    public function index()
    {
        $user = auth()->user();
        $agent = $user->agent;
        $structure = Structure::where('responsable_id', $agent->id)->first();
        $arbre = $structure
            ? $structure->load(['children.children.children'])
            : null;

        // Récupérer les agents avec le rôle MS qui appartiennent à la structure du RS connecté
        $structureId = $structure ? $structure->id : null;
        $agents = \App\Models\Agent::with('user')
            ->where('role_agent', 'MS')
            ->where('structure_id', $structureId)
            ->get();

        // Débogage détaillé
        \Illuminate\Support\Facades\Log::info('Agents récupérés pour l\'organigramme', [
            'structure_id' => $structure ? $structure->id : null,
            'nombre_agents' => $agents->count(),
            'agents' => $agents->map(function($agent) {
                return [
                    'id' => $agent->id,
                    'user_id' => $agent->user_id,
                    'nom' => $agent->user ? $agent->user->nom : null,
                    'prenom' => $agent->user ? $agent->user->prenom : null,
                    'role_agent' => $agent->role_agent,
                    'structure_id' => $agent->structure_id
                ];
            })
        ]);

        return Inertia::render('Agent/RS/Organigramme/Index', [
            'structure' => $arbre,
            'agents' => $agents,
        ]);
    }

    // Ajoute une sous-structure
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sigle' => 'required|string|max:255',
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:structures,id',
            'type_structure' => 'nullable|string|max:255',
        ]);
        Structure::create([
            'sigle' => $validated['sigle'],
            'libelle' => $validated['libelle'],
            'description' => $validated['description'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
            'type_structure' => $validated['type_structure'] ?? null,
            'niveau' => $validated['parent_id'] ? (Structure::find($validated['parent_id'])->niveau + 1) : 0,
        ]);
        return redirect()->back()->with('success', 'Sous-structure ajoutée.');
    }

    // Modifie une structure
    public function update(Request $request, Structure $structure)
    {

        $validated = $request->validate([
            'sigle' => 'required|string|max:255',
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type_structure' => 'nullable|string|max:255',
        ]);
        $result = $structure->update($validated);
        \Illuminate\Support\Facades\Log::info('Résultat update', [
            'structure_id' => $structure->id,
            'result' => $result,
            'nouvelle_valeur' => $structure->fresh()
        ]);
        return redirect()->back()->with('success', 'Structure modifiée.');
    }

    // Supprime une structure (et ses enfants)
    public function destroy(Structure $structure)
    {
        // Empêcher la suppression de la racine (pas de parent_id)
        if (is_null($structure->parent_id)) {
            return redirect()->back()->with('error', 'Impossible de supprimer la structure racine.');
        }
        $structure->delete();
        return redirect()->back()->with('success', 'Structure supprimée.');
    }

    // Affecter un agent à une sous-structure
    public function assignAgent(Request $request, Structure $structure)
    {
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
        ]);

        $agent = \App\Models\Agent::find($request->agent_id);

        // Vérifier si l'agent est déjà responsable d'une autre structure
        $autresStructures = \App\Models\Structure::where('responsable_id', $agent->id)->where('id', '!=', $structure->id)->get();

        if ($autresStructures->count() > 0) {
            $nomsStructures = $autresStructures->pluck('libelle')->implode(', ');

            \Illuminate\Support\Facades\Log::warning('Tentative d\'affecter un agent déjà responsable d\'autres structures', [
                'agent_id' => $agent->id,
                'structure_id_tentative' => $structure->id,
                'structures_existantes' => $autresStructures->pluck('id')->toArray()
            ]);

            return redirect()->back()->with('error', 'Cet agent est déjà responsable d\'une autre structure (' . $nomsStructures . '). Un agent ne peut être responsable que d\'une seule structure.');
        }

        // Vérifier si la structure a déjà un responsable
        if ($structure->responsable_id !== null) {
            $ancienResponsable = \App\Models\Agent::find($structure->responsable_id);
            $nomAncienResponsable = $ancienResponsable ? $ancienResponsable->user->nom . ' ' . $ancienResponsable->user->prenom : 'Inconnu';

            // Si l'utilisateur essaie d'affecter le même agent, ne rien faire
            if ($structure->responsable_id == $agent->id) {
                return redirect()->back()->with('info', 'Cet agent est déjà le responsable de cette structure.');
            }

            // Sinon, informer que nous allons remplacer l'ancien responsable
            $messageLog = 'Remplacement du responsable de la structure';
            $messageSuccess = 'Responsable de la structure remplacé avec succès.';

            \Illuminate\Support\Facades\Log::info('Ancien responsable remplacé', [
                'structure_id' => $structure->id,
                'ancien_responsable_id' => $structure->responsable_id,
                'ancien_responsable_nom' => $nomAncienResponsable,
                'nouveau_responsable_id' => $agent->id
            ]);
        } else {
            $messageLog = 'Agent affecté comme responsable de la structure';
            $messageSuccess = 'Agent affecté comme responsable de la structure.';
        }

        // Mettre à jour uniquement la structure avec l'ID de l'agent comme responsable
        $structure->responsable_id = $agent->id; // Utiliser l'ID de l'agent et non l'ID de l'utilisateur
        $structure->save();

        // Ne pas mettre à jour la structure_id de l'agent

        \Illuminate\Support\Facades\Log::info($messageLog, [
            'agent_id' => $agent->id,
            'structure_id' => $structure->id,
            'user_id' => $agent->user_id,
            'responsable_id_updated' => $structure->responsable_id,
            'structure_id_agent_non_modifie' => $agent->structure_id
        ]);

        return redirect()->back()->with('success', $messageSuccess);
    }

    // Retourne toutes les sous-structures du RS connecté (liste plate)
    public function sousStructures()
    {
        $user = Auth::user();
        $agent = $user->agent;
        $structure = \App\Models\Structure::where('responsable_id', $agent->id)->first();

        if (!$structure) {
            \Log::warning('Aucune structure principale trouvée pour l\'agent RS', ['agent_id' => $agent->id]);
            return response()->json([]);
        }

        $structure->load('children.children.children');
        $getAllChildren = function ($structure) use (&$getAllChildren) {
            $result = [];
            foreach ($structure->children as $child) {
                $result[] = [
                    'id' => $child->id,
                    'libelle' => $child->libelle,
                    'sigle' => $child->sigle,
                    // Ajoute d'autres champs si besoin
                ];
                $result = array_merge($result, $getAllChildren($child));
            }
            return $result;
        };

        $sousStructures = $getAllChildren($structure);

        return response()->json($sousStructures);
    }

    public function show($id)
    {
        return response()->json(['message' => 'Not implemented'], 404);
    }
}
