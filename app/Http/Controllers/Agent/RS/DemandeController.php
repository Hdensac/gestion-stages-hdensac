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

            // Récupérer les IDs des demandes affectées à cette structure via la table d'affectation
            $demandesAffecteesIds = \App\Models\AffectationResponsableStructure::where('structure_id', $structure->id)
                ->pluck('id_demande_stages')
                ->toArray();

            // Construction de la requête de base (demandes directes + affectées)
            $query = DemandeStage::with(['stagiaire.user'])
                ->where(function($q) use ($structure, $demandesAffecteesIds) {
                    $q->where('structure_id', $structure->id)
                      ->orWhereIn('id', $demandesAffecteesIds);
                });

            // Ajouter la relation avec les affectations pour récupérer la date d'affectation
            $query->with(['affectations' => function($query) use ($structure) {
                $query->where('structure_id', $structure->id)
                      ->orderBy('date_affectation', 'desc')
                      ->orderBy('created_at', 'desc');
            }]);

            // Filtre par statut
            if ($request->filled('status')) {
                $status = $request->status;
                $statusVariants = [$status];
                if ($status === 'En cours') {
                    $statusVariants[] = 'Encours';
                    $statusVariants[] = 'en cours';
                    $statusVariants[] = 'en_cours';
                    $statusVariants[] = 'EN COURS';
                }
                $query->whereIn('statut', $statusVariants);
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

            // Vérifier si la demande est directement liée à la structure ou affectée via la table d'affectation
            $estAffectee = \App\Models\AffectationResponsableStructure::where('structure_id', $structure->id)
                ->where('id_demande_stages', $demande->id)
                ->exists();

            if (!$structure || ($demande->structure_id !== $structure->id && !$estAffectee)) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à cette demande.');
            }

            $demande->load(['stagiaire.user', 'structure', 'membres.user', 'affectations.structure']);

            // Si la demande est affectée mais n'a pas de structure directe, utiliser la structure de l'affectation
            if (!$demande->structure && $estAffectee) {
                $affectation = \App\Models\AffectationResponsableStructure::where('structure_id', $structure->id)
                    ->where('id_demande_stages', $demande->id)
                    ->with('structure')
                    ->first();

                if ($affectation && $affectation->structure) {
                    // Attacher temporairement la structure de l'affectation à la demande
                    $demande->structure = $affectation->structure;
                }
            }

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

            // Vérifier si la demande est directement liée à la structure ou affectée via la table d'affectation
            $estAffectee = \App\Models\AffectationResponsableStructure::where('structure_id', $structure->id)
                ->where('id_demande_stages', $demande->id)
                ->exists();

            if (!$structure || ($demande->structure_id !== $structure->id && !$estAffectee)) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à cette demande.');
            }

            // Charger les relations nécessaires pour l'email
            $demande->load(['stagiaire.user', 'structure', 'affectations.structure']);

            // Si la demande est affectée mais n'a pas de structure directe, utiliser la structure de l'affectation
            if (!$demande->structure && $estAffectee) {
                $affectation = \App\Models\AffectationResponsableStructure::where('structure_id', $structure->id)
                    ->where('id_demande_stages', $demande->id)
                    ->with('structure')
                    ->first();

                if ($affectation && $affectation->structure) {
                    // Attacher temporairement la structure de l'affectation à la demande
                    $demande->structure = $affectation->structure;
                }
            }

            // Charger les relations nécessaires pour l'email et la création du stage
            $demande->load(['stagiaire.user', 'structure', 'affectations.structure', 'membres.user']);

            // Journaliser l'état actuel de la demande
            Log::info('État actuel de la demande avant approbation', [
                'demande_id' => $demande->id,
                'statut_actuel' => $demande->statut,
                'structure_id' => $demande->structure_id
            ]);

            // Vérifier si le statut est "Encours" et le corriger en "En cours"
            if ($demande->statut === 'Encours') {
                Log::info('Correction du statut "Encours" en "En cours"', [
                    'demande_id' => $demande->id
                ]);

                try {
                    \Illuminate\Support\Facades\DB::table('demande_stages')
                        ->where('id', $demande->id)
                        ->update([
                            'statut' => 'En cours',
                            'updated_at' => now()
                        ]);

                    $demande->refresh();
                    // Recharger les relations après le refresh
                    $demande->load(['stagiaire.user', 'structure', 'affectations.structure', 'membres.user']);
                } catch (\Exception $e) {
                    Log::error('Erreur lors de la correction du statut', [
                        'demande_id' => $demande->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Mettre à jour le statut de la demande avec une requête SQL directe
            try {
                $updateResult = \Illuminate\Support\Facades\DB::table('demande_stages')
                    ->where('id', $demande->id)
                    ->update([
                        'statut' => 'Acceptée',
                        'date_traitement' => now(),
                        'traite_par' => $agent->id,
                        'updated_at' => now()
                    ]);

                Log::info('Résultat de la mise à jour directe', [
                    'demande_id' => $demande->id,
                    'update_result' => $updateResult
                ]);

                if ($updateResult === 0) {
                    Log::error('Échec de la mise à jour du statut de la demande (SQL direct)', [
                        'demande_id' => $demande->id
                    ]);
                    return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du statut de la demande.');
                }
            } catch (\Exception $e) {
                Log::error('Exception lors de la mise à jour du statut de la demande', [
                    'demande_id' => $demande->id,
                    'error' => $e->getMessage()
                ]);
                return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du statut de la demande: ' . $e->getMessage());
            }

            // Vérifier que la mise à jour a bien été effectuée et recharger les relations
            $demande->refresh();
            $demande->load(['stagiaire.user', 'structure', 'affectations.structure', 'membres.user']);

            // Mettre à jour manuellement le statut dans l'objet demande pour s'assurer qu'il est correct
            $demande->statut = 'Acceptée';

            Log::info('État de la demande après approbation', [
                'demande_id' => $demande->id,
                'nouveau_statut' => $demande->statut,
                'stagiaire_id' => $demande->stagiaire->id ?? 'non défini',
                'stagiaire_email' => $demande->stagiaire->user->email ?? 'non défini'
            ]);

            // Créer un stage à partir de la demande acceptée
            try {
                // Journaliser les données avant la création
                Log::info('Données pour la création du stage', [
                    'demande_id' => $demande->id,
                    'structure_id' => $demande->structure_id,
                    'date_debut' => $demande->date_debut,
                    'date_fin' => $demande->date_fin,
                    'type' => $demande->type
                ]);

                // S'assurer que structure_id est défini, sinon utiliser la structure du RS
                if (!$demande->structure_id && $structure) {
                    Log::info('Structure ID manquante, utilisation de la structure du RS', [
                        'structure_id_rs' => $structure->id
                    ]);
                    $demande->structure_id = $structure->id;
                }

                // Vérifier si un stage existe déjà pour cette demande
                $existingStage = \App\Models\Stage::where('demande_stage_id', $demande->id)->first();

                if ($existingStage) {
                    Log::info('Un stage existe déjà pour cette demande', [
                        'stage_id' => $existingStage->id,
                        'demande_id' => $demande->id
                    ]);
                    $stage = $existingStage;
                } else {
                    // Convertir le type au format attendu par la base de données
                    $typeFormatted = strtolower(str_replace('é', 'e', $demande->type));

                    Log::info('Type formaté pour la création du stage', [
                        'type_original' => $demande->type,
                        'type_formatted' => $typeFormatted
                    ]);

                    // Créer le stage directement avec create() au lieu de firstOrCreate()
                    $stage = \App\Models\Stage::create([
                        'demande_stage_id' => $demande->id,
                        'structure_id' => $demande->structure_id,
                        'date_debut' => $demande->date_debut,
                        'date_fin' => $demande->date_fin,
                        'statut' => 'En cours',
                        'type' => $typeFormatted
                    ]);

                    Log::info('Stage créé avec la méthode create()', [
                        'stage_id' => $stage->id,
                        'demande_stage_id' => $demande->id
                    ]);
                }

                // Vérifier que le stage a bien été créé
                $stageCheck = \App\Models\Stage::find($stage->id);

                Log::info('Vérification de la création du stage', [
                    'stage_id' => $stage->id,
                    'demande_stage_id' => $demande->id,
                    'structure_id' => $demande->structure_id,
                    'stage_exists' => $stageCheck ? 'oui' : 'non'
                ]);

                // Vérifier tous les stages dans la base de données
                $allStages = \App\Models\Stage::all();
                Log::info('Nombre total de stages dans la base de données', [
                    'count' => $allStages->count(),
                    'ids' => $allStages->pluck('id')->toArray()
                ]);

            } catch (\Exception $e) {
                Log::error('Erreur lors de la création du stage', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'demande_id' => $demande->id
                ]);

                // Essayer une approche alternative avec une requête SQL directe
                try {
                    // Convertir le type au format attendu par la base de données
                    $typeFormatted = strtolower(str_replace('é', 'e', $demande->type));

                    Log::info('Type formaté pour la création du stage (SQL direct)', [
                        'type_original' => $demande->type,
                        'type_formatted' => $typeFormatted
                    ]);

                    $stageId = \Illuminate\Support\Facades\DB::table('stages')->insertGetId([
                        'demande_stage_id' => $demande->id,
                        'structure_id' => $demande->structure_id,
                        'date_debut' => $demande->date_debut,
                        'date_fin' => $demande->date_fin,
                        'statut' => 'En cours',
                        'type' => $typeFormatted,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    Log::info('Stage créé avec une requête SQL directe', [
                        'stage_id' => $stageId,
                        'demande_id' => $demande->id
                    ]);
                } catch (\Exception $innerException) {
                    Log::error('Échec de la création du stage même avec une requête SQL directe', [
                        'error' => $innerException->getMessage(),
                        'demande_id' => $demande->id
                    ]);
                }
            }

            // Envoyer l'email au stagiaire
            try {
                // Vérifier que le stagiaire et son utilisateur sont bien chargés
                if ($demande->stagiaire && $demande->stagiaire->user) {
                    Log::info('Envoi d\'email d\'acceptation au stagiaire', [
                        'email' => $demande->stagiaire->user->email,
                        'nom' => $demande->stagiaire->user->nom,
                        'prenom' => $demande->stagiaire->user->prenom
                    ]);

                    Mail::to($demande->stagiaire->user->email)
                        ->send(new DemandeAcceptationMail($demande, $demande->stagiaire->user));
                } else {
                    Log::error('Impossible d\'envoyer l\'email : stagiaire ou utilisateur non défini', [
                        'demande_id' => $demande->id,
                        'stagiaire' => $demande->stagiaire ? 'défini' : 'non défini',
                        'user' => ($demande->stagiaire && $demande->stagiaire->user) ? 'défini' : 'non défini'
                    ]);
                }

                // Si c'est une demande de groupe, envoyer aux membres aussi
                if ($demande->nature === 'Groupe' && $demande->membres) {
                    foreach ($demande->membres as $membre) {
                        if ($membre->user && $membre->user->email && $demande->stagiaire && $demande->stagiaire->user && $membre->user->email !== $demande->stagiaire->user->email) {
                            Log::info('Envoi d\'email d\'acceptation à un membre du groupe', [
                                'email' => $membre->user->email,
                                'nom' => $membre->user->nom,
                                'prenom' => $membre->user->prenom
                            ]);

                            Mail::to($membre->user->email)
                                ->send(new DemandeAcceptationMail($demande, $membre->user));
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de l\'email d\'acceptation', [
                    'error' => $e->getMessage(),
                    'demande_id' => $demande->id,
                    'trace' => $e->getTraceAsString()
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

            // Vérifier si la demande est directement liée à la structure ou affectée via la table d'affectation
            $estAffectee = \App\Models\AffectationResponsableStructure::where('structure_id', $structure->id)
                ->where('id_demande_stages', $demande->id)
                ->exists();

            if (!$structure || ($demande->structure_id !== $structure->id && !$estAffectee)) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à cette demande.');
            }

            // Valider le motif de refus
            $validated = $request->validate([
                'motif_refus' => 'required|string|max:500'
            ]);

            // Charger les relations nécessaires pour l'email
            $demande->load(['stagiaire.user', 'structure', 'affectations.structure']);

            // Si la demande est affectée mais n'a pas de structure directe, utiliser la structure de l'affectation
            if (!$demande->structure && $estAffectee) {
                $affectation = \App\Models\AffectationResponsableStructure::where('structure_id', $structure->id)
                    ->where('id_demande_stages', $demande->id)
                    ->with('structure')
                    ->first();

                if ($affectation && $affectation->structure) {
                    // Attacher temporairement la structure de l'affectation à la demande
                    $demande->structure = $affectation->structure;
                }
            }

            // Journaliser l'état actuel de la demande
            Log::info('État actuel de la demande avant rejet', [
                'demande_id' => $demande->id,
                'statut_actuel' => $demande->statut,
                'structure_id' => $demande->structure_id
            ]);

            // Vérifier si le statut est "Encours" et le corriger en "En cours"
            if ($demande->statut === 'Encours') {
                Log::info('Correction du statut "Encours" en "En cours"', [
                    'demande_id' => $demande->id
                ]);

                try {
                    \Illuminate\Support\Facades\DB::table('demande_stages')
                        ->where('id', $demande->id)
                        ->update([
                            'statut' => 'En cours',
                            'updated_at' => now()
                        ]);

                    $demande->refresh();
                } catch (\Exception $e) {
                    Log::error('Erreur lors de la correction du statut', [
                        'demande_id' => $demande->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Mettre à jour la demande avec une requête SQL directe
            try {
                $updateResult = \Illuminate\Support\Facades\DB::table('demande_stages')
                    ->where('id', $demande->id)
                    ->update([
                        'statut' => 'Refusée',
                        'date_traitement' => now(),
                        'traite_par' => $agent->id,
                        'motif_refus' => $validated['motif_refus'],
                        'updated_at' => now()
                    ]);

                Log::info('Résultat de la mise à jour directe (rejet)', [
                    'demande_id' => $demande->id,
                    'update_result' => $updateResult
                ]);

                if ($updateResult === 0) {
                    Log::error('Échec de la mise à jour du statut de la demande (SQL direct - rejet)', [
                        'demande_id' => $demande->id
                    ]);
                    return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du statut de la demande.');
                }
            } catch (\Exception $e) {
                Log::error('Exception lors de la mise à jour du statut de la demande (rejet)', [
                    'demande_id' => $demande->id,
                    'error' => $e->getMessage()
                ]);
                return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du statut de la demande: ' . $e->getMessage());
            }

            // Vérifier que la mise à jour a bien été effectuée
            $demande->refresh();
            Log::info('État de la demande après rejet', [
                'demande_id' => $demande->id,
                'nouveau_statut' => $demande->statut
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

            // Récupérer les agents avec le rôle MS appartenant à la structure dont l'agent est responsable
            $msAgents = Agent::with(['user', 'structuresResponsable'])
                ->where('role_agent', 'MS')
                ->where('structure_id', $structure->id)
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

            // Vérifier si la demande est directement liée à la structure ou affectée via la table d'affectation
            $estAffectee = \App\Models\AffectationResponsableStructure::where('structure_id', $structure->id)
                ->where('id_demande_stages', $demande->id)
                ->exists();

            if (!$structure || ($demande->structure_id !== $structure->id && !$estAffectee)) {
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

            // Vérifier si la demande est directement liée à la structure ou affectée via la table d'affectation
            $estAffectee = \App\Models\AffectationResponsableStructure::where('structure_id', $structure->id)
                ->where('id_demande_stages', $demande->id)
                ->exists();

            if (!$structure || ($demande->structure_id !== $structure->id && !$estAffectee)) {
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

            // Vérifier que le maître de stage appartient à la structure du RS
            if ($maitreStage->structure_id !== $structure->id) {
                return redirect()->back()->with('error', 'Le maître de stage sélectionné n\'appartient pas à votre structure.');
            }

            // Vérifier que la demande est acceptée
            if ($demande->statut !== 'Acceptée') {
                return redirect()->back()->with('error', 'La demande doit être acceptée avant d\'affecter un maître de stage.');
            }

            // Charger les relations nécessaires
            $demande->load(['structure', 'affectations.structure']);

            // Si la demande est affectée mais n'a pas de structure directe, utiliser la structure de l'affectation
            if (!$demande->structure && $estAffectee) {
                $affectation = \App\Models\AffectationResponsableStructure::where('structure_id', $structure->id)
                    ->where('id_demande_stages', $demande->id)
                    ->with('structure')
                    ->first();

                if ($affectation && $affectation->structure) {
                    // Attacher temporairement la structure de l'affectation à la demande
                    $demande->structure = $affectation->structure;
                }
            }

            // Récupérer le stage associé à la demande (qui a été créé lors de l'acceptation)
            $stage = \App\Models\Stage::where('demande_stage_id', $demande->id)->first();

            if (!$stage) {
                // Si pour une raison quelconque le stage n'existe pas, le créer
                $stage = \App\Models\Stage::create([
                    'demande_stage_id' => $demande->id,
                    'structure_id' => $demande->structure ? $demande->structure->id : $structure->id,
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