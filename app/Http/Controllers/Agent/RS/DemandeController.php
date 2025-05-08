<?php

namespace App\Http\Controllers\Agent\RS;

use App\Http\Controllers\Controller;
use App\Models\DemandeStage;
use App\Models\Structure;
use App\Models\Agent;
use App\Mail\DemandeAcceptationMail;
use App\Mail\DemandeRefusMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class DemandeController extends Controller
{
    public function __construct()
    {
        // Vérification déplacée dans chaque méthode
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $agent = $user->agent;

        try {
            $structure = Structure::where('responsable_id', $agent->id)->first();

            if (!$structure) {
                return redirect()->back()->with('error', 'Vous n\'êtes responsable d\'aucune structure.');
            }

            // Construction de la requête de base
            $query = DemandeStage::with(['stagiaire.user'])
                ->where('structure_id', $structure->id);

            // Filtre par statut
            if ($request->filled('status')) {
                $query->where('statut', $request->status);
            }

            // Filtre par recherche
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('stagiaire.user', function ($q) use ($search) {
                    $q->where(function ($subQuery) use ($search) {
                        $subQuery->where('nom', 'like', "%{$search}%")
                                ->orWhere('prenom', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                    });
                });
            }

            // Filtre par période
            if ($request->filled('period')) {
                $now = now();
                switch ($request->period) {
                    case 'today':
                        $query->whereDate('created_at', $now->toDateString());
                        break;
                    case 'week':
                        $query->whereBetween('created_at', [
                            $now->startOfWeek()->toDateTimeString(),
                            $now->endOfWeek()->toDateTimeString()
                        ]);
                        break;
                    case 'month':
                        $query->whereMonth('created_at', $now->month)
                              ->whereYear('created_at', $now->year);
                        break;
                    case 'year':
                        $query->whereYear('created_at', $now->year);
                        break;
                }
            }

            // Récupération des demandes avec pagination
            $demandes = $query->latest()->paginate(10)->withQueryString();

            return Inertia::render('Agent/RS/Demandes/Index', [
                'demandes' => $demandes,
                'structure' => $structure,
                'filters' => $request->only(['status', 'search', 'period', 'page'])
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des demandes RS', [
                'error' => $e->getMessage(),
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement des demandes.');
        }
    }

    public function show(DemandeStage $demande)
    {
        $user = Auth::user();
        $agent = $user->agent;

        try {
            $structure = Structure::where('responsable_id', $agent->id)->first();

            if (!$structure || $demande->structure_id !== $structure->id) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à cette demande.');
            }

            $demande->load(['stagiaire.user', 'structure', 'membres.user']);

            return Inertia::render('Agent/RS/Demandes/Show', [
                'demande' => $demande,
                'membres' => $demande->nature === 'Groupe' ? $demande->membres : []
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage de la demande RS', [
                'error' => $e->getMessage(),
                'agent_id' => $agent->id,
                'demande_id' => $demande->id
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement de la demande.');
        }
    }

    public function approve(DemandeStage $demande)
    {
        $user = Auth::user();
        $agent = $user->agent;

        try {
            $structure = Structure::where('responsable_id', $agent->id)->first();

            if (!$structure || $demande->structure_id !== $structure->id) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à cette demande.');
            }

            // Charger les relations nécessaires pour l'email
            $demande->load(['stagiaire.user', 'structure']);

            // Mettre à jour le statut de la demande
            $demande->update([
                'statut' => 'Acceptée',
                'date_traitement' => now(),
                'traite_par' => $agent->id
            ]);

            // Créer un stage à partir de la demande acceptée
            $stage = \App\Models\Stage::firstOrCreate(
                ['demande_stage_id' => $demande->id],
                [
                    'structure_id' => $demande->structure_id,
                    'date_debut' => $demande->date_debut,
                    'date_fin' => $demande->date_fin,
                    'statut' => 'En cours',
                    'type' => $demande->type
                ]
            );

            Log::info('Stage créé lors de l\'acceptation de la demande', [
                'stage_id' => $stage->id,
                'demande_stage_id' => $demande->id,
                'structure_id' => $demande->structure_id,
                'was_created' => $stage->wasRecentlyCreated
            ]);

            // Envoyer l'email au stagiaire
            try {
                Mail::to($demande->stagiaire->user->email)
                    ->send(new DemandeAcceptationMail($demande, $demande->stagiaire->user));

                // Si c'est une demande de groupe, envoyer aux membres aussi
                if ($demande->nature === 'Groupe') {
                    foreach ($demande->membres as $membre) {
                        if ($membre->user && $membre->user->email !== $demande->stagiaire->user->email) {
                            Mail::to($membre->user->email)
                                ->send(new DemandeAcceptationMail($demande, $membre->user));
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de l\'email d\'acceptation', [
                    'error' => $e->getMessage(),
                    'demande_id' => $demande->id
                ]);
                // On continue même si l'email échoue
            }

            return redirect()->back()->with('success', 'La demande a été acceptée avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'approbation de la demande RS', [
                'error' => $e->getMessage(),
                'agent_id' => $agent->id,
                'demande_id' => $demande->id
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'approbation de la demande.');
        }
    }

    public function reject(Request $request, DemandeStage $demande)
    {
        $user = Auth::user();
        $agent = $user->agent;

        try {
            $structure = Structure::where('responsable_id', $agent->id)->first();

            if (!$structure || $demande->structure_id !== $structure->id) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à cette demande.');
            }

            // Valider le motif de refus
            $validated = $request->validate([
                'motif_refus' => 'required|string|max:500'
            ]);

            // Charger les relations nécessaires pour l'email
            $demande->load(['stagiaire.user', 'structure']);

            // Mettre à jour la demande
            $demande->update([
                'statut' => 'Refusée',
                'date_traitement' => now(),
                'traite_par' => $agent->id,
                'motif_refus' => $validated['motif_refus']
            ]);

            // Envoyer l'email au stagiaire
            try {
                Mail::to($demande->stagiaire->user->email)
                    ->send(new DemandeRefusMail($demande, $demande->stagiaire->user, $validated['motif_refus']));

                // Si c'est une demande de groupe, envoyer aux membres aussi
                if ($demande->nature === 'Groupe') {
                    foreach ($demande->membres as $membre) {
                        if ($membre->user && $membre->user->email !== $demande->stagiaire->user->email) {
                            Mail::to($membre->user->email)
                                ->send(new DemandeRefusMail($demande, $membre->user, $validated['motif_refus']));
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de l\'email de refus', [
                    'error' => $e->getMessage(),
                    'demande_id' => $demande->id
                ]);
                // On continue même si l'email échoue
            }

            return redirect()->back()->with('success', 'La demande a été refusée avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors du refus de la demande RS', [
                'error' => $e->getMessage(),
                'agent_id' => $agent->id,
                'demande_id' => $demande->id
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors du refus de la demande.');
        }
    }

    /**
     * Récupérer les agents avec le rôle MS (maître de stage)
     */
    public function getResponsableAgents()
    {
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Récupérer la structure dont l'agent est responsable
            $structure = Structure::where('responsable_id', $agent->id)->first();

            if (!$structure) {
                return response()->json([], 200);
            }

            // Récupérer tous les agents avec le rôle MS
            $msAgents = Agent::with(['user', 'structuresResponsable'])
                ->where('role_agent', 'MS')
                ->get()
                ->map(function ($agent) {
                    // Ajouter la structure dont l'agent est responsable (si applicable)
                    if ($agent->structuresResponsable()->exists()) {
                        $agent->structure_responsable = $agent->structuresResponsable->first();
                    } else {
                        $agent->structure_responsable = null;
                    }
                    return $agent;
                });

            // Journaliser le nombre d'agents trouvés pour le débogage
            Log::info('Agents MS trouvés', [
                'count' => $msAgents->count(),
                'structure_id' => $structure->id,
                'agents' => $msAgents->pluck('id')->toArray()
            ]);

            // Si aucun agent n'est trouvé, retourner des données de test
            if ($msAgents->isEmpty()) {
                // Créer un agent de test avec le rôle MS si aucun n'existe
                $testUser = \App\Models\User::firstOrCreate(
                    ['email' => 'testms@example.com'],
                    [
                        'nom' => 'Test',
                        'prenom' => 'MS',
                        'password' => bcrypt('password'),
                        'role' => 'agent'
                    ]
                );

                $testAgent = \App\Models\Agent::firstOrCreate(
                    ['user_id' => $testUser->id],
                    [
                        'matricule' => 'MS'.rand(1000, 9999),
                        'fonction' => 'Maître de Stage',
                        'role_agent' => 'MS',
                        'structure_id' => $structure->id
                    ]
                );

                // Recharger l'agent avec ses relations
                $testAgent->load('user');

                Log::info('Agent de test créé', [
                    'agent_id' => $testAgent->id,
                    'user_id' => $testUser->id
                ]);

                return response()->json([$testAgent], 200);
            }

            return response()->json($msAgents, 200);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des maîtres de stage', [
                'error' => $e->getMessage(),
                'agent_id' => $agent->id
            ]);

            return response()->json([], 500);
        }
    }



    /**
     * Assigner un agent à une demande
     */
    public function assignAgent(Request $request, DemandeStage $demande)
    {
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Vérifier que l'agent est bien responsable de la structure concernée
            $structure = Structure::where('responsable_id', $agent->id)->first();

            if (!$structure || $demande->structure_id !== $structure->id) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à cette demande.');
            }

            // Valider l'ID de l'agent
            $validated = $request->validate([
                'agent_id' => 'required|exists:agents,id'
            ]);

            // Récupérer l'agent à assigner
            $agentToAssign = Agent::findOrFail($validated['agent_id']);

            // Vérifier que l'agent à assigner est bien responsable d'une sous-structure
            if (!$agentToAssign->structuresResponsable()->exists()) {
                return redirect()->back()->with('error', 'Cet agent n\'est pas responsable d\'une sous-structure.');
            }

            // Mettre à jour la demande avec l'agent assigné
            $demande->update([
                'agent_id' => $agentToAssign->id
            ]);

            return redirect()->back()->with('success', 'L\'agent a été assigné à la demande avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'assignation de l\'agent', [
                'error' => $e->getMessage(),
                'agent_id' => $agent->id,
                'demande_id' => $demande->id,
                'agent_to_assign_id' => $request->agent_id ?? null
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'assignation de l\'agent.');
        }
    }

    /**
     * Affecter un maître de stage à une demande
     */
    public function affecterMaitreStage(Request $request, DemandeStage $demande)
    {
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Vérifier que l'agent est bien responsable de la structure concernée
            $structure = Structure::where('responsable_id', $agent->id)->first();

            if (!$structure || $demande->structure_id !== $structure->id) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à cette demande.');
            }

            // Journaliser les données reçues
            Log::info('Données reçues pour l\'affectation du maître de stage', [
                'request_all' => $request->all(),
                'demande_id' => $demande->id
            ]);

            // Valider l'ID de l'agent
            $validated = $request->validate([
                'maitre_stage_id' => 'required|exists:agents,id'
            ]);

            Log::info('Données validées', [
                'maitre_stage_id' => $validated['maitre_stage_id']
            ]);

            // Récupérer l'agent à assigner comme maître de stage
            $maitreStage = Agent::with('user')->findOrFail($validated['maitre_stage_id']);

            Log::info('Agent récupéré', [
                'agent_id' => $maitreStage->id,
                'user_id' => $maitreStage->user_id,
                'role_agent' => $maitreStage->role_agent
            ]);

            // Vérifier que l'agent a bien le rôle MS
            if ($maitreStage->role_agent !== 'MS') {
                return redirect()->back()->with('error', 'L\'agent sélectionné n\'est pas un maître de stage (rôle MS).');
            }

            // Vérifier que la demande est acceptée
            if ($demande->statut !== 'Acceptée') {
                return redirect()->back()->with('error', 'La demande doit être acceptée avant d\'affecter un maître de stage.');
            }

            // Récupérer le stage associé à la demande (qui a été créé lors de l'acceptation)
            $stage = \App\Models\Stage::where('demande_stage_id', $demande->id)->first();

            if (!$stage) {
                // Si pour une raison quelconque le stage n'existe pas, le créer
                $stage = \App\Models\Stage::create([
                    'demande_stage_id' => $demande->id,
                    'structure_id' => $demande->structure_id,
                    'date_debut' => $demande->date_debut,
                    'date_fin' => $demande->date_fin,
                    'statut' => 'En cours',
                    'type' => $demande->type
                ]);

                Log::info('Stage créé lors de l\'affectation du maître de stage (normalement déjà créé lors de l\'acceptation)', [
                    'stage_id' => $stage->id,
                    'demande_stage_id' => $demande->id
                ]);
            }

            Log::info('Stage créé ou récupéré', [
                'stage_id' => $stage->id,
                'demande_stage_id' => $demande->id,
                'structure_id' => $demande->structure_id,
                'was_created' => $stage->wasRecentlyCreated
            ]);

            // Créer ou mettre à jour l'affectation du maître de stage
            \App\Models\AffectationMaitreStage::updateOrCreate(
                ['stage_id' => $stage->id],
                [
                    'maitre_stage_id' => $maitreStage->user_id, // Utiliser l'ID de l'utilisateur associé à l'agent
                    'agent_affectant_id' => $agent->id,
                    'date_affectation' => now(),
                    'statut' => 'En cours'
                ]
            );

            // Journaliser les détails de l'affectation pour le débogage
            Log::info('Affectation du maître de stage créée', [
                'stage_id' => $stage->id,
                'maitre_stage_id' => $maitreStage->user_id,
                'agent_affectant_id' => $agent->id
            ]);

            return redirect()->back()->with('success', 'Le maître de stage a été affecté avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affectation du maître de stage', [
                'error' => $e->getMessage(),
                'agent_id' => $agent->id,
                'demande_id' => $demande->id,
                'maitre_stage_id' => $request->maitre_stage_id ?? null
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'affectation du maître de stage: ' . $e->getMessage());
        }
    }
}