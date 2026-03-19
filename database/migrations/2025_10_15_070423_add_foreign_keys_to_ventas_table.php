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
        Schema::table('ventas', function (Blueprint $table) {
            $table->foreign(['mueblerias_id'], 'ventas_ibfk_1')->references(['mueblerias_id'])->on('mueblerias')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['usuarios_id'], 'ventas_ibfk_3')->references(['usuarios_id'])->on('usuarios')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['clientes_id'], 'ventas_ibfk_4')->references(['clientes_id'])->on('clientes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['tipo_pago_id'], 'ventas_ibfk_5')->references(['tipo_pago_id'])->on('tipo_pago')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign('ventas_ibfk_1');
            $table->dropForeign('ventas_ibfk_3');
            $table->dropForeign('ventas_ibfk_4');
            $table->dropForeign('ventas_ibfk_5');
        });
    }
};
