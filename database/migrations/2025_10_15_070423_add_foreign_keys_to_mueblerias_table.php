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
        Schema::table('mueblerias', function (Blueprint $table) {
            $table->foreign(['direccion_id'], 'mueblerias_ibfk_1')->references(['direccion_id'])->on('direccion')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['fotografia_id'], 'mueblerias_ibfk_2')->references(['fotografia_id'])->on('fotografia')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mueblerias', function (Blueprint $table) {
            $table->dropForeign('mueblerias_ibfk_1');
            $table->dropForeign('mueblerias_ibfk_2');
        });
    }
};
