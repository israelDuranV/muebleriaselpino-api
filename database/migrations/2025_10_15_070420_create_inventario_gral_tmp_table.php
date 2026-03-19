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
        Schema::create('inventario_gral_tmp', function (Blueprint $table) {
            $table->integer('inventarios_id')->nullable()->default(0);
            $table->integer('muebles_id')->nullable();
            $table->integer('pedido')->nullable();
            $table->integer('estatus')->nullable();
            $table->integer('usuario_id')->nullable();
            $table->integer('procedencia')->nullable();
            $table->integer('muebleria_id')->nullable();
            $table->bigInteger('cantidad')->default(0);
            $table->string('mueble', 150)->nullable();
            $table->string('descripcion', 1000)->nullable();
            $table->string('departamento', 20)->nullable();
            $table->string('tipo', 7)->default('');
            $table->string('usuario', 82)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_gral_tmp');
    }
};
