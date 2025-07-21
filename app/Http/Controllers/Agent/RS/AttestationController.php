<?php

namespace App\Http\Controllers\Agent\RS;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\Agent;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class AttestationController extends Controller
{
    /**
     * Display a listing of stages for attestation management.
     */
    public function index()
    {
        // Récupérer l'agent connecté et sa structure
        $user = Auth::user();
        $agent = Agent::where('user_id', $user->id)->first();

        if (!$agent) {
            return back()->with('error', 'Aucun agent associé à cet utilisateur.');
        }

        $structure = Structure::where('responsable_id', $agent->id)->first();

        if (!$structure) {
            return back()->with('error', 'Aucune structure assignée à cet agent.');
        }

        // Récupérer tous les stages de la structure (y compris les sous-structures)
        $structureIds = [$structure->id];

        // Ajouter les sous-structures
        $sousStructures = $structure->sousStructures()->pluck('id')->toArray();
        $structureIds = array_merge($structureIds, $sousStructures);

        $stages = Stage::with([
            'stagiaire.user',
            'structure',
            'agentAttestationStructure.user',
            'agentAttestationDpaf.user'
        ])
        ->whereIn('structure_id', $structureIds)
        ->where('statut', 'termine')
        ->orderBy('date_fin', 'desc')
        ->paginate(15);

        return Inertia::render('Agent/RS/Attestations/Index', [
            'stages' => $stages,
            'structure' => $structure
        ]);
    }

    /**
     * Generate structure attestation for a stage.
     */
    public function generate(Stage $stage)
    {
        // LOG DE DÉBOGAGE - MÉTHODE APPELÉE
        Log::info('=== MÉTHODE GENERATE APPELÉE ===', [
            'stage_id' => $stage->id,
            'timestamp' => now(),
            'user_id' => Auth::id()
        ]);

        // Vérifier que l'agent a le droit de générer l'attestation pour ce stage
        $user = Auth::user();
        $agent = Agent::where('user_id', $user->id)->first();

        if (!$agent) {
            return back()->with('error', 'Aucun agent associé à cet utilisateur.');
        }

        $structure = Structure::where('responsable_id', $agent->id)->first();

        if (!$structure) {
            return back()->with('error', 'Aucune structure assignée à cet agent.');
        }
        
        if ($stage->structure_id !== $structure->id && 
            !$structure->sousStructures()->where('id', $stage->structure_id)->exists()) {
            return back()->with('error', 'Vous n\'avez pas l\'autorisation de générer cette attestation.');
        }

        // Vérifier que le stage est terminé
        if ($stage->statut !== 'termine') {
            return back()->with('error', 'Le stage doit être terminé pour générer une attestation.');
        }

        // Marquer l'attestation structure comme générée
        $stage->update([
            'attestation_structure_generee' => true,
            'attestation_structure_date' => now(),
            'attestation_structure_par' => $agent->id,
        ]);

        // Notifier le stagiaire (email + notification in-app)
        if ($stage->stagiaire && $stage->stagiaire->user) {
            try {
                // Envoyer l'email
                Mail::to($stage->stagiaire->user->email)
                    ->send(new \App\Mail\AttestationStructureDisponibleMail($stage, $stage->structure, $stage->stagiaire));

                // Envoyer la notification in-app
                $stage->stagiaire->user->notify(new \App\Notifications\AttestationStructureDisponible($stage, $stage->structure));
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de la notification d\'attestation', [
                    'error' => $e->getMessage(),
                    'stage_id' => $stage->id,
                    'stagiaire_id' => $stage->stagiaire->id_stagiaire
                ]);
            }
        }

        // Notifier les agents DPAF qu'une nouvelle attestation est prête pour validation
        try {
            Log::info('Début de l\'envoi de notification DPAF', ['stage_id' => $stage->id]);

            $dpafStructure = Structure::where('sigle', 'DPAF')->first();
            Log::info('Structure DPAF recherchée', [
                'found' => $dpafStructure ? true : false,
                'structure_id' => $dpafStructure ? $dpafStructure->id : null
            ]);

            if ($dpafStructure && $dpafStructure->responsable && $dpafStructure->responsable->user) {
                Log::info('Responsable DPAF trouvé, envoi de la notification', [
                    'responsable_id' => $dpafStructure->responsable->id,
                    'user_id' => $dpafStructure->responsable->user->id,
                    'user_email' => $dpafStructure->responsable->user->email
                ]);

                $dpafStructure->responsable->user->notify(
                    new \App\Notifications\AttestationStructureGenereePourDPAF($stage, $stage->structure)
                );

                Log::info('Notification DPAF envoyée avec succès', ['stage_id' => $stage->id]);
            } else {
                Log::warning('Impossible d\'envoyer la notification DPAF', [
                    'dpaf_structure_exists' => $dpafStructure ? true : false,
                    'has_responsable' => $dpafStructure && $dpafStructure->responsable ? true : false,
                    'has_user' => $dpafStructure && $dpafStructure->responsable && $dpafStructure->responsable->user ? true : false
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la notification DPAF', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'trace' => $e->getTraceAsString()
            ]);
        }

        return back()->with('success', 'Attestation de structure générée avec succès.');
    }

    /**
     * Display the structure attestation (PDF or HTML).
     */
    public function show(Stage $stage)
    {
        // Vérifier les permissions
        $user = Auth::user();
        $agent = Agent::where('user_id', $user->id)->first();

        if (!$agent) {
            return back()->with('error', 'Aucun agent associé à cet utilisateur.');
        }

        $structure = Structure::where('responsable_id', $agent->id)->first();

        if (!$structure) {
            return back()->with('error', 'Aucune structure assignée à cet agent.');
        }
        
        if ($stage->structure_id !== $structure->id && 
            !$structure->sousStructures()->where('id', $stage->structure_id)->exists()) {
            return back()->with('error', 'Vous n\'avez pas l\'autorisation de voir cette attestation.');
        }

        $stage->load(['stagiaire.user', 'structure.responsable.user', 'demandeStage.stagiaire.user']);

        // Vérifier que le stagiaire et ses données existent
        if (!$stage->stagiaire) {
            return back()->with('error', 'Aucun stagiaire associé à ce stage (ID: ' . $stage->id . ').');
        }

        if (!$stage->stagiaire->user) {
            return back()->with('error', 'Aucun utilisateur associé au stagiaire (ID: ' . $stage->stagiaire->id_stagiaire . ').');
        }

        // Marquer l'attestation structure comme générée si ce n'est pas déjà fait
        if (!$stage->attestation_structure_generee) {
            $stage->update([
                'attestation_structure_generee' => true,
                'attestation_structure_date' => now(),
                'attestation_structure_par' => $agent->id,
            ]);

            // Notifier le stagiaire (email + notification in-app)
            if ($stage->stagiaire && $stage->stagiaire->user) {
                try {
                    // Envoyer l'email
                    Mail::to($stage->stagiaire->user->email)
                        ->send(new \App\Mail\AttestationStructureDisponibleMail($stage, $stage->structure, $stage->stagiaire));

                    // Envoyer la notification in-app
                    $stage->stagiaire->user->notify(new \App\Notifications\AttestationStructureDisponible($stage, $stage->structure));
                } catch (\Exception $e) {
                    Log::error('Erreur lors de l\'envoi de la notification d\'attestation', [
                        'error' => $e->getMessage(),
                        'stage_id' => $stage->id,
                        'stagiaire_id' => $stage->stagiaire->id_stagiaire
                    ]);
                }
            }

            // Notifier les agents DPAF qu'une nouvelle attestation est prête pour validation
            try {
                $dpafStructure = Structure::where('sigle', 'DPAF')->first();
                if ($dpafStructure && $dpafStructure->responsable && $dpafStructure->responsable->user) {
                    $dpafStructure->responsable->user->notify(
                        new \App\Notifications\AttestationStructureGenereePourDPAF($stage, $stage->structure)
                    );
                }
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de la notification DPAF', [
                    'error' => $e->getMessage(),
                    'stage_id' => $stage->id
                ]);
            }
        }

        $demande = $stage->demandeStage;
        $stagiairePrincipal = $demande && $demande->stagiaire ? $demande->stagiaire : $stage->stagiaire;
        $stageStructure = $stage->structure;
        $responsable = $stageStructure && $stageStructure->responsable ? $stageStructure->responsable : null;
        $nom_rs = ($responsable && $responsable->user) ? ($responsable->user->nom . ' ' . $responsable->user->prenom) : '';
        $libelle_structure = $stageStructure ? $stageStructure->libelle : '';
        $sigle_structure = $stageStructure ? $stageStructure->sigle : '';
        
        return view('attestation', [
            'stage' => $stage,
            'stagiaire' => $stage->stagiaire,
            'structure' => $stageStructure,
            'stagiaire_principal' => $stagiairePrincipal,
            'nom_rs' => $nom_rs,
            'libelle_structure' => $libelle_structure,
            'sigle_structure' => $sigle_structure,
        ]);
    }

    /**
     * Revoke structure attestation (if needed).
     */
    public function revoke(Stage $stage)
    {
        // Vérifier les permissions
        $user = Auth::user();
        $agent = Agent::where('user_id', $user->id)->first();

        if (!$agent) {
            return back()->with('error', 'Aucun agent associé à cet utilisateur.');
        }

        $structure = Structure::where('responsable_id', $agent->id)->first();

        if (!$structure) {
            return back()->with('error', 'Aucune structure assignée à cet agent.');
        }
        
        if ($stage->structure_id !== $structure->id && 
            !$structure->sousStructures()->where('id', $stage->structure_id)->exists()) {
            return back()->with('error', 'Vous n\'avez pas l\'autorisation de révoquer cette attestation.');
        }

        if (!$stage->attestation_structure_generee) {
            return back()->with('error', 'Aucune attestation de structure à révoquer.');
        }

        // Ne pas permettre la révocation si l'attestation DPAF a déjà été générée
        if ($stage->attestation_dpaf_generee) {
            return back()->with('error', 'Impossible de révoquer l\'attestation : l\'attestation DPAF a déjà été générée.');
        }

        $stage->update([
            'attestation_structure_generee' => false,
            'attestation_structure_date' => null,
            'attestation_structure_par' => null,
        ]);

        return back()->with('success', 'Attestation de structure révoquée avec succès.');
    }
}
