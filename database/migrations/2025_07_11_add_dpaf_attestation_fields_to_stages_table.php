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
        Schema::table('stages', function (Blueprint $table) {
            // Attestation de la structure d'accueil
            $table->boolean('attestation_structure_generee')->default(false)->after('note');
            $table->timestamp('attestation_structure_date')->nullable()->after('attestation_structure_generee');
            $table->unsignedBigInteger('attestation_structure_par')->nullable()->after('attestation_structure_date');
            
            // Attestation DPAF (conditionnée par la première)
            $table->boolean('attestation_dpaf_generee')->default(false)->after('attestation_structure_par');
            $table->timestamp('attestation_dpaf_date')->nullable()->after('attestation_dpaf_generee');
            $table->unsignedBigInteger('attestation_dpaf_par')->nullable()->after('attestation_dpaf_date');
            $table->string('numero_attestation_dpaf')->nullable()->after('attestation_dpaf_par');
            
            // Clés étrangères
            $table->foreign('attestation_structure_par')->references('id')->on('agents')->onDelete('set null');
            $table->foreign('attestation_dpaf_par')->references('id')->on('agents')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stages', function (Blueprint $table) {
            $table->dropForeign(['attestation_structure_par']);
            $table->dropForeign(['attestation_dpaf_par']);
            
            $table->dropColumn([
                'attestation_structure_generee',
                'attestation_structure_date',
                'attestation_structure_par',
                'attestation_dpaf_generee',
                'attestation_dpaf_date',
                'attestation_dpaf_par',
                'numero_attestation_dpaf'
            ]);
        });
    }
};
