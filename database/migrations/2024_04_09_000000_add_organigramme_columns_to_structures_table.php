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
            $table->foreignId('parent_id')->nullable()->constrained('structures')->onDelete('cascade');
            $table->integer('niveau')->default(0);
            $table->integer('ordre')->default(0);
           // $table->boolean('actif')->default(true);
            $table->string('type_structure')->nullable()->comment('Direction, Service, Département, etc.');
           // $table->string('code')->nullable()->comment('Code unique de la structure');
            //$table->string('responsable_name')->nullable()->comment('Nom du responsable actuel');
            //$table->date('date_creation')->nullable();
            //$table->date('date_modification')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('structures', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn([
                'parent_id',
                'niveau',
                'ordre',
                'actif',
                'type_structure',
                'code',
                'responsable_name',
                'date_creation',
                'date_modification'
            ]);
        });
    }
}; 