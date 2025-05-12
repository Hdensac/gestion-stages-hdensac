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
        Schema::table('affectation_maitre_stages', function (Blueprint $table) {
            // Ajout de la colonne agent_affectant_id pour stocker l'ID de l'agent RS qui affecte la demande
            $table->unsignedBigInteger('agent_affectant_id')->nullable()->after('maitre_stage_id');
            
            // Ajout de la contrainte de clé étrangère
            $table->foreign('agent_affectant_id')->references('id')->on('agents')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affectation_maitre_stages', function (Blueprint $table) {
            // Suppression de la contrainte de clé étrangère
            $table->dropForeign(['agent_affectant_id']);
            
            // Suppression de la colonne
            $table->dropColumn('agent_affectant_id');
        });
    }
};
