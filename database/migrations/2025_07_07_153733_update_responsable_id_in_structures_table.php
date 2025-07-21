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
        Schema::table('structures', function (Blueprint $table) {
            // Supprimer d'abord la contrainte de clé étrangère existante
            $table->dropForeign(['responsable_id']);

            // Modifier la colonne pour qu'elle pointe vers la table agents au lieu de users
            $table->foreignId('responsable_id')
                  ->nullable()
                  ->change();
        });

        // Ajouter la nouvelle contrainte de clé étrangère
        Schema::table('structures', function (Blueprint $table) {
            $table->foreign('responsable_id')
                  ->references('id')
                  ->on('agents')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('structures', function (Blueprint $table) {
            // Supprimer la contrainte de clé étrangère vers agents
            $table->dropForeign(['responsable_id']);

            // Restaurer la contrainte de clé étrangère vers users
            $table->foreign('responsable_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }
};
