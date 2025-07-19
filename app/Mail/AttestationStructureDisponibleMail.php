<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AttestationStructureDisponibleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $stage;
    public $structure;
    public $stagiaire;

    /**
     * Create a new message instance.
     */
    public function __construct($stage, $structure, $stagiaire)
    {
        $this->stage = $stage;
        $this->structure = $structure;
        $this->stagiaire = $stagiaire;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $structureNom = $this->structure ? $this->structure->libelle : 'votre structure d\'accueil';
        
        return new Envelope(
            subject: '🎓 Votre attestation   d\'effectivité de stage est disponible - ' . $structureNom,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.attestation-structure-disponible',
            with: [
                'stage' => $this->stage,
                'structure' => $this->structure,
                'stagiaire' => $this->stagiaire,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
