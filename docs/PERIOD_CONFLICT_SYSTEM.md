# Système de Gestion des Conflits de Période

## Vue d'ensemble

Ce système empêche les stagiaires de soumettre plusieurs demandes de stage pour des périodes qui se chevauchent et garantit que les membres d'un groupe ne sont pas déjà occupés pendant la période demandée.

## Fonctionnalités

### 1. Validation des Périodes pour le Stagiaire Principal

- **Objectif** : Empêcher un stagiaire de soumettre une nouvelle demande s'il a déjà :
  - Une demande en attente ou acceptée pour une période qui chevauche
  - Un stage en cours ou à venir pour une période qui chevauche

- **Statuts considérés comme conflictuels** :
  - Demandes : `En attente`, `Accepté`
  - Stages : `En cours`, `À venir`

### 2. Validation des Membres de Groupe

- **Objectif** : S'assurer que tous les membres sélectionnés pour un stage de groupe sont disponibles
- **Vérification** : Même logique que pour le stagiaire principal, appliquée à chaque membre

## API Endpoints

### Vérification de Période pour le Stagiaire Connecté

```http
POST /api/availability/check-period
Content-Type: application/json
Authorization: Bearer {token}

{
    "date_debut": "2024-07-01",
    "date_fin": "2024-09-30",
    "demande_id": null // Optionnel, pour exclure une demande lors de la modification
}
```

**Réponse en cas de conflit :**
```json
{
    "success": false,
    "available": false,
    "message": "Vous avez déjà une demande En attente (Code: STG001) pour la période du 01/06/2024 au 31/08/2024 dans la structure \"Direction Informatique\". Veuillez modifier les dates ou attendre le traitement de votre demande actuelle.",
    "conflict": {
        "hasConflict": true,
        "conflictType": "demande",
        "conflictData": {
            "id": 1,
            "code_suivi": "STG001",
            "statut": "En attente",
            "date_debut": "01/06/2024",
            "date_fin": "31/08/2024",
            "structure": "Direction Informatique"
        }
    }
}
```

### Récupération des Membres Disponibles

```http
POST /api/availability/available-members
Content-Type: application/json
Authorization: Bearer {token}

{
    "date_debut": "2024-07-01",
    "date_fin": "2024-09-30"
}
```

**Réponse :**
```json
{
    "success": true,
    "members": [
        {
            "id": 2,
            "name": "Dupont",
            "prenom": "Jean",
            "email": "jean.dupont@example.com",
            "niveau_etude": "Master 2",
            "universite": "Université de Cotonou",
            "filiere": "Informatique"
        }
    ],
    "total": 1
}
```

### Vérification de Disponibilité d'un Membre Spécifique

```http
POST /api/availability/check-member
Content-Type: application/json
Authorization: Bearer {token}

{
    "user_id": 2,
    "date_debut": "2024-07-01",
    "date_fin": "2024-09-30"
}
```

### Vérification de Plusieurs Membres

```http
POST /api/availability/check-multiple-members
Content-Type: application/json
Authorization: Bearer {token}

{
    "user_ids": [2, 3, 4],
    "date_debut": "2024-07-01",
    "date_fin": "2024-09-30"
}
```

## Utilisation dans les Modèles

### Vérification de Conflit de Période

```php
use App\Models\DemandeStage;

$conflict = DemandeStage::checkPeriodConflict(
    $stagiaireId,
    '2024-07-01',
    '2024-09-30',
    $excludeDemandeId // Optionnel
);

if ($conflict['hasConflict']) {
    // Gérer le conflit
    $type = $conflict['conflictType']; // 'demande' ou 'stage'
    $data = $conflict['conflictData'];
}
```

### Récupération des Stagiaires Disponibles

```php
use App\Models\User;

$availableMembers = User::getAvailableStagiaires(
    '2024-07-01',
    '2024-09-30',
    $excludeUserId // Optionnel
);
```

### Vérification de Disponibilité d'un Utilisateur

```php
$user = User::find($userId);
$availability = $user->checkStagiaireAvailability(
    '2024-07-01',
    '2024-09-30'
);

if (!$availability['available']) {
    echo $availability['reason'];
}
```

## Validation des Formulaires

### Utilisation de la FormRequest

```php
use App\Http\Requests\StoreDemandeStageRequest;

public function store(StoreDemandeStageRequest $request)
{
    // Les validations de conflit sont automatiquement effectuées
    $validated = $request->validated();
    
    // Créer la demande...
}
```

### Règles de Validation Personnalisées

La `StoreDemandeStageRequest` inclut automatiquement :
- Validation de période pour le stagiaire principal
- Validation de disponibilité pour chaque membre du groupe
- Messages d'erreur détaillés et informatifs

## Messages d'Erreur Types

### Conflit de Demande
```
"Vous avez déjà une demande En attente (Code: STG001) pour la période du 01/06/2024 au 31/08/2024 dans la structure "Direction Informatique". Veuillez modifier les dates ou attendre le traitement de votre demande actuelle."
```

### Conflit de Stage
```
"Vous avez déjà un stage En cours pour la période du 01/06/2024 au 31/08/2024 dans la structure "Direction Informatique". Veuillez modifier les dates ou attendre la fin de votre stage actuel."
```

### Membre Non Disponible
```
"Jean Dupont n'est pas disponible pour cette période : A déjà une demande Accepté pour cette période"
```

## Optimisations de Performance

### Index de Base de Données

Des index ont été ajoutés pour optimiser les requêtes :

```sql
-- Table demande_stages
INDEX idx_stagiaire_period_conflict (stagiaire_id, statut, date_debut, date_fin)
INDEX idx_period_range (date_debut, date_fin)
INDEX idx_statut (statut)

-- Table stages
INDEX idx_stage_stagiaire_period (stagiaire_id, statut, date_debut, date_fin)
INDEX idx_stage_period_range (date_debut, date_fin)
```

### Algorithme de Détection de Chevauchement

Le système utilise quatre cas pour détecter les chevauchements :
1. La nouvelle période commence pendant une période existante
2. La nouvelle période finit pendant une période existante
3. La nouvelle période englobe complètement une période existante
4. Une période existante englobe complètement la nouvelle période

## Tests

Des tests automatisés vérifient :
- Détection correcte des conflits
- Absence de faux positifs
- Récupération des membres disponibles
- Validation des formulaires

```bash
php artisan test --filter=PeriodConflictTest
```

## Intégration Frontend

Le système est conçu pour être utilisé avec des validations en temps réel côté frontend :
- Vérification de période lors de la saisie des dates
- Filtrage dynamique des membres disponibles
- Messages d'erreur contextuels et informatifs
