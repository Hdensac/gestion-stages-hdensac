<?php

require_once 'vendor/autoload.php';

// Charger Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Stage;
use App\Notifications\AffectationMaitreStageNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

echo "=== Test des notifications ===\n";

// 1. Vérifier si on a des utilisateurs
$users = User::all();
echo "Nombre d'utilisateurs: " . $users->count() . "\n";

if ($users->count() > 0) {
    $user = $users->first();
    echo "Premier utilisateur: " . $user->email . "\n";
    
    // 2. Vérifier si on a des stages
    $stages = Stage::all();
    echo "Nombre de stages: " . $stages->count() . "\n";
    
    if ($stages->count() > 0) {
        $stage = $stages->first();
        echo "Premier stage ID: " . $stage->id . "\n";
        
        // 3. Tester l'envoi d'une notification
        try {
            echo "Test d'envoi de notification...\n";
            
            // Créer une notification de test
            $notification = new AffectationMaitreStageNotification($stage, $user);
            
            // Envoyer la notification
            $user->notify($notification);
            
            echo "✅ Notification envoyée avec succès!\n";
            
            // Vérifier si la notification est en base
            $notifications = $user->notifications()->get();
            echo "Nombre de notifications en base: " . $notifications->count() . "\n";
            
        } catch (Exception $e) {
            echo "❌ Erreur lors de l'envoi de notification: " . $e->getMessage() . "\n";
            echo "Stack trace: " . $e->getTraceAsString() . "\n";
        }
        
        // 4. Tester l'envoi d'email
        try {
            echo "Test d'envoi d'email...\n";
            
            Mail::to($user->email)->send(new \App\Mail\AffectationMaitreStageMail($user, $stage, $user));
            
            echo "✅ Email envoyé avec succès!\n";
            
        } catch (Exception $e) {
            echo "❌ Erreur lors de l'envoi d'email: " . $e->getMessage() . "\n";
            echo "Stack trace: " . $e->getTraceAsString() . "\n";
        }
        
    } else {
        echo "❌ Aucun stage trouvé en base\n";
    }
} else {
    echo "❌ Aucun utilisateur trouvé en base\n";
}

echo "=== Fin du test ===\n"; 