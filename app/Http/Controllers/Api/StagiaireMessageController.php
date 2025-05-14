<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\User;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\StagiaireMessageMail;

class StagiaireMessageController extends Controller
{
    /**
     * Envoie un email à un stagiaire
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'stage_id' => 'required|exists:stages,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        try {
            // Récupérer le stage
            $stage = Stage::findOrFail($request->stage_id);
            
            // Vérifier que l'utilisateur connecté est bien le maître de stage assigné à ce stage
            $user = Auth::user();
            $agent = $user->agent;
            
            // Récupérer l'email du stagiaire
            $stagiaireEmail = null;
            
            // Essayer de récupérer l'email du stagiaire depuis les différentes relations possibles
            if ($stage->stagiaire_info && $stage->stagiaire_info->email) {
                $stagiaireEmail = $stage->stagiaire_info->email;
            } elseif ($stage->demandeStage && $stage->demandeStage->stagiaire && $stage->demandeStage->stagiaire->user) {
                $stagiaireEmail = $stage->demandeStage->stagiaire->user->email;
            }
            
            if (!$stagiaireEmail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de trouver l\'email du stagiaire.'
                ], 404);
            }
            
            // Créer les données pour l'email
            $emailData = [
                'subject' => $request->subject,
                'message' => $request->message,
                'stage' => $stage,
                'agent' => $agent
            ];
            
            // Envoyer l'email au stagiaire
            Mail::to($stagiaireEmail)
                ->send(new StagiaireMessageMail($emailData));
            
            Log::info('Email envoyé au stagiaire', [
                'stage_id' => $stage->id,
                'agent_id' => $agent->id,
                'stagiaire_email' => $stagiaireEmail
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Message envoyé avec succès',
                'stagiaire_email' => $stagiaireEmail
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi du message au stagiaire', [
                'error' => $e->getMessage(),
                'stage_id' => $request->stage_id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'envoi du message: ' . $e->getMessage()
            ], 500);
        }
    }
}
