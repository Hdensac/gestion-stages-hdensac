<?php

namespace App\Http\Controllers\Agent\MS;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\AffectationMaitreStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StageController extends Controller
{
    /**
     * Mettre à jour le statut d'un stage
     */
    public function updateStatus(Request $request, Stage $stage)
    {
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
}
