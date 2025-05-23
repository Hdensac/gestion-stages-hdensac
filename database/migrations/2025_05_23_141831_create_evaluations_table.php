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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->constrained()->onDelete('cascade');
            $table->foreignId('agent_id')->nullable()->constrained('agents')->onDelete('set null');
            $table->float('ponctualite')->default(1);
            $table->float('motivation')->default(1);
            $table->float('capacite_apprendre')->default(1);
            $table->float('qualite_travail')->default(1);
            $table->float('rapidite_execution')->default(1);
            $table->float('jugement')->default(1);
            $table->float('esprit_motivation')->default(1);
            $table->float('esprit_collaboration')->default(1);
            $table->float('sens_responsabilite')->default(1);
            $table->float('communication')->default(1);
            $table->float('note_totale')->default(0);
            $table->text('commentaire_general')->nullable();
            $table->timestamp('date_evaluation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
