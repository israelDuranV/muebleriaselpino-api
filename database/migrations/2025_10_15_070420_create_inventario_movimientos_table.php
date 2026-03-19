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
        Schema::create('inventario_movimientos', function (Blueprint $table) {
            $table->integer('inventarios_id', true);
            $table->integer('muebles_id')->nullable();
            $table->integer('pedido_id')->nullable();
            $table->integer('codigo_pedido')->nullable();
            $table->date('fecha_produccion')->nullable();
            $table->string('descripcion', 300)->nullable();
            $table->date('fecha_entrega')->nullable();
            $table->integer('estatus')->nullable();
            $table->integer('usuario_id')->nullable();
            $table->integer('procedencia')->nullable();
            $table->integer('muebleria_id')->nullable();
            $table->date('fecha_comienzo')->nullable();
            $table->date('fecha_termino')->nullable();
            $table->date('fecha_traspaso')->nullable();
            $table->integer('usuario_acepta')->nullable()->default(0);
            $table->integer('aceptado')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_movimientos');
    }
};
