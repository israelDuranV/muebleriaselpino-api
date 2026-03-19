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
        Schema::table('nominas', function (Blueprint $table) {
            $table->foreign(['usuarios_id'], 'nominas_ibfk_1')->references(['usuarios_id'])->on('usuarios')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nominas', function (Blueprint $table) {
            $table->dropForeign('nominas_ibfk_1');
        });
    }
};
