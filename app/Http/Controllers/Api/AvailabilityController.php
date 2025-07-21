<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DemandeStage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AvailabilityController extends Controller
{
    /**
     * Vérifie si le stagiaire connecté peut soumettre une demande pour une période donnée
     */
    public function checkStagiairePeriod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after:date_debut',
            'demande_id' => 'nullable|integer|exists:demande_stages,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        
        if (!$user->isStagiaire() || !$user->stagiaire) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non autorisé'
            ], 403);
        }

        $conflict = DemandeStage::checkPeriodConflict(
            $user->stagiaire->id_stagiaire,
            $request->date_debut,
            $request->date_fin,
            $request->demande_id
        );

        if ($conflict['hasConflict']) {
            $message = $this->generateConflictMessage($conflict);
            
            return response()->json([
                'success' => false,
                'available' => false,
                'message' => $message,
                'conflict' => $conflict
            ]);
        }

        return response()->json([
            'success' => true,
            'available' => true,
            'message' => 'Période disponible'
        ]);
    }

    /**
     * Récupère la liste des stagiaires disponibles pour une période donnée
     */
    public function getAvailableMembers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        
        if (!$user->isStagiaire()) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non autorisé'
            ], 403);
        }

        // Récupérer les stagiaires disponibles (exclure l'utilisateur connecté)
        $availableMembers = User::getAvailableStagiaires(
            $request->date_debut,
            $request->date_fin,
            $user->id
        );

        // Formater les données pour le frontend
        $formattedMembers = $availableMembers->map(function ($member) {
            return [
                'id' => $member->id,
                'name' => $member->name,
                'prenom' => $member->prenom,
                'email' => $member->email,
                'niveau_etude' => $member->stagiaire->niveau_etude ?? null,
                'universite' => $member->stagiaire->universite ?? null,
                'filiere' => $member->stagiaire->filiere ?? null,
            ];
        });

        return response()->json([
            'success' => true,
            'members' => $formattedMembers,
            'total' => $formattedMembers->count()
        ]);
    }

    /**
     * Vérifie la disponibilité d'un membre spécifique pour une période donnée
     */
    public function checkMemberAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $member = User::find($request->user_id);
        
        if (!$member || !$member->isStagiaire()) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé ou non stagiaire'
            ], 404);
        }

        $availability = $member->checkStagiaireAvailability(
            $request->date_debut,
            $request->date_fin
        );

        return response()->json([
            'success' => true,
            'user_id' => $member->id,
            'name' => $member->name,
            'available' => $availability['available'],
            'reason' => $availability['reason'],
            'conflict_data' => $availability['conflictData']
        ]);
    }

    /**
     * Vérifie la disponibilité de plusieurs membres en une seule requête
     */
    public function checkMultipleMembersAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'integer|exists:users,id',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $results = [];
        $allAvailable = true;

        foreach ($request->user_ids as $userId) {
            $member = User::find($userId);
            
            if (!$member || !$member->isStagiaire()) {
                $results[] = [
                    'user_id' => $userId,
                    'available' => false,
                    'reason' => 'Utilisateur non trouvé ou non stagiaire'
                ];
                $allAvailable = false;
                continue;
            }

            $availability = $member->checkStagiaireAvailability(
                $request->date_debut,
                $request->date_fin
            );

            $results[] = [
                'user_id' => $userId,
                'name' => $member->name,
                'available' => $availability['available'],
                'reason' => $availability['reason'],
                'conflict_data' => $availability['conflictData']
            ];

            if (!$availability['available']) {
                $allAvailable = false;
            }
        }

        return response()->json([
            'success' => true,
            'all_available' => $allAvailable,
            'results' => $results
        ]);
    }

    /**
     * Génère un message d'erreur lisible pour les conflits
     */
    private function generateConflictMessage($conflict)
    {
        $data = $conflict['conflictData'];
        
        if ($conflict['conflictType'] === 'demande') {
            return "Vous avez déjà une demande {$data['statut']} (Code: {$data['code_suivi']}) " .
                   "pour la période du {$data['date_debut']} au {$data['date_fin']} " .
                   "dans la structure \"{$data['structure']}\". " .
                   "Veuillez modifier les dates ou attendre le traitement de votre demande actuelle.";
        } else {
            return "Vous avez déjà un stage {$data['statut']} " .
                   "pour la période du {$data['date_debut']} au {$data['date_fin']} " .
                   "dans la structure \"{$data['structure']}\". " .
                   "Veuillez modifier les dates ou attendre la fin de votre stage actuel.";
        }
    }
}
