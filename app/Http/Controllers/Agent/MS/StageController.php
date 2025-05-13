<?php

namespace App\Http\Controllers\Agent\MS;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\AffectationMaitreStage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class StageController extends Controller
{
    /**
     * Vérification du rôle avant chaque action
     */
    private function checkMSRole()
    {
        $user = Auth::user();
        if (!$user || !$user->agent || $user->agent->role_agent !== 'MS') {
            abort(403, 'Accès réservé aux Maîtres de Stage.');
        }
    }

    /**
     * Afficher la liste des stages du maître de stage
     */
    public function index()
    {
        $this->checkMSRole();
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Récupérer les stages affectés à ce maître de stage
            $affectations = AffectationMaitreStage::where('maitre_stage_id', $user->id)
                ->with([
                    'stage.demandeStage.stagiaire.user',
                    'stage.structure',
                    'stage.themeStage'
                ])
                ->get();

            // Extraire les stages des affectations
            $stages = $affectations->map(function ($affectation) {
                return $affectation->stage;
            })->filter(); // Filtrer les valeurs null

            return Inertia::render('Agent/MS/Stages/Index', [
                'stages' => $stages
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des stages', [
                'error' => $e->getMessage(),
                'agent_id' => $agent->id
            ]);

            return Inertia::render('Agent/MS/Stages/Index', [
                'stages' => [],
                'error' => 'Une erreur est survenue lors du chargement des stages.'
            ]);
        }
    }

    /**
     * Afficher les détails d'un stage
     */
    public function show(Request $request, Stage $stage)
    {
        $this->checkMSRole();
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Vérifier que l'utilisateur est bien le maître de stage assigné à ce stage
            $affectation = AffectationMaitreStage::where('stage_id', $stage->id)
                ->where('maitre_stage_id', $user->id)
                ->first();

            if (!$affectation) {
                return redirect()->route('agent.ms.stages')->with('error', 'Vous n\'êtes pas autorisé à accéder à ce stage.');
            }

            // Charger les relations nécessaires
            $stage->load([
                'demandeStage.stagiaire.user',
                'structure',
                'themeStage'
            ]);

            return Inertia::render('Agent/MS/Stages/Show', [
                'stage' => $stage,
                'openContact' => $request->boolean('openContact'),
                'success' => session('success'),
                'error' => session('error')
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des détails du stage', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'agent_id' => $agent->id
            ]);

            return redirect()->route('agent.ms.stages')->with('error', 'Une erreur est survenue lors du chargement des détails du stage.');
        }
    }

    /**
     * Mettre à jour le statut d'un stage
     */
    public function updateStatus(Request $request, Stage $stage)
    {
        $this->checkMSRole();
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Vérifier que l'utilisateur est bien le maître de stage assigné à ce stage
            $affectation = AffectationMaitreStage::where('stage_id', $stage->id)
                ->where('maitre_stage_id', $user->id)
                ->first();

            if (!$affectation) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à modifier ce stage.');
            }

            // Valider le statut
            $validated = $request->validate([
                'statut' => 'required|in:En cours,Terminé,En attente'
            ]);

            // Mettre à jour le statut du stage
            $stage->update([
                'statut' => $validated['statut']
            ]);

            Log::info('Statut du stage mis à jour', [
                'stage_id' => $stage->id,
                'ancien_statut' => $stage->getOriginal('statut'),
                'nouveau_statut' => $validated['statut'],
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('success', 'Le statut du stage a été mis à jour avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du statut du stage', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du statut du stage.');
        }
    }

    /**
     * Valider le thème d'un stage
     */
    public function validerTheme(Stage $stage)
    {
        $this->checkMSRole();
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Vérifier que l'utilisateur est bien le maître de stage assigné à ce stage
            $affectation = AffectationMaitreStage::where('stage_id', $stage->id)
                ->where('maitre_stage_id', $user->id)
                ->first();

            if (!$affectation) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à modifier ce stage.');
            }

            // Vérifier que le stage a un thème
            if (!$stage->themeStage) {
                return redirect()->back()->with('error', 'Ce stage n\'a pas de thème à valider.');
            }

            // Mettre à jour l'état du thème
            $stage->themeStage->update([
                'etat' => 'Validé'
            ]);

            Log::info('Thème du stage validé', [
                'stage_id' => $stage->id,
                'theme_id' => $stage->themeStage->id,
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('success', 'Le thème du stage a été validé avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la validation du thème du stage', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la validation du thème du stage.');
        }
    }

    /**
     * Refuser le thème d'un stage
     */
    public function refuserTheme(Request $request, Stage $stage)
    {
        $this->checkMSRole();
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Vérifier que l'utilisateur est bien le maître de stage assigné à ce stage
            $affectation = AffectationMaitreStage::where('stage_id', $stage->id)
                ->where('maitre_stage_id', $user->id)
                ->first();

            if (!$affectation) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à modifier ce stage.');
            }

            // Vérifier que le stage a un thème
            if (!$stage->themeStage) {
                return redirect()->back()->with('error', 'Ce stage n\'a pas de thème à refuser.');
            }

            // Valider le motif de refus
            $validated = $request->validate([
                'motif_refus' => 'required|string|max:500'
            ]);

            // Mettre à jour l'état du thème
            $stage->themeStage->update([
                'etat' => 'Refusé',
                'motif_refus' => $validated['motif_refus']
            ]);

            Log::info('Thème du stage refusé', [
                'stage_id' => $stage->id,
                'theme_id' => $stage->themeStage->id,
                'motif_refus' => $validated['motif_refus'],
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('success', 'Le thème du stage a été refusé avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors du refus du thème du stage', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors du refus du thème du stage.');
        }
    }

    /**
     * Noter un stage
     */
    public function noter(Request $request, Stage $stage)
    {
        $this->checkMSRole();
        $user = Auth::user();
        $agent = $user->agent;

        try {
            // Vérifier que l'utilisateur est bien le maître de stage assigné à ce stage
            $affectation = AffectationMaitreStage::where('stage_id', $stage->id)
                ->where('maitre_stage_id', $user->id)
                ->first();

            if (!$affectation) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à noter ce stage.');
            }

            // Vérifier que le stage est terminé
            if ($stage->statut !== 'Terminé') {
                return redirect()->back()->with('error', 'Vous ne pouvez noter que les stages terminés.');
            }

            // Valider la note
            $validated = $request->validate([
                'note' => 'required|numeric|min:0|max:20',
                'commentaire' => 'nullable|string|max:1000'
            ]);

            // Mettre à jour la note du stage
            $stage->update([
                'note' => $validated['note'],
                'commentaire_evaluation' => $validated['commentaire']
            ]);

            Log::info('Stage noté', [
                'stage_id' => $stage->id,
                'note' => $validated['note'],
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('success', 'Le stage a été noté avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la notation du stage', [
                'error' => $e->getMessage(),
                'stage_id' => $stage->id,
                'agent_id' => $agent->id
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la notation du stage.');
        }
    }
}
