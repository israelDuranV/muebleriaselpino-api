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
        Schema::create('ventas', function (Blueprint $table) {
            $table->integer('ventas_id', true);
            $table->integer('mueblerias_id')->nullable()->index('muebleria_index');
            $table->integer('muebles_id')->nullable()->index('muebles_index');
            $table->integer('usuarios_id')->nullable()->index('usuario_index');
            $table->integer('clientes_id')->nullable()->index('cliente_index');
            $table->string('forma_pago', 100)->nullable();
            $table->string('tipo_pago', 100)->nullable();
            $table->integer('cantidad')->nullable();
            $table->string('color', 100)->nullable();
            $table->integer('precio')->nullable();
            $table->integer('descuento')->nullable();
            $table->integer('tipo_pago_id')->nullable()->index('tipo_pago_id');
            $table->date('fecha_venta')->nullable();
            $table->integer('comision')->nullable();
            $table->date('fecha_entrega')->nullable();
            $table->integer('primer_abono')->nullable();
            $table->integer('codigo_ventas')->nullable();
            $table->integer('pagado')->nullable()->default(0);

            $table->index(['clientes_id'], 'tipo_pago_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
