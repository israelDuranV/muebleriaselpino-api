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
        Schema::create('inventarios', function (Blueprint $table) {
            $table->integer('inventarios_id', true);
            $table->integer('muebles_id');
            $table->date('fecha')->nullable();
            $table->string('tipo', 20)->nullable();
            $table->integer('cantidad')->nullable();
            $table->integer('usuarios_id')->nullable()->index('usuario_index');
            $table->integer('mueblerias_id')->nullable()->index('muebleria_index');
            $table->string('comentario', 30)->nullable();
            $table->string('comienzo', 30)->nullable();
            $table->string('termino', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
