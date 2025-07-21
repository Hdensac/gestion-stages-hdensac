<?php

namespace App\Http\Middleware;

use App\Models\DemandeStage;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidateStageRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier seulement pour les requêtes POST de création de demande de stage
        if ($request->isMethod('POST') && $request->routeIs('demande.store')) {
            $user = Auth::user();

            if (!$user || !$user->isStagiaire() || !$user->stagiaire) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non autorisé'
                ], 403);
            }

            $dateDebut = $request->input('date_debut');
            $dateFin = $request->input('date_fin');

            if ($dateDebut && $dateFin) {
                // Vérifier les conflits de période pour le stagiaire principal
                $conflict = DemandeStage::checkPeriodConflict(
                    $user->stagiaire->id_stagiaire,
                    $dateDebut,
                    $dateFin
                );

                if ($conflict['hasConflict']) {
                    $message = $this->generateConflictMessage($conflict);

                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'conflict' => $conflict
                    ], 422);
                }

                // Vérifier les conflits pour les membres du groupe si c'est un stage de groupe
                if ($request->input('nature') === 'Groupe') {
                    $membres = $request->input('membres', []);

                    foreach ($membres as $membreId) {
                        $membre = \App\Models\User::find($membreId);

                        if (!$membre || !$membre->isStagiaire() || !$membre->stagiaire) {
                            continue;
                        }

                        $memberConflict = DemandeStage::checkPeriodConflict(
                            $membre->stagiaire->id_stagiaire,
                            $dateDebut,
                            $dateFin
                        );

                        if ($memberConflict['hasConflict']) {
                            $memberName = $membre->name . ' ' . $membre->prenom;
                            $message = "Le membre $memberName n'est pas disponible pour cette période : " .
                                     $this->generateConflictMessage($memberConflict);

                            return response()->json([
                                'success' => false,
                                'message' => $message,
                                'conflict' => $memberConflict,
                                'member' => $memberName
                            ], 422);
                        }
                    }
                }
            }
        }

        return $next($request);
    }

    /**
     * Génère un message d'erreur lisible pour les conflits
     */
    private function generateConflictMessage($conflict)
    {
        $data = $conflict['conflictData'];

        if ($conflict['conflictType'] === 'demande') {
            return "Demande {$data['statut']} (Code: {$data['code_suivi']}) " .
                   "du {$data['date_debut']} au {$data['date_fin']} " .
                   "dans \"{$data['structure']}\".";
        } else {
            return "Stage {$data['statut']} " .
                   "du {$data['date_debut']} au {$data['date_fin']} " .
                   "dans \"{$data['structure']}\".";
        }
    }
}
