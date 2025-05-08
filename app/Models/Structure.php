<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Structure extends Model
{
    use HasFactory;

    protected $fillable = [
        'sigle',
        'libelle',
        'responsable_id',
        'description',
        'parent_id',
        'type_structure',
        'niveau',
        'ordre',
        'active',
    ];

    /**
     * Relation avec le responsable (agent).
     */
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'responsable_id');
    }

    /**
     * Get all the stages that belong to this structure.
     */
    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class);
    }

    /**
     * Get all the demande attestation that are directed to this structure (if specified).
     */
    public function demandesAttestation(): HasMany
    {
        return $this->hasMany(DemandeAttestation::class); // Assurez-vous que la clé étrangère 'structure_id' existe dans DemandeAttestation si nécessaire
    }

    /**
     * Get all the stagiaires assigned to this structure.
     */
    public function stagiaires(): HasMany
    {
        return $this->hasMany(Stagiaire::class);
    }

    /**
     * Structure parente (relation hiérarchique).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Structure::class, 'parent_id');
    }

    /**
     * Sous-structures (enfants dans l'organigramme).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Structure::class, 'parent_id');
    }

    // Définir d'autres relations Eloquent ici ultérieurement
}