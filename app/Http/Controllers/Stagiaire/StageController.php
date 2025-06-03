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
            
            // Récupérer les stages où le stagiaire est membre du groupe
            $stagesMembreGroupe = Stage::whereHas('demandeStage', function($q) use ($user) {
                $q->whereIn('statut', ['Acceptée', 'Approuvée'])
                  ->whereHas('membres', function($query) use ($user) {
                      $query->where('user_id', $user->id);
                  });
            })->pluck('id');

            // Récupérer les stages associés à ces demandes
            $stages = Stage::where(function($query) use ($demandeIds, $stagesMembreGroupe) {
                $query->whereIn('demande_stage_id', $demandeIds)
                    ->orWhereIn('id', $stagesMembreGroupe);
            })
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
                // Aplatir la relation themeStage
                $stageArray = $stage->toArray();
                $stageArray['themeStage'] = $stage->themeStage ? $stage->themeStage->toArray() : null;
                return $stageArray;
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
            
            // Vérifier que le stage appartient bien au stagiaire principal OU à un membre du groupe
            $demandeStage = DemandeStage::where('id', $stage->demande_stage_id)->first();
            $isPrincipal = $demandeStage && $demandeStage->stagiaire_id == $stagiaire->id_stagiaire;
            $isMembre = $demandeStage && $demandeStage->membres()->where('user_id', $user->id)->exists();
            
            if (!$isPrincipal && !$isMembre) {
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
            
            \Log::info('DEBUG_STAGIAIRE_STAGE_SHOW', [
                'stage_id' => $stage->id,
                'theme_stage_id' => $stage->theme_stage_id,
                'themeStage' => $stage->themeStage
            ]);
            return Inertia::render('Stagiaire/ShowStage', [
                'stage' => array_merge($stage->toArray(), [
                    'themeStage' => $stage->themeStage ? $stage->themeStage->toArray() : null
                ]),
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

    /**
     * Proposer un nouveau thème pour un stage (par le stagiaire)
     */
    public function proposerTheme(Request $request, Stage $stage)
    {
        $user = Auth::user();
        $stagiaire = Stagiaire::where('user_id', $user->id)->first();
        if (!$stagiaire) {
            return response()->json(['success' => false, 'message' => 'Aucun profil de stagiaire trouvé.'], 403);
        }
        // Vérifier que le stage appartient bien au stagiaire principal OU à un membre du groupe
        $demandeStage = DemandeStage::where('id', $stage->demande_stage_id)->first();
        $isPrincipal = $demandeStage && $demandeStage->stagiaire_id == $stagiaire->id_stagiaire;
        $isMembre = $demandeStage && $demandeStage->membres()->where('user_id', $user->id)->exists();
        if (!$isPrincipal && !$isMembre) {
            return response()->json(['success' => false, 'message' => 'Non autorisé.'], 403);
        }
        $validated = $request->validate([
            'intitule' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'mots_cles' => 'nullable|string|max:255',
        ]);
        $theme = $stage->themesProposes()->create([
            'intitule' => $validated['intitule'],
            'description' => $validated['description'],
            'etat' => 'Proposé',
            'mots_cles' => $validated['mots_cles'] ?? null,
            'propose_par' => 'stagiaire',
            'user_id' => $user->id,
        ]);
        return response()->json(['success' => true, 'theme' => $theme]);
    }
}
