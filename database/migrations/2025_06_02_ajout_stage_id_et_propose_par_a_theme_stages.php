<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('theme_stages', function (Blueprint $table) {
            $table->unsignedBigInteger('stage_id')->nullable()->after('id');
            $table->string('propose_par')->default('stagiaire')->after('stage_id'); // 'stagiaire' ou 'ms'
            $table->foreign('stage_id')->references('id')->on('stages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theme_stages', function (Blueprint $table) {
            $table->dropForeign(['stage_id']);
            $table->dropColumn('stage_id');
            $table->dropColumn('propose_par');
        });
    }
}; 