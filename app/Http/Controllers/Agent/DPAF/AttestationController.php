<?php

namespace App\Http\Controllers\Agent\DPAF;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AttestationController extends Controller
{
    /**
     * Display a listing of stages eligible for DPAF attestation.
     */
    public function index()
    {
        $stages = Stage::with([
            'stagiaire.user',
            'structure',
            'agentAttestationStructure.user',
            'agentAttestationDpaf.user'
        ])
        ->where('attestation_structure_generee', true)
        ->where('statut', 'termine')
        ->orderBy('attestation_structure_date', 'desc')
        ->paginate(15);

        return Inertia::render('Agent/DPAF/Attestations/Index', [
            'stages' => $stages
        ]);
    }

    /**
     * Generate DPAF attestation for a stage.
     */
    public function generate(Stage $stage)
    {
        try {
            Log::info('Tentative de génération d\'attestation DPAF', [
                'stage_id' => $stage->id,
                'attestation_structure_generee' => $stage->attestation_structure_generee,
                'statut' => $stage->statut,
                'attestation_dpaf_generee' => $stage->attestation_dpaf_generee
            ]);

            // Vérifier que l'attestation structure a été générée
            if (!$stage->canGenerateDpafAttestation()) {
                Log::warning('Stage non éligible pour attestation DPAF', [
                    'stage_id' => $stage->id,
                    'attestation_structure_generee' => $stage->attestation_structure_generee,
                    'statut' => $stage->statut,
                    'attestation_dpaf_generee' => $stage->attestation_dpaf_generee
                ]);
                return back()->with('error', 'Ce stage n\'est pas éligible pour une attestation DPAF.');
            }

            // Récupérer l'agent connecté
            $user = Auth::user();
            $agent = Agent::where('user_id', $user->id)->first();

            if (!$agent) {
                Log::error('Aucun agent trouvé pour l\'utilisateur', ['user_id' => $user->id]);
                return back()->with('error', 'Aucun agent associé à cet utilisateur.');
            }

            // Générer le numéro d'attestation DPAF
            $numeroAttestation = $stage->generateDpafAttestationNumber();

            Log::info('Génération du numéro d\'attestation', [
                'stage_id' => $stage->id,
                'numero_attestation' => $numeroAttestation,
                'agent_id' => $agent->id
            ]);

            // Marquer l'attestation DPAF comme générée
            $stage->update([
                'attestation_dpaf_generee' => true,
                'attestation_dpaf_date' => now(),
                'attestation_dpaf_par' => $agent->id,
                'numero_attestation_dpaf' => $numeroAttestation,
            ]);

            Log::info('Attestation DPAF générée avec succès', [
                'stage_id' => $stage->id,
                'numero_attestation' => $numeroAttestation
            ]);

            return back()->with('success', 'Attestation DPAF générée avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération de l\'attestation DPAF', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Erreur lors de la génération: ' . $e->getMessage());
        }
    }

    /**
     * Display the DPAF attestation (PDF or HTML).
     */
    public function show(Stage $stage)
    {
        try {
            // Vérifier que l'attestation DPAF a été générée
            if (!$stage->attestation_dpaf_generee) {
                return response()->json(['error' => 'L\'attestation DPAF n\'a pas encore été générée pour ce stage.'], 400);
            }

            $stage->load(['stagiaire.user', 'structure', 'agentAttestationDpaf.user']);

            // Vérifier que les données nécessaires existent
            if (!$stage->stagiaire || !$stage->stagiaire->user) {
                return response()->json(['error' => 'Données du stagiaire introuvables pour ce stage.'], 400);
            }

            if (!$stage->structure) {
                return response()->json(['error' => 'Structure introuvable pour ce stage.'], 400);
            }

            $stagiaire = $stage->stagiaire;
            $structure = $stage->structure;
            $agentDpaf = $stage->agentAttestationDpaf;

            return view('attestation-dpaf', [
                'stage' => $stage,
                'stagiaire' => $stagiaire,
                'structure' => $structure,
                'agent_dpaf' => $agentDpaf,
                'numero_attestation' => $stage->numero_attestation_dpaf,
                'date_generation' => $stage->attestation_dpaf_date,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage de l\'attestation DPAF', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Erreur lors de l\'affichage de l\'attestation: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Revoke DPAF attestation (if needed).
     */
    public function revoke(Stage $stage)
    {
        if (!$stage->attestation_dpaf_generee) {
            return back()->with('error', 'Aucune attestation DPAF à révoquer.');
        }

        $stage->update([
            'attestation_dpaf_generee' => false,
            'attestation_dpaf_date' => null,
            'attestation_dpaf_par' => null,
            'numero_attestation_dpaf' => null,
        ]);

        return back()->with('success', 'Attestation DPAF révoquée avec succès.');
    }
}
