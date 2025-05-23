<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EvaluationNotifieeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $stagiaire;
    public $stage;
    public $evaluation;

    /**
     * Create a new message instance.
     */
    public function __construct($stagiaire, $stage, $evaluation)
    {
        $this->stagiaire = $stagiaire;
        $this->stage = $stage;
        $this->evaluation = $evaluation;
    }

    public function build()
    {
        return $this->view('emails.evaluation_notifiee')
            ->subject('Notification de votre évaluation de stage')
            ->with([
                'stagiaire' => $this->stagiaire,
                'stage' => $this->stage,
                'evaluation' => $this->evaluation,
            ]);
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
