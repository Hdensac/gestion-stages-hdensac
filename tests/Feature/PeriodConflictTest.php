<?php

namespace Tests\Feature;

use App\Models\DemandeStage;
use App\Models\Stagiaire;
use App\Models\User;
use App\Models\Structure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PeriodConflictTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer une structure de test
        $this->structure = Structure::create([
            'sigle' => 'TEST',
            'libelle' => 'Structure de Test',
            'description' => 'Structure pour les tests'
        ]);
    }

    /**
     * Test de détection de conflit avec une demande existante
     */
    public function test_detects_conflict_with_existing_demande(): void
    {
        // Créer un utilisateur stagiaire
        $user = User::factory()->create(['role' => 'stagiaire']);
        $stagiaire = Stagiaire::create([
            'user_id' => $user->id,
            'niveau_etude' => 'Master',
            'universite' => 'Test University',
            'filiere' => 'Informatique'
        ]);

        // Créer une demande existante
        DemandeStage::create([
            'stagiaire_id' => $stagiaire->id_stagiaire,
            'structure_id' => $this->structure->id,
            'date_debut' => '2024-06-01',
            'date_fin' => '2024-08-31',
            'type' => 'Académique',
            'nature' => 'Individuel',
            'statut' => 'En attente',
            'code_suivi' => 'TEST001'
        ]);

        // Tester le conflit avec une période qui chevauche
        $conflict = DemandeStage::checkPeriodConflict(
            $stagiaire->id_stagiaire,
            '2024-07-01',
            '2024-09-30'
        );

        $this->assertTrue($conflict['hasConflict']);
        $this->assertEquals('demande', $conflict['conflictType']);
        $this->assertEquals('TEST001', $conflict['conflictData']['code_suivi']);
    }

    /**
     * Test qu'aucun conflit n'est détecté pour des périodes non chevauchantes
     */
    public function test_no_conflict_with_non_overlapping_periods(): void
    {
        $user = User::factory()->create(['role' => 'stagiaire']);
        $stagiaire = Stagiaire::create([
            'user_id' => $user->id,
            'niveau_etude' => 'Master',
            'universite' => 'Test University',
            'filiere' => 'Informatique'
        ]);

        // Créer une demande existante
        DemandeStage::create([
            'stagiaire_id' => $stagiaire->id_stagiaire,
            'structure_id' => $this->structure->id,
            'date_debut' => '2024-06-01',
            'date_fin' => '2024-08-31',
            'type' => 'Académique',
            'nature' => 'Individuel',
            'statut' => 'En attente',
            'code_suivi' => 'TEST002'
        ]);

        // Tester avec une période qui ne chevauche pas
        $conflict = DemandeStage::checkPeriodConflict(
            $stagiaire->id_stagiaire,
            '2024-10-01',
            '2024-12-31'
        );

        $this->assertFalse($conflict['hasConflict']);
    }
}
