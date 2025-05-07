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
        Schema::create('demande_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stagiaire_id')->constrained('stagiaires', 'id_stagiaire')->onDelete('cascade'); // Clé étrangère vers stagiaires.id_stagiaire
            $table->foreignId('structure_id')->nullable()->constrained('structures')->onDelete('set null'); // Clé étrangère vers structures.id (nullable)
            $table->enum('nature', ['Individuel', 'Groupe'])->default('Individuel'); // Colonne enum pour la nature de la demande
            $table->enum('type', ['Académique', 'Professionnelle'])->default('Académique');
            $table->string('statut')->default('En attente'); // Statut de la demande
            $table->text('motif_refus')->nullable();
            $table->timestamp('date_soumission')->nullable();
            $table->timestamp('date_traitement')->nullable();
            $table->foreignId('traite_par')->nullable()
                ->constrained('agents')->onDelete('set null');
            $table->string('lettre_cv_path')->nullable();
            $table->string('lettre_motivation_path')->nullable();
            $table->string('releve_notes_path')->nullable();
            $table->string('convention_stage_path')->nullable();
            $table->string('assurance_path')->nullable();
            $table->string('code_suivi')->nullable()->unique(); // Ajout de la contrainte unique pour code_suivi
            $table->string('diplomes_path')->nullable();
            $table->integer('structure_souhaitee')->nullable(); // ID de la structure souhaitée (peut-être redondant avec structure_id ?)
            $table->date('date_debut')->nullable(); // Ajout de la colonne date_debut
            $table->date('date_fin')->nullable(); // Ajout de la colonne date_fin
            $table->timestamps();
        });

        // Remplir la colonne code_suivi avec des valeurs uniques pour les enregistrements existants
        \App\Models\DemandeStage::all()->each(function ($demande) {
            if (!$demande->code_suivi) {
                $demande->code_suivi = \Illuminate\Support\Str::uuid(); // Génère un UUID unique
                $demande->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_stages');
    }
};