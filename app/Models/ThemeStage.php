<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThemeStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'intitule',
        'etat',
        'description',
        'mots_cles',
        'user_id',
    ];

    /**
     * Get all the stages that are related to this theme.
     */
    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class);
    }

    /**
     * Le stage auquel ce thème est rattaché.
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }

    /**
     * L'utilisateur qui a proposé ce thème.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Définir d'autres relations Eloquent ici ultérieurement (propositions, etc.)
}