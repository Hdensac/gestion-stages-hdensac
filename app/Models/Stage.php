<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_stage_id',
        'structure_id',
        'theme_stage_id',
        'date_debut',
        'date_fin',
        'statut',
        'documents_stage',
        'note',
        'type',
        'stagiaire_id',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    /**
     * Get the demande de stage that created this stage.
     */
    public function demandeStage(): BelongsTo
    {
        return $this->belongsTo(DemandeStage::class);
    }

    /**
     * Get the structure where this stage takes place.
     */
    public function structure(): BelongsTo
    {
        return $this->belongsTo(Structure::class);
    }

    /**
     * Get the stagiaire associated with this stage.
     */
    public function stagiaire(): BelongsTo
    {
        return $this->belongsTo(Stagiaire::class, 'stagiaire_id', 'id_stagiaire');
    }

    /**
     * Get the theme of this stage.
     */
    public function themeStage(): BelongsTo
    {
        return $this->belongsTo(ThemeStage::class, 'theme_stage_id');
    }

    /**
     * Get the affectation of the maitre de stage for this stage.
     */
    public function affectationMaitreStage(): HasOne
    {
        return $this->hasOne(AffectationMaitreStage::class);
    }

    /**
     * Get all affectations of maitres de stage for this stage.
     */
    public function affectationsMaitreStage(): HasMany
    {
        return $this->hasMany(AffectationMaitreStage::class);
    }

    /**
     * Get the current active affectation of maitre de stage for this stage.
     */
    public function activeAffectationMaitreStage(): HasOne
    {
        return $this->hasOne(AffectationMaitreStage::class)
            ->whereIn('statut', ['En cours', 'Acceptée']);
    }

    public function evaluation()
    {
        return $this->hasOne(Evaluation::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * Tous les thèmes proposés pour ce stage.
     */
    public function themesProposes()
    {
        return $this->hasMany(ThemeStage::class, 'stage_id');
    }

    /**
     * Le thème validé pour ce stage.
     */
    public function themeValide()
    {
        return $this->belongsTo(ThemeStage::class, 'theme_stage_id');
    }

    // Définir d'autres relations Eloquent ici ultérieurement
}