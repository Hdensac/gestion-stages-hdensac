<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('demande_stages', function (Blueprint $table) {
            // Ajouter des index pour optimiser les requêtes de vérification de conflits
            $table->index(['stagiaire_id', 'statut', 'date_debut', 'date_fin'], 'idx_stagiaire_period_conflict');
            $table->index(['date_debut', 'date_fin'], 'idx_period_range');
            $table->index(['statut'], 'idx_statut');
        });

        Schema::table('stages', function (Blueprint $table) {
            // Ajouter des index similaires pour la table stages
            $table->index(['stagiaire_id', 'statut', 'date_debut', 'date_fin'], 'idx_stage_stagiaire_period');
            $table->index(['date_debut', 'date_fin'], 'idx_stage_period_range');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demande_stages', function (Blueprint $table) {
            $table->dropIndex('idx_stagiaire_period_conflict');
            $table->dropIndex('idx_period_range');
            $table->dropIndex('idx_statut');
        });

        Schema::table('stages', function (Blueprint $table) {
            $table->dropIndex('idx_stage_stagiaire_period');
            $table->dropIndex('idx_stage_period_range');
        });
    }
};
