<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ThemeProposeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $stagiaire;
    public $stage;
    public $theme;

    /**
     * Create a new message instance.
     */
    public function __construct($stagiaire, $stage, $theme)
    {
        $this->stagiaire = $stagiaire;
        $this->stage = $stage;
        $this->theme = $theme;
    }

    /**
     * Get the message envelope.
     */
   

    /**
     * Get the message content definition.
     */
   

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('emails.theme_propose')
            ->subject('Notification de thème de stage')
            ->with([
                'stagiaire' => $this->stagiaire,
                'stage' => $this->stage,
                'theme' => $this->theme,
            ]);
    }
}
