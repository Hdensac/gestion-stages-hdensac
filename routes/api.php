<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\StagiaireMessageController;
use App\Http\Controllers\Api\AvailabilityController;
use App\Http\Controllers\Agent\NotificationController as AgentNotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



// Routes pour l'envoi d'emails
// Désactivation temporaire de l'authentification pour faciliter les tests
// Route::middleware('auth:sanctum')->group(function () {
    // Envoi d'email de confirmation pour une demande de stage
    Route::post('/emails/demande-confirmation', [EmailController::class, 'sendDemandeConfirmation']);
    Route::get('/emails/check-config', [EmailController::class, 'checkEmailConfig']);
// });

// Route pour l'envoi de messages aux stagiaires (protégée par authentification)
Route::middleware('auth')->post('stagiaire/send-message', [StagiaireMessageController::class, 'sendMessage']);

// Routes pour la vérification de disponibilité (protégées par authentification)
Route::middleware('auth')->group(function () {
    // Vérifier la disponibilité du stagiaire connecté pour une période
    Route::post('/availability/check-period', [AvailabilityController::class, 'checkStagiairePeriod']);

    // Récupérer les membres disponibles pour une période
    Route::post('/availability/available-members', [AvailabilityController::class, 'getAvailableMembers']);

    // Vérifier la disponibilité d'un membre spécifique
    Route::post('/availability/check-member', [AvailabilityController::class, 'checkMemberAvailability']);

    // Vérifier la disponibilité de plusieurs membres
    Route::post('/availability/check-multiple-members', [AvailabilityController::class, 'checkMultipleMembersAvailability']);
});

// Notifications pour les agents (dont MS)
Route::middleware('auth')->group(function () {
    Route::get('/agent/notifications', [AgentNotificationController::class, 'list']);
    Route::post('/agent/notifications/mark-all-read', [AgentNotificationController::class, 'markAllAsRead']);
});

// Route pour le test d'envoi d'email
Route::get('/test-email', function() {
    $demande = \App\Models\DemandeStage::first();
    $user = \App\Models\User::first();

    if (!$demande || !$user) {
        return response()->json([
            'success' => false,
            'message' => 'Impossible de trouver une demande ou un utilisateur pour le test'
        ], 404);
    }

    try {
        \Illuminate\Support\Facades\Mail::to($user->email)
            ->send(new \App\Mail\DemandeConfirmationMail($demande, $user));

        return response()->json([
            'success' => true,
            'message' => 'Email de test envoyé à ' . $user->email
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'envoi: ' . $e->getMessage()
        ], 500);
    }
});

// Route pour diagnostiquer les problèmes de file d'attente
Route::get('/diagnose-queue', function() {
    // Vérifier la configuration de base
    $queueConnection = config('queue.default');
    $queueDriver = config("queue.connections.{$queueConnection}.driver");

    // Vérifier si le dossier de stockage est accessible en écriture (pour le driver 'file')
    $storageWritable = is_writable(storage_path());

    return response()->json([
        'success' => true,
        'queue_diagnostics' => [
            'default_connection' => $queueConnection,
            'driver' => $queueDriver,
            'storage_writable' => $storageWritable,
            'mail_config' => [
                'driver' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'from' => config('mail.from.address'),
                'timeout' => config('mail.mailers.smtp.timeout') ?? '30 (default)',
            ],
            'php_config' => [
                'max_execution_time' => ini_get('max_execution_time'),
                'memory_limit' => ini_get('memory_limit'),
            ],
        ]
    ]);
});