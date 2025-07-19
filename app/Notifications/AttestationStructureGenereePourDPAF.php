<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttestationStructureGenereePourDPAF extends Notification
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
        $structureNom = $this->structure ? $this->structure->libelle : 'une structure';
        $stagiaireNom = $this->stage && $this->stage->stagiaire && $this->stage->stagiaire->user 
            ? $this->stage->stagiaire->user->prenom . ' ' . $this->stage->stagiaire->user->nom 
            : 'un stagiaire';
        
        return [
            'title' => 'Nouvelle attestation à valider',
            'message' => 'Une attestation de structure a été générée pour ' . $stagiaireNom . ' (' . $structureNom . ') et est prête pour validation DPAF.',
            'type' => 'attestation',
            'url' => url('/agent/dpaf/attestations'),
            'stage_id' => $this->stage ? $this->stage->id : null,
            'structure_nom' => $structureNom,
            'stagiaire_nom' => $stagiaireNom,
        ];
    }
}
