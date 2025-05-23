<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'stage_id',
        'agent_id',
        'ponctualite',
        'motivation',
        'capacite_apprendre',
        'qualite_travail',
        'rapidite_execution',
        'jugement',
        'esprit_motivation',
        'esprit_collaboration',
        'sens_responsabilite',
        'communication',
        'note_totale',
        'commentaire_general',
        'date_evaluation',
    ];

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
