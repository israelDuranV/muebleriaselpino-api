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
        Schema::table('muebles', function (Blueprint $table) {
            $table->foreign(['materiales_id'], 'muebles_ibfk_1')->references(['materiales_id'])->on('materiales')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['terminado_id'], 'muebles_ibfk_2')->references(['terminado_id'])->on('terminado')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['departamento_id'], 'muebles_ibfk_3')->references(['departamento_id'])->on('departamentos')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('muebles', function (Blueprint $table) {
            $table->dropForeign('muebles_ibfk_1');
            $table->dropForeign('muebles_ibfk_2');
            $table->dropForeign('muebles_ibfk_3');
        });
    }
};
