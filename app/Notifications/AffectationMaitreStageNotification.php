<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Stage;
use App\Models\User;

class AffectationMaitreStageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $stage;
    public $maitreStage;
    public $isReaffectation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Stage $stage, User $maitreStage, $isReaffectation = false)
    {
        $this->stage = $stage;
        $this->maitreStage = $maitreStage;
        $this->isReaffectation = $isReaffectation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->isReaffectation 
            ? 'Votre maître de stage a été réaffecté'
            : 'Affectation de votre maître de stage';

        $greeting = $this->isReaffectation
            ? "Bonjour {$notifiable->prenom},"
            : "Bonjour {$notifiable->prenom},";

        $message = $this->isReaffectation
            ? "Votre stage a été réaffecté à un nouveau maître de stage."
            : "Un maître de stage a été affecté à votre stage.";

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($message)
            ->line("**Maître de stage :** {$this->maitreStage->prenom} {$this->maitreStage->nom}")
            ->line("**Structure :** {$this->stage->structure->libelle}")
            ->line("**Période :** Du " . date('d/m/Y', strtotime($this->stage->date_debut)) . " au " . date('d/m/Y', strtotime($this->stage->date_fin)))
            ->action('Voir mes stages', route('stagiaire.stages'))
            ->line('Merci de votre confiance !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'affectation_maitre_stage',
            'title' => $this->isReaffectation 
                ? 'Maître de stage réaffecté'
                : 'Maître de stage affecté',
            'message' => $this->isReaffectation
                ? "Votre stage a été réaffecté à {$this->maitreStage->prenom} {$this->maitreStage->nom}"
                : "Un maître de stage ({$this->maitreStage->prenom} {$this->maitreStage->nom}) a été affecté à votre stage",
            'stage_id' => $this->stage->id,
            'maitre_stage_id' => $this->maitreStage->id,
            'structure_id' => $this->stage->structure_id,
            'is_reaffectation' => $this->isReaffectation,
            'action_url' => route('stagiaire.stages'),
        ];
    }
} 