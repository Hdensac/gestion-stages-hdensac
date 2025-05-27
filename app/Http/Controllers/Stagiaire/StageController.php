<?php

namespace App\Http\Controllers\Stagiaire;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\Stagiaire;
use App\Models\DemandeStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class StageController extends Controller
{
    /**
     * Afficher la liste des stages du stagiaire
     */
    public function index()
    {
        $user = Auth::user();
        
        try {
            // Récupérer le stagiaire associé à l'utilisateur
            $stagiaire = Stagiaire::where('user_id', $user->id)->first();
            
            if (!$stagiaire) {
                Log::error('Stagiaire non trouvé pour l\'utilisateur', [
                    'user_id' => $user->id
                ]);
                return Inertia::render('Stagiaire/MesStages', [
                    'stages' => [],
                    'message' => 'Aucun profil de stagiaire trouvé pour cet utilisateur.'
                ]);
            }
            
            // Récupérer les demandes de stage du stagiaire
            $demandeIds = DemandeStage::where('stagiaire_id', $stagiaire->id_stagiaire)
                ->whereIn('statut', ['Acceptée', 'Approuvée'])
                ->pluck('id');
            
            // Récupérer les stages associés à ces demandes
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
                ->get();
            
            // Ajouter des informations supplémentaires pour chaque stage
            $stages = $stages->map(function ($stage) {
                // Calcul dynamique du statut selon les dates
                $aujourdhui = now()->toDateString();
                if ($stage->date_debut > $aujourdhui) {
                    $stage->statut_calculé = 'À venir';
                } elseif ($stage->date_debut <= $aujourdhui && $stage->date_fin >= $aujourdhui) {
                    $stage->statut_calculé = 'En cours';
                } else {
                    $stage->statut_calculé = 'Terminé';
                }
                
                // Déterminer le maître de stage actuel
                $activeAffectation = $stage->affectationsMaitreStage
                    ->whereIn('statut', ['En cours', 'Acceptée'])
                    ->first();
                if ($activeAffectation) {
                    $stage->maitre_stage_actuel = [
                        'id' => $activeAffectation->maitreStage->id,
                        'nom' => $activeAffectation->maitreStage->nom,
                        'prenom' => $activeAffectation->maitreStage->prenom,
                        'email' => $activeAffectation->maitreStage->email,
                    ];
                }
                return $stage;
            });
            
            return Inertia::render('Stagiaire/MesStages', [
                'stages' => $stages,
                'notifications' => Auth::user()->unreadNotifications()->orderBy('created_at', 'desc')->take(10)->get(),
                'message' => isset($message) ? $message : null,
                'error' => isset($error) ? $error : null,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des stages du stagiaire', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            
            return Inertia::render('Stagiaire/MesStages', [
                'stages' => [],
                'error' => 'Une erreur est survenue lors du chargement de vos stages.'
            ]);
        }
    }
    
    /**
     * Afficher les détails d'un stage
     */
    public function show(Stage $stage)
    {
        $user = Auth::user();
        
        try {
            // Récupérer le stagiaire associé à l'utilisateur
            $stagiaire = Stagiaire::where('user_id', $user->id)->first();
            
            if (!$stagiaire) {
                return redirect()->route('stagiaire.stages')->with('error', 'Aucun profil de stagiaire trouvé pour cet utilisateur.');
            }
            
            // Vérifier que le stage appartient bien au stagiaire
            $demandeStage = DemandeStage::where('id', $stage->demande_stage_id)
                ->where('stagiaire_id', $stagiaire->id_stagiaire)
                ->first();
            
            if (!$demandeStage) {
                return redirect()->route('stagiaire.stages')->with('error', 'Vous n\'êtes pas autorisé à accéder à ce stage.');
            }
            
            // Charger les relations nécessaires
            $stage->load([
                'structure',
                'demandeStage',
                'themeStage',
                'affectationsMaitreStage' => function($query) {
                    $query->orderBy('date_affectation', 'desc');
                },
                'affectationsMaitreStage.maitreStage',
            ]);

            // Injecter le même champ maitre_stage_actuel que dans la liste
            $activeAffectation = $stage->affectationsMaitreStage
                ->whereIn('statut', ['En cours', 'Acceptée'])
                ->first();
            if (!$activeAffectation) {
                // Prendre la dernière affectation avec maitreStage si aucune n'est "En cours" ou "Acceptée"
                $activeAffectation = $stage->affectationsMaitreStage->filter(function($aff) { return $aff->maitreStage; })->sortByDesc('date_affectation')->first();
            }
            if ($activeAffectation && $activeAffectation->maitreStage) {
                $stage->maitre_stage_actuel = [
                    'id' => $activeAffectation->maitreStage->id,
                    'nom' => $activeAffectation->maitreStage->nom,
                    'prenom' => $activeAffectation->maitreStage->prenom,
                    'email' => $activeAffectation->maitreStage->email,
                ];
            }
            
            // Calcul dynamique du statut selon les dates (comme dans la liste)
            $aujourdhui = now()->toDateString();
            if ($stage->date_debut > $aujourdhui) {
                $stage->statut_calculé = 'À venir';
            } elseif ($stage->date_debut <= $aujourdhui && $stage->date_fin >= $aujourdhui) {
                $stage->statut_calculé = 'En cours';
            } else {
                $stage->statut_calculé = 'Terminé';
            }
            
            return Inertia::render('Stagiaire/ShowStage', [
                'stage' => $stage,
                'notifications' => Auth::user()->unreadNotifications()->orderBy('created_at', 'desc')->take(10)->get(),
                'error' => session('error'),
                'success' => session('success'),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des détails du stage', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'user_id' => $user->id
            ]);
            
            return redirect()->route('stagiaire.stages')->with('error', 'Une erreur est survenue lors du chargement des détails du stage.');
        }
    }
}
