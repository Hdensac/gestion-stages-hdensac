<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\DemandeStage;
use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();

            // Log pour le débogage
            Log::info('Tentative d\'accès au dashboard DPAF', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'has_agent' => $user->agent ? 'oui' : 'non',
                'agent_role' => $user->agent ? $user->agent->role_agent : 'aucun',
                'is_dpaf_responsable' => Structure::where('responsable_id', $user->agent->id)
                    ->where('sigle', 'DPAF')
                    ->exists() ? 'oui' : 'non'
            ]);

            // Vérifier si l'utilisateur a un agent associé
            if (!$user->agent) {
                return Inertia::render('Dashboard/Default', [
                    'error' => 'Vous n\'êtes pas un agent. User ID: ' . $user->id . ', Role: ' . $user->role
                ]);
            }

            // Vérifier si l'agent est un RS
            if ($user->agent->role_agent !== 'RS') {
                return Inertia::render('Dashboard/Default', [
                    'error' => 'Accès réservé aux Responsables de Structure (RS).'
                ]);
            }

            // Vérifier si l'agent est responsable de la structure DPAF
            $isDpafResponsable = Structure::where('responsable_id', $user->agent->id)
                ->where('sigle', 'DPAF')
                ->exists();

            if (!$isDpafResponsable) {
                return Inertia::render('Dashboard/Default', [
                    'error' => 'Accès réservé au responsable de la structure DPAF. Vous n\'êtes pas responsable de cette structure.'
                ]);
            }

            // Initialiser les variables avec des valeurs par défaut
            $stats = [
                'demandesEnAttente' => 0,
                'demandesTraitees' => 0,
                'demandesAffectees' => 0,
                'structuresActives' => 0,
            ];

            // Récupérer les statistiques de manière sécurisée
            try {
                $stats['demandesEnAttente'] = DemandeStage::where('statut', 'En attente')->count();
                $stats['demandesTraitees'] = DemandeStage::whereIn('statut', ['Acceptée', 'Refusée'])->count();
                $stats['demandesAffectees'] = DemandeStage::where('statut', 'En cours')->count();
                $stats['structuresActives'] = Structure::where('active', true)->count();
            } catch (\Exception $e) {
                Log::error('Erreur lors de la récupération des statistiques', [
                    'error' => $e->getMessage()
                ]);
            }

            // Récupérer les demandes de manière sécurisée
            try {
                $dernieresDemandes = DemandeStage::with(['stagiaire.user', 'structure'])
                    ->latest()
                    ->take(5)
                    ->get();
            } catch (\Exception $e) {
                Log::error('Erreur lors de la récupération des dernières demandes', [
                    'error' => $e->getMessage()
                ]);
                $dernieresDemandes = [];
            }

            // Récupérer les structures de manière sécurisée
            try {
                $structures = Structure::withCount('stagiaires')
                    ->where('active', true)
                    ->take(6)
                    ->get();
            } catch (\Exception $e) {
                Log::error('Erreur lors de la récupération des structures', [
                    'error' => $e->getMessage()
                ]);
                $structures = [];
            }

            return Inertia::render('Agent/Dashboard', [
                'stats' => $stats,
                'dernieresDemandes' => $dernieresDemandes,
                'structures' => $structures,
                'debug' => [
                    'userId' => $user->id,
                    'userRole' => $user->role,
                    'agentId' => $user->agent->id,
                    'agentRole' => $user->agent->role_agent,
                    'structureResponsable' => Structure::where('responsable_id', $user->agent->id)
                        ->where('sigle', 'DPAF')
                        ->first()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur critique dans le dashboard DPAF', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return Inertia::render('Dashboard/Default', [
                'error' => 'Une erreur est survenue : ' . $e->getMessage()
            ]);
        }
    }
}