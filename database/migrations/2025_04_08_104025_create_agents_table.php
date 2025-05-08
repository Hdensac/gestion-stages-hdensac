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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('matricule')->unique();
            $table->string('fonction')->nullable();
            $table->date('date_embauche')->nullable();
            $table->foreignId('universite_responsable_id')->nullable()->constrained('universites')->onDelete('set null'); // Ajout de la clé étrangère
            $table->enum('role_agent', ['DPAF', 'MS', 'RS'])->nullable();
            $table->timestamps();

            $table->foreignId('structure_id') // Ajout de la colonne structure_id
                ->nullable()
                ->constrained('structures')
                ->onDelete('set null');

            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};