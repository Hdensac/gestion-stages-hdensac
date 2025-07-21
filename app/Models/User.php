<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne; // N'oubliez pas d'importer HasOne

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'date_de_naissance',
        'sexe',
        'adresse',
        'email',
        'password',
        'telephone',
        'date_d_inscription',
        'role',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_de_naissance' => 'date',
        'date_d_inscription' => 'date',
        'role' => 'string',
        'telephone' => 'integer',
    ];

    /**
     * Get the stagiaire associated with the user.
     */
   // Ajouter ceci pour la vérification de rôle

public function isStagiaire()
{
    return $this->role === 'stagiaire';
}

public function isAdmin()
{
    return $this->role === 'admin';
}

public function isAgent()
{
    return $this->role === 'agent';
}

public function stagiaire()
{
    return $this->hasOne(Stagiaire::class);
}

public function membreGroupes()
{
    return $this->hasMany(MembreGroupe::class);
}

public function agent()
{
    return $this->hasOne(Agent::class);
}

/**
 * Get the avatar URL.
 *
 * @return string|null
 */
public function getAvatarUrlAttribute()
{
    if (!$this->avatar) {
        return null;
    }
    return asset('storage/' . $this->avatar);
}

/**
 * Récupère tous les utilisateurs stagiaires disponibles pour une période donnée
 *
 * @param string $dateDebut Date de début au format Y-m-d
 * @param string $dateFin Date de fin au format Y-m-d
 * @param int|null $excludeUserId ID de l'utilisateur à exclure (généralement le demandeur principal)
 * @return \Illuminate\Database\Eloquent\Collection
 */
public static function getAvailableStagiaires($dateDebut, $dateFin, $excludeUserId = null)
{
    // Récupérer tous les utilisateurs stagiaires
    $query = self::where('role', 'stagiaire')
        ->with(['stagiaire']);

    if ($excludeUserId) {
        $query->where('id', '!=', $excludeUserId);
    }

    $allStagiaires = $query->get();

    // Filtrer ceux qui sont disponibles
    $availableStagiaires = $allStagiaires->filter(function ($user) use ($dateDebut, $dateFin) {
        if (!$user->stagiaire) {
            return false; // Pas de profil stagiaire
        }

        // Vérifier les conflits via la méthode du modèle DemandeStage
        $conflict = \App\Models\DemandeStage::checkPeriodConflict(
            $user->stagiaire->id_stagiaire,
            $dateDebut,
            $dateFin
        );

        return !$conflict['hasConflict'];
    });

    return $availableStagiaires->values(); // Réindexer la collection
}

/**
 * Vérifie si cet utilisateur stagiaire est disponible pour une période donnée
 *
 * @param string $dateDebut Date de début au format Y-m-d
 * @param string $dateFin Date de fin au format Y-m-d
 * @return array ['available' => bool, 'reason' => string|null, 'conflictData' => array|null]
 */
public function checkStagiaireAvailability($dateDebut, $dateFin)
{
    if ($this->role !== 'stagiaire' || !$this->stagiaire) {
        return [
            'available' => false,
            'reason' => 'Utilisateur non stagiaire',
            'conflictData' => null
        ];
    }

    $conflict = \App\Models\DemandeStage::checkPeriodConflict(
        $this->stagiaire->id_stagiaire,
        $dateDebut,
        $dateFin
    );

    if ($conflict['hasConflict']) {
        $reason = $conflict['conflictType'] === 'demande'
            ? "A déjà une demande {$conflict['conflictData']['statut']} pour cette période"
            : "A déjà un stage {$conflict['conflictData']['statut']} pour cette période";

        return [
            'available' => false,
            'reason' => $reason,
            'conflictData' => $conflict['conflictData']
        ];
    }

    return [
        'available' => true,
        'reason' => null,
        'conflictData' => null
    ];
}

}