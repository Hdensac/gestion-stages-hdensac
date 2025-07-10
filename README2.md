# 🎓 Système de Gestion des Stages - HDENSAC

Une application web complète pour la gestion des stages au sein du Haut Département de l'Enseignement Supérieur, de l'Artisanat et de la Culture (HDENSAC).

## 📋 Table des Matières

- [Vue d'ensemble](#vue-densemble)
- [Fonctionnalités](#fonctionnalités)
- [Architecture](#architecture)
- [Rôles et Permissions](#rôles-et-permissions)
- [Installation](#installation)
- [Configuration](#configuration)
- [Utilisation](#utilisation)
- [Technologies](#technologies)

## 🎯 Vue d'ensemble

Cette application permet de gérer l'ensemble du processus de stage, depuis la soumission des demandes par les stagiaires jusqu'à l'évaluation finale, en passant par l'affectation des maîtres de stage et le suivi des activités.

### Objectifs principaux :
- Digitaliser le processus de gestion des stages
- Faciliter la communication entre tous les acteurs
- Centraliser les informations et documents
- Automatiser les notifications et suivis
- Générer des rapports et statistiques

## ⚡ Fonctionnalités

### 👨‍🎓 Pour les Stagiaires
- **Soumission de demandes** : Dépôt de demandes individuelles ou en groupe
- **Suivi en temps réel** : Visualisation du statut et progression du stage
- **Gestion des documents** : Upload de CV, lettres de motivation, relevés de notes
- **Notifications** : Alertes sur les changements de statut
- **Consultation des évaluations** : Accès aux notes et commentaires

### 👨‍💼 Pour les Responsables de Structure (RS)
- **Gestion des demandes** : Validation, refus ou réaffectation des demandes
- **Affectation des maîtres de stage** : Attribution des stages aux agents MS
- **Suivi des structures** : Gestion de l'organigramme et des sous-structures
- **Réaffectation** : Transfert de stages entre maîtres de stage
- **Tableaux de bord** : Vue d'ensemble des activités de la structure

### 👨‍🏫 Pour les Maîtres de Stage (MS)
- **Gestion des stages assignés** : Suivi des stagiaires sous responsabilité
- **Évaluation** : Notation détaillée des stagiaires (ponctualité, motivation, etc.)
- **Communication** : Contact direct avec les stagiaires
- **Réaffectation** : Possibilité de transférer vers d'autres MS
- **Suivi de progression** : Visualisation de l'avancement des stages

### 🏛️ Pour le DPAF (Direction du Personnel et des Affaires Financières)
- **Vue globale** : Supervision de tous les stages de l'organisation
- **Gestion des évaluations** : Consultation des notes individuelles et de groupe
- **Rapports** : Génération de statistiques et analyses
- **Validation finale** : Approbation des évaluations et attestations

## 🏗️ Architecture

### Structure du projet
```
gestion-stages-hdensac/
├── app/
│   ├── Http/Controllers/
│   │   ├── Agent/           # Contrôleurs pour les agents
│   │   ├── Stagiaire/       # Contrôleurs pour les stagiaires
│   │   └── Auth/            # Authentification
│   ├── Models/              # Modèles Eloquent
│   ├── Mail/                # Classes d'emails
│   └── Notifications/       # Système de notifications
├── database/
│   ├── migrations/          # Migrations de base de données
│   └── seeders/             # Données de test
├── resources/
│   ├── js/
│   │   ├── Pages/           # Composants Vue.js (Inertia)
│   │   └── Layouts/         # Layouts de l'application
│   └── views/               # Templates Blade
└── routes/                  # Définition des routes
```

### Base de données
- **users** : Utilisateurs du système
- **agents** : Agents avec rôles spécifiques
- **stagiaires** : Informations des stagiaires
- **structures** : Organigramme organisationnel
- **demande_stages** : Demandes de stage
- **stages** : Stages validés
- **affectation_maitre_stages** : Attribution des maîtres de stage
- **evaluations** : Évaluations des stagiaires
- **membre_groupes** : Gestion des stages en groupe

## 👥 Rôles et Permissions

### 🔐 Système de rôles hiérarchique

| Rôle | Description | Permissions principales |
|------|-------------|------------------------|
| **DPAF** | Direction du Personnel | Vue globale, rapports, validation finale |
| **RS** | Responsable de Structure | Gestion des demandes, affectation MS |
| **MS** | Maître de Stage | Suivi stagiaires, évaluations |
| **Stagiaire** | Demandeur de stage | Soumission demandes, suivi personnel |

### 🔄 Workflow des stages

1. **Soumission** : Le stagiaire soumet sa demande
2. **Validation RS** : Le responsable de structure valide
3. **Affectation MS** : Attribution d'un maître de stage
4. **Suivi** : Accompagnement pendant le stage
5. **Évaluation** : Notation par le maître de stage
6. **Validation DPAF** : Approbation finale

## 🚀 Installation

### Prérequis

#### Option 1 : Avec Laragon (Recommandé pour Windows)
- **Laragon 2025 v8.2.3 Full (64-bit)** - 229 MB
  - Apache 2.4, Nginx
  - MySQL 9.1/8.4
  - PHP 8.4/8.3/8.2/8.1 (auto-mise à jour)
  - Node.js 22/23
  - Python 3.13
  - Redis, Memcached
  - PostgreSQL 15/16/17
  - npm, git inclus
- **Composer** (inclus dans Laragon)
- **Mailpit** (optionnel, pour tester les emails en développement)
- **Toutes les autres dépendances incluses** - Installation ultra-simplifiée !

#### Option 2 : Installation manuelle
- PHP 8.1+
- Composer
- Node.js 16+
- MySQL 8.0+
- Serveur web (Apache/Nginx)
- Laravel 10

#### Extensions PHP requises
- OpenSSL
- PDO
- Mbstring
- Tokenizer
- XML
- Ctype
- JSON
- BCMath
- Fileinfo
- GD (pour la manipulation d'images)

### Étapes d'installation

#### Installation avec Laragon (Windows)

1. **Installer Laragon 2025 v8.2.3 Full**
   - Télécharger **Laragon 2025 v8.2.3 Full (64-bit)** depuis [laragon.org](https://laragon.org)
   - Taille : 229 MB (tout inclus !)
   - Installer et démarrer Laragon
   - Vérifier que Apache/Nginx et MySQL sont démarrés
   - **Node.js 22/23 et Git sont déjà inclus** - Aucune installation supplémentaire nécessaire !

2. **Installer Mailpit (optionnel, pour les emails de développement)**
   ```bash
   # Via Go (si installé)
   go install github.com/axllent/mailpit@latest

   # Ou télécharger l'exécutable depuis GitHub
   # https://github.com/axllent/mailpit/releases
   ```

2. **Cloner le projet dans Laragon**
```bash
# Naviguer vers le dossier www de Laragon (généralement C:\laragon\www)
cd C:\laragon\www
git clone https://github.com/votre-repo/gestion-stages-hdensac.git
cd gestion-stages-hdensac
```

3. **Installer les dépendances**
```bash
composer install
npm install
```

4. **Configuration de l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configuration de la base de données pour Laragon**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_stages
DB_USERNAME=root
DB_PASSWORD=
```

6. **Créer la base de données**
   - Ouvrir HeidiSQL (inclus avec Laragon)
   - Se connecter avec : Host=127.0.0.1, User=root, Password=(vide)
   - Créer une nouvelle base de données nommée `gestion_stages`

7. **Migration et seeding**
```bash
php artisan migrate
php artisan db:seed
```

8. **Compilation des assets**
```bash
npm run build
```

9. **Accéder à l'application**
   - URL automatique Laragon : `http://gestion-stages-hdensac.test`
   - Ou via : `http://localhost/gestion-stages-hdensac/public`

> **💡 Avantage Laragon :** Mise à jour automatique des versions PHP les plus récentes !

#### Installation manuelle (Linux/Mac)

1. **Cloner le repository**
```bash
git clone https://github.com/votre-repo/gestion-stages-hdensac.git
cd gestion-stages-hdensac
```

2. **Installer les dépendances**
```bash
composer install
npm install
```

3. **Configuration de l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configuration de la base de données**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_stages
DB_USERNAME=votre_username
DB_PASSWORD=votre_password
```

5. **Migration et seeding**
```bash
php artisan migrate
php artisan db:seed
```

6. **Compilation des assets**
```bash
npm run build
```

7. **Lancement du serveur**
```bash
php artisan serve
```

## ⚙️ Configuration

### Configuration email

#### Pour le développement avec Mailpit
```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@hdensac.test"
MAIL_FROM_NAME="HDENSAC Stages"
```

#### Pour la production
```env
MAIL_MAILER=smtp
MAIL_HOST=votre-smtp-host
MAIL_PORT=587
MAIL_USERNAME=votre-email
MAIL_PASSWORD=votre-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@hdensac.gov"
MAIL_FROM_NAME="HDENSAC Stages"
```

#### Démarrer Mailpit (développement)
```bash
# Lancer Mailpit
mailpit

# Interface web disponible sur : http://localhost:8025
```

### Configuration des notifications
Les notifications sont automatiquement envoyées pour :
- Changements de statut des demandes
- Affectations de maîtres de stage
- Réaffectations
- Évaluations terminées

## 📱 Utilisation

### Accès à l'application
- **URL** : `http://localhost:8000`
- **Comptes de test** : Créés automatiquement via les seeders

### Dashboards spécialisés
- **Stagiaires** : `/stagiaire/dashboard`
- **Maîtres de Stage** : `/agent/ms/dashboard`
- **Responsables Structure** : `/agent/rs/dashboard`
- **DPAF** : `/agent/dpaf/dashboard`

### Fonctionnalités avancées

#### 🎯 Gestion des groupes
- Stages en équipe avec évaluations individuelles
- Calcul automatique de moyennes de groupe
- Suivi collectif et individuel

#### 📊 Évaluations détaillées
- Grille d'évaluation sur 10 critères
- Notes sur 20 avec commentaires
- Historique des évaluations

#### 🔄 Réaffectations intelligentes
- Transfert entre maîtres de stage
- Notifications automatiques
- Historique des affectations

#### 🏢 Gestion organisationnelle
- Organigramme hiérarchique avec structures et sous-structures
- Affichage collapsible pour grandes organisations (100+ structures)
- Filtrage et recherche avancée
- Interface moderne et responsive

## 🛠️ Technologies

### Backend
- **Laravel 10** : Framework PHP
- **MySQL** : Base de données
- **Eloquent ORM** : Mapping objet-relationnel
- **Laravel Mail** : Système d'emails
- **Laravel Notifications** : Notifications en temps réel

### Frontend
- **Vue.js 3** : Framework JavaScript
- **Inertia.js** : SPA sans API
- **Tailwind CSS** : Framework CSS
- **Vite** : Build tool moderne

### Outils de développement
- **Composer** : Gestionnaire de dépendances PHP
- **NPM** : Gestionnaire de paquets Node.js
- **Laravel Artisan** : CLI Laravel
- **Laravel Tinker** : REPL interactif

## 🎨 Interface Utilisateur

### Design moderne
- Interface responsive adaptée à tous les écrans
- Thème cohérent avec codes couleurs par rôle
- Animations et transitions fluides
- Composants réutilisables

### Tableaux de bord personnalisés
- Vue d'ensemble adaptée à chaque rôle
- Widgets informatifs et interactifs
- Graphiques de progression en temps réel
- Navigation intuitive

### Gestion des évaluations
- Affichage intelligent selon le type de stage :
  - **Stages individuels** : Note unique du stagiaire
  - **Stages en groupe** : Moyenne du groupe + détail par membre
- Interface d'évaluation complète avec critères multiples
- Historique et suivi des performances

## 📈 Fonctionnalités à venir

- [ ] API REST pour intégrations externes
- [ ] Génération automatique d'attestations PDF
- [ ] Système de chat en temps réel
- [ ] Application mobile
- [ ] Tableaux de bord analytiques avancés
- [ ] Intégration calendrier
- [ ] Système de notation par QR Code
- [ ] Export de données en Excel/CSV
- [ ] Système de rappels automatiques

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/nouvelle-fonctionnalite`)
3. Commit les changements (`git commit -am 'Ajout nouvelle fonctionnalité'`)
4. Push vers la branche (`git push origin feature/nouvelle-fonctionnalite`)
5. Créer une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 📞 Support

Pour toute question ou problème :
- **Email** : support@hdensac.gov
- **Documentation** : [Wiki du projet](lien-vers-wiki)
- **Issues** : [GitHub Issues](lien-vers-issues)

---

**Développé avec ❤️ pour HDENSAC**
