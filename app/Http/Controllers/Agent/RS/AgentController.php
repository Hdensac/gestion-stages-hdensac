<?php

namespace App\Http\Controllers\Agent\RS;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    public function index()
    {
        $rs = auth()->user()->agent;
        $structure = \App\Models\Structure::where('responsable_id', $rs->id)->first();
        $agents = [];
        if ($structure) {
            $agents = \App\Models\Agent::with('user')
                ->where('role_agent', 'MS')
                ->where('structure_id', $structure->id)
                ->get();
        }
        return Inertia::render('Agent/RS/Agents/Index', ['agents' => $agents]);
    }

    public function create()
    {
        return Inertia::render('Agent/RS/Agents/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'               => 'required|string|max:255',
            'prenom'            => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|min:8',
            'telephone'         => 'required|integer',
            'date_de_naissance' => 'required|date',
            'sexe'              => 'required|in:Homme,Femme',
            'matricule'         => 'required|string|unique:agents',
            'fonction'          => 'required|string',
            'date_embauche'     => 'nullable|date',
        ]);
        $user = User::create([
            'nom'               => $validated['nom'],
            'prenom'            => $validated['prenom'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'role'              => 'agent',
            'telephone'         => $validated['telephone'],
            'date_de_naissance' => $validated['date_de_naissance'],
            'sexe'              => $validated['sexe'],
        ]);
        $rs = auth()->user()->agent;
        $structure = \App\Models\Structure::where('responsable_id', $rs->id)->first();
        $structureId = $structure ? $structure->id : null;
        Agent::create([
            'user_id'       => $user->id,
            'role_agent'    => 'MS',
            'structure_id'  => $structureId,
            'matricule'     => $validated['matricule'],
            'fonction'      => $validated['fonction'],
            'date_embauche' => $validated['date_embauche'] ?? null,
        ]);
        return redirect()->route('agent.rs.agents.index')->with('success', 'Agent ajouté.');
    }

    public function edit(Agent $agent)
    {
        $agent->load('user');
        return Inertia::render('Agent/RS/Agents/Edit', ['agent' => $agent]);
    }

    public function update(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'nom'               => 'required|string|max:255',
            'prenom'            => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $agent->user_id,
            'telephone'         => 'required|integer',
            'date_de_naissance' => 'required|date',
            'sexe'              => 'required|in:Homme,Femme',
            'matricule'         => 'required|string|unique:agents,matricule,' . $agent->id,
            'fonction'          => 'required|string',
            'date_embauche'     => 'nullable|date',
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
        ]);
        return redirect()->route('agent.rs.agents.index')->with('success', 'Agent modifié.');
    }

    public function destroy(Agent $agent)
    {
        $agent->user->delete();
        $agent->delete();
        return redirect()->route('agent.rs.agents.index')->with('success', 'Agent supprimé.');
    }
} 