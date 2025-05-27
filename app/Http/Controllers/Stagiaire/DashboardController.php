<?php

namespace App\Http\Controllers\Stagiaire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\DemandeStage;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Stage;
use Illuminate\Notifications\DatabaseNotification;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $stagiaire = $user->stagiaire;

        // Récupérer uniquement les structures principales (parent_id = null)
        $structures = Structure::where('active', true)
            ->whereNull('parent_id')
            ->get();

        // Récupérer tous les utilisateurs pour la sélection des membres
        $users = User::where('role', 'stagiaire')
            ->where('id', '!=', $user->id)
            ->get();

        // Récupérer les stages du stagiaire (3 plus récents)
        $stages = collect();
        if ($stagiaire) {
            $demandeIds = \App\Models\DemandeStage::where('stagiaire_id', $stagiaire->id_stagiaire)
                ->whereIn('statut', ['Acceptée', 'Approuvée'])
                ->pluck('id');
            $stages = Stage::whereIn('demande_stage_id', $demandeIds)
                ->with([
                    'structure',
                    'demandeStage',
                    'themeStage',
                    'affectationsMaitreStage' => function($query) {
                        $query->orderBy('date_affectation', 'desc');
                    },
                    'affectationsMaitreStage.maitreStage',
                ])
                ->orderByDesc('date_debut')
                ->take(3)
                ->get();
            // Calcul du statut dynamique
            $stages = $stages->map(function ($stage) {
                $aujourdhui = now()->toDateString();
                if ($stage->date_debut > $aujourdhui) {
                    $stage->statut_calculé = 'À venir';
                } elseif ($stage->date_debut <= $aujourdhui && $stage->date_fin >= $aujourdhui) {
                    $stage->statut_calculé = 'En cours';
                } else {
                    $stage->statut_calculé = 'Terminé';
                }
                $activeAffectation = $stage->affectationsMaitreStage
                    ->where('statut', 'En cours')
                    ->first();
                if ($activeAffectation && $activeAffectation->maitreStage) {
                    $stage->maitre_stage_actuel = [
                        'id' => $activeAffectation->maitreStage->id,
                        'nom' => $activeAffectation->maitreStage->nom,
                        'prenom' => $activeAffectation->maitreStage->prenom,
                        'email' => $activeAffectation->maitreStage->email,
                    ];
                }
                return $stage;
            });
        }

        // Log pour vérifier les informations du stagiaire
        Log::info('Informations du stagiaire', [
            'user_id' => $user->id,
            'stagiaire' => $stagiaire,
            'stagiaire_id' => $stagiaire ? $stagiaire->id_stagiaire : null
        ]);

        if (!$stagiaire) {
            Log::error('Stagiaire non trouvé pour l\'utilisateur', [
                'user_id' => $user->id
            ]);
            return Inertia::render('Stagiaire/Dashboard', [
                'demandes' => [],
                'stats' => [
                    'total_demandes' => 0,
                    'en_attente' => 0,
                    'approuvees' => 0,
                    'refusees' => 0,
                ],
                'structures' => $structures,
                'users' => $users,
                'stages' => $stages,
                'auth' => [
                    'user' => $user,
                ],
            ]);
        }

        // Récupérer toutes les demandes pour les statistiques
        $allDemandes = DemandeStage::where('stagiaire_id', $stagiaire->id_stagiaire)->get();

        // Log pour vérifier les demandes trouvées
        Log::info('Demandes trouvées', [
            'nombre_demandes' => $allDemandes->count(),
            'demandes' => $allDemandes->toArray()
        ]);

        // Récupérer les 5 dernières demandes pour l'affichage
        $recentDemandes = DemandeStage::where('stagiaire_id', $stagiaire->id_stagiaire)
            ->with(['structure'])
            ->latest()
            ->take(5)
            ->get();

        // Récupérer les notifications non lues du stagiaire connecté
        $notifications = [];
        if (Auth::user()) {
            $notifications = Auth::user()->unreadNotifications()->orderBy('created_at', 'desc')->take(10)->get();
        }

        return Inertia::render('Stagiaire/Dashboard', [
            'demandes' => $recentDemandes,
            'stats' => [
                'total_demandes' => $allDemandes->count(),
                'en_attente' => $allDemandes->where('statut', 'En attente')->count(),
                'approuvees' => $allDemandes->where('statut', 'Approuvée')->count(),
                'refusees' => $allDemandes->where('statut', 'Refusée')->count(),
            ],
            'structures' => $structures,
            'users' => $users,
            'stages' => $stages,
            'auth' => [
                'user' => $user,
            ],
            'notifications' => $notifications,
        ]);
    }
}