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
        Schema::create('theme_stages', function (Blueprint $table) {
            $table->id();
            $table->string('intitule');
            $table->enum('etat', ['Proposé', 'Modifié', 'Validé', 'Refusé'])->default('Proposé');
            $table->text('description')->nullable();
            $table->text('mots_cles')->nullable(); // Pour faciliter la recherche
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_stages');
    }
};