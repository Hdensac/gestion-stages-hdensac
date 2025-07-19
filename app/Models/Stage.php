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
        'attestation_structure_generee',
        'attestation_structure_date',
        'attestation_structure_par',
        'attestation_dpaf_generee',
        'attestation_dpaf_date',
        'attestation_dpaf_par',
        'numero_attestation_dpaf',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'attestation_structure_generee' => 'boolean',
        'attestation_structure_date' => 'datetime',
        'attestation_dpaf_generee' => 'boolean',
        'attestation_dpaf_date' => 'datetime',
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
     * Get the agent who generated the structure attestation.
     */
    public function agentAttestationStructure(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'attestation_structure_par');
    }

    /**
     * Get the agent who generated the DPAF attestation.
     */
    public function agentAttestationDpaf(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'attestation_dpaf_par');
    }

    /**
     * Check if the stage can have a DPAF attestation generated.
     */
    public function canGenerateDpafAttestation(): bool
    {
        return $this->attestation_structure_generee &&
               in_array($this->statut, ['termine', 'Terminé', 'terminé']) &&
               !$this->attestation_dpaf_generee;
    }

    /**
     * Generate a unique DPAF attestation number.
     */
    public function generateDpafAttestationNumber(): string
    {
        $year = now()->year;
        $count = self::where('attestation_dpaf_generee', true)
                    ->whereYear('attestation_dpaf_date', $year)
                    ->count() + 1;

        return sprintf('%04d/%d/MEF/DPAF/ATT', $count, $year);
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