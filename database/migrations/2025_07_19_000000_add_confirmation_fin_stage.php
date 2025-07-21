<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stages', function (Blueprint $table) {
            $table->boolean('termine_par_ms')->default(false);
            $table->timestamp('date_confirmation_ms')->nullable();
        });
    }

    public function down()
    {
        Schema::table('stages', function (Blueprint $table) {
            $table->dropColumn(['termine_par_ms', 'date_confirmation_ms']);
        });
    }
}; 