<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifier l'énumération pour ajouter le rôle SR (Sans Rôle)
        DB::statement("ALTER TABLE agents MODIFY COLUMN role_agent ENUM('DPAF', 'MS', 'RS', 'SR') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir à l'énumération d'origine
        // Note: Cela pourrait causer des erreurs si des agents ont déjà le rôle SR
        DB::statement("ALTER TABLE agents MODIFY COLUMN role_agent ENUM('DPAF', 'MS', 'RS') NULL");
    }
};
