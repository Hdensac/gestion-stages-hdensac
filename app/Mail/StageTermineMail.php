<?php

namespace App\Mail;

use App\Models\Stage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StageTermineMail extends Mailable
{
    use Queueable, SerializesModels;

    public $stage;

    public function __construct(Stage $stage)
    {
        $this->stage = $stage;
    }

    public function build()
    {
        return $this->subject('Stage terminé - ' . $this->stage->intitule)
                    ->markdown('emails.stage-termine');
    }
} 