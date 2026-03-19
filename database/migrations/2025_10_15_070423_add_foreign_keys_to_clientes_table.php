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
        Schema::table('clientes', function (Blueprint $table) {
            $table->foreign(['mueblerias_id'], 'clientes_ibfk_1')->references(['mueblerias_id'])->on('mueblerias')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['usuarios_id'], 'clientes_ibfk_2')->references(['usuarios_id'])->on('usuarios')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['direccion_id'], 'clientes_ibfk_3')->references(['direccion_id'])->on('direccion')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign('clientes_ibfk_1');
            $table->dropForeign('clientes_ibfk_2');
            $table->dropForeign('clientes_ibfk_3');
        });
    }
};
