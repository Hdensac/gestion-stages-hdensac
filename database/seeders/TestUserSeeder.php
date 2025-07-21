<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Stagiaire;
use App\Models\Structure;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer une structure de test
        $structure = Structure::create([
            'sigle' => 'TEST',
            'libelle' => 'Structure de Test',
            'description' => 'Structure pour les tests de développement'
        ]);

        // Créer un utilisateur stagiaire de test
        $user = User::create([
            'nom' => 'Test',
            'prenom' => 'Stagiaire',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'stagiaire',
            'telephone' => 12345678
        ]);

        // Créer le profil stagiaire
        $stagiaire = Stagiaire::create([
            'user_id' => $user->id,
            'niveau_etude' => 'Master 2',
            'universite' => 'Université de Test',
            'filiere' => 'Informatique'
        ]);

        // Créer quelques autres stagiaires pour les tests de groupe
        for ($i = 2; $i <= 5; $i++) {
            $otherUser = User::create([
                'nom' => "Stagiaire$i",
                'prenom' => "Test$i",
                'email' => "stagiaire$i@example.com",
                'password' => bcrypt('password'),
                'role' => 'stagiaire',
                'telephone' => intval("1234567$i")
            ]);

            Stagiaire::create([
                'user_id' => $otherUser->id,
                'niveau_etude' => 'Master 1',
                'universite' => 'Université de Test',
                'filiere' => 'Informatique'
            ]);
        }

        // Créer un administrateur
        $admin = User::create([
            'nom' => 'Admin',
            'prenom' => 'Système',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'telephone' => 87654321
        ]);

        // Créer un agent RS (Responsable de Structure)
        $rs = User::create([
            'nom' => 'Responsable',
            'prenom' => 'Structure',
            'email' => 'rs@example.com',
            'password' => bcrypt('password'),
            'role' => 'RS',
            'telephone' => 11223344
        ]);

        // Créer un agent MS (Maître de Stage)
        $ms = User::create([
            'nom' => 'Maitre',
            'prenom' => 'Stage',
            'email' => 'ms@example.com',
            'password' => bcrypt('password'),
            'role' => 'MS',
            'telephone' => 55667788
        ]);

        echo "Utilisateurs de test créés avec succès !\n";
        echo "=== COMPTES DE TEST ===\n";
        echo "Stagiaire - Email: test@example.com | Mot de passe: password\n";
        echo "Admin - Email: admin@example.com | Mot de passe: password\n";
        echo "RS - Email: rs@example.com | Mot de passe: password\n";
        echo "MS - Email: ms@example.com | Mot de passe: password\n";
    }
}
