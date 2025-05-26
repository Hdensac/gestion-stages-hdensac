<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AffectationMaitreStageMail extends Mailable
{
    use Queueable, SerializesModels;

    public $stagiaire;
    public $stage;
    public $maitreStage;
    public $isReaffectation;

    /**
     * Create a new message instance.
     */
    public function __construct($stagiaire, $stage, $maitreStage, $isReaffectation = false)
    {
        $this->stagiaire = $stagiaire;
        $this->stage = $stage;
        $this->maitreStage = $maitreStage;
        $this->isReaffectation = $isReaffectation;
    }

    public function build()
    {
        return $this->view('emails.affectation_maitre_stage')
            ->subject($this->isReaffectation
                ? 'Votre maître de stage a été réaffecté'
                : 'Affectation de votre maître de stage')
            ->with([
                'stagiaire' => $this->stagiaire,
                'stage' => $this->stage,
                'maitreStage' => $this->maitreStage,
                'isReaffectation' => $this->isReaffectation,
            ]);
    }
} 