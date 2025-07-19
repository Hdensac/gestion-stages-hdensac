<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class AttestationStructureDisponible extends Notification
{
    use Queueable;

    public $stage;
    public $structure;

    /**
     * Create a new notification instance.
     */
    public function __construct($stage, $structure = null)
    {
        $this->stage = $stage;
        $this->structure = $structure ?? $stage->structure;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $structureNom = $this->structure ? $this->structure->libelle : 'votre structure d\'accueil';

        return [
            'title' => 'Attestation d\'effectivité de stage disponible',
            'message' => 'Votre attestation d\'effectivité de  stage pour ' . $structureNom . ' est maintenant disponible.',
            'type' => 'attestation',
            'url' => url('/stagiaire/dashboard'),
            'stage_id' => $this->stage ? $this->stage->id : null,
            'structure_nom' => $structureNom,
        ];
    }
}
