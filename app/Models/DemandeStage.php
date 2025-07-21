<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeStage extends Model
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'demande_stages';

    /**
     * La clé primaire associée à la table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'stagiaire_id',
        'structure_id',
        'date_debut',
        'date_fin',
        'type',
        'nature',
        'lettre_cv_path',
        'code_suivi',
        'statut',
        'date_soumission',
        'date_traitement',
        'traite_par',
        'motif_refus',
        'agent_id',
        'visage_path',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_soumission' => 'datetime',
        'date_traitement' => 'datetime',
        'structure_id' => 'integer',
        'traite_par' => 'integer',
    ];

    /**
     * Relation avec le stagiaire
     */
    public function stagiaire()
    {
        return $this->belongsTo(Stagiaire::class, 'stagiaire_id', 'id_stagiaire');
    }

    /**
     * Relation avec la structure
     */
    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

    /**
     * Relation avec les membres du groupe
     */
    public function membres()
    {
        return $this->hasMany(MembreGroupe::class, 'demande_stage_id');
    }

    /**
     * Relation avec les affectations de responsables de structure
     */
    public function affectations()
    {
        return $this->hasMany(AffectationResponsableStructure::class, 'id_demande_stages');
    }

    /**
     * Relation avec l'agent qui a traité la demande
     */
    public function agentTraitement()
    {
        return $this->belongsTo(Agent::class, 'traite_par');
    }

    /**
     * Relation avec l'agent assigné à la demande
     */
    public function agentAssigne()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    /**
     * Récupère toutes les structures auxquelles cette demande est affectée
     */
    public function structuresAffectees()
    {
        return Structure::whereIn('id', $this->affectations()->pluck('structure_id'));
    }

    /**
     * Relation avec la structure souhaitée par le stagiaire
     */
    public function structureSouhaitee()
    {
        return $this->belongsTo(Structure::class, 'structure_id');
    }

    /**
     * Dernière affectation de la demande (structure d'affectation réelle)
     */
    public function derniereAffectation()
    {
        return $this->hasOne(\App\Models\AffectationResponsableStructure::class, 'id_demande_stages')->latestOfMany();
    }

    /**
     * Vérifie si un stagiaire a déjà une demande ou un stage en conflit pour une période donnée
     *
     * @param int $stagiaireId ID du stagiaire (id_stagiaire de la table stagiaires)
     * @param string $dateDebut Date de début au format Y-m-d
     * @param string $dateFin Date de fin au format Y-m-d
     * @param int|null $excludeDemandeId ID de la demande à exclure (pour les modifications)
     * @return array ['hasConflict' => bool, 'conflictType' => string, 'conflictData' => array]
     */
    public static function checkPeriodConflict($stagiaireId, $dateDebut, $dateFin, $excludeDemandeId = null)
    {
        // Convertir les dates en objets Carbon pour faciliter les comparaisons
        $dateDebut = \Carbon\Carbon::parse($dateDebut);
        $dateFin = \Carbon\Carbon::parse($dateFin);

        // 1. Vérifier les demandes non rejetées (en_attente, accepte)
        $conflictingRequest = self::where('stagiaire_id', $stagiaireId)
            ->whereIn('statut', ['En attente', 'Accepté'])
            ->when($excludeDemandeId, function ($query, $excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->where(function ($query) use ($dateDebut, $dateFin) {
                $query->where(function ($q) use ($dateDebut, $dateFin) {
                    // Cas 1: La nouvelle période commence pendant une période existante
                    $q->where('date_debut', '<=', $dateDebut)
                      ->where('date_fin', '>=', $dateDebut);
                })->orWhere(function ($q) use ($dateDebut, $dateFin) {
                    // Cas 2: La nouvelle période finit pendant une période existante
                    $q->where('date_debut', '<=', $dateFin)
                      ->where('date_fin', '>=', $dateFin);
                })->orWhere(function ($q) use ($dateDebut, $dateFin) {
                    // Cas 3: La nouvelle période englobe complètement une période existante
                    $q->where('date_debut', '>=', $dateDebut)
                      ->where('date_fin', '<=', $dateFin);
                })->orWhere(function ($q) use ($dateDebut, $dateFin) {
                    // Cas 4: Une période existante englobe complètement la nouvelle période
                    $q->where('date_debut', '<=', $dateDebut)
                      ->where('date_fin', '>=', $dateFin);
                });
            })
            ->with(['structure'])
            ->first();

        if ($conflictingRequest) {
            return [
                'hasConflict' => true,
                'conflictType' => 'demande',
                'conflictData' => [
                    'id' => $conflictingRequest->id,
                    'code_suivi' => $conflictingRequest->code_suivi,
                    'statut' => $conflictingRequest->statut,
                    'date_debut' => $conflictingRequest->date_debut->format('d/m/Y'),
                    'date_fin' => $conflictingRequest->date_fin->format('d/m/Y'),
                    'structure' => $conflictingRequest->structure->libelle ?? 'Structure non définie'
                ]
            ];
        }

        // 2. Vérifier les stages en cours ou à venir
        $conflictingStage = \App\Models\Stage::where('stagiaire_id', $stagiaireId)
            ->whereIn('statut', ['En cours', 'À venir'])
            ->where(function ($query) use ($dateDebut, $dateFin) {
                $query->where(function ($q) use ($dateDebut, $dateFin) {
                    $q->where('date_debut', '<=', $dateDebut)
                      ->where('date_fin', '>=', $dateDebut);
                })->orWhere(function ($q) use ($dateDebut, $dateFin) {
                    $q->where('date_debut', '<=', $dateFin)
                      ->where('date_fin', '>=', $dateFin);
                })->orWhere(function ($q) use ($dateDebut, $dateFin) {
                    $q->where('date_debut', '>=', $dateDebut)
                      ->where('date_fin', '<=', $dateFin);
                })->orWhere(function ($q) use ($dateDebut, $dateFin) {
                    $q->where('date_debut', '<=', $dateDebut)
                      ->where('date_fin', '>=', $dateFin);
                });
            })
            ->with(['structure'])
            ->first();

        if ($conflictingStage) {
            return [
                'hasConflict' => true,
                'conflictType' => 'stage',
                'conflictData' => [
                    'id' => $conflictingStage->id,
                    'statut' => $conflictingStage->statut,
                    'date_debut' => $conflictingStage->date_debut->format('d/m/Y'),
                    'date_fin' => $conflictingStage->date_fin->format('d/m/Y'),
                    'structure' => $conflictingStage->structure->libelle ?? 'Structure non définie'
                ]
            ];
        }

        return [
            'hasConflict' => false,
            'conflictType' => null,
            'conflictData' => null
        ];
    }
}
