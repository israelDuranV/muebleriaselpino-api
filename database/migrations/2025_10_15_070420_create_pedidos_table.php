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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->integer('pedidos_id', true);
            $table->integer('muebles_id')->nullable();
            $table->integer('usuario_id')->nullable();
            $table->integer('mueblerias_id')->nullable();
            $table->integer('cantidad')->nullable();
            $table->integer('cantidad_inicial')->nullable();
            $table->date('fecha')->nullable();
            $table->string('descripcion', 300)->nullable();
            $table->date('fecha_entrega')->nullable();
            $table->integer('produccion')->nullable();
            $table->integer('id_venta')->nullable();
            $table->string('comprobante', 500)->nullable();
            $table->integer('codigo_pedido')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
