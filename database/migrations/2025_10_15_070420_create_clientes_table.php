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
        Schema::create('clientes', function (Blueprint $table) {
            $table->integer('clientes_id', true);
            $table->string('nombres', 50)->nullable();
            $table->string('paterno', 30)->nullable();
            $table->string('materno', 30)->nullable();
            $table->string('tel_local', 30)->nullable();
            $table->string('celular', 30)->nullable();
            $table->string('comprobante_domicilio', 200)->nullable();
            $table->string('comprobante_ine', 200)->nullable();
            $table->string('comprobante_croquis', 200)->nullable();
            $table->date('fecha_alta')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('email', 50)->nullable();
            $table->string('fotografia', 150)->nullable();
            $table->integer('mueblerias_id')->nullable()->index('muebleria_index');
            $table->integer('usuarios_id')->nullable()->index('usuario_index');
            $table->integer('direccion_id')->nullable()->index('direccion_index');
            $table->string('observaciones', 100)->nullable();
            $table->integer('calificacion')->nullable();
            $table->integer('acepta_promociones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
