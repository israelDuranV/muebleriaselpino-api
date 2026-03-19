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
        Schema::create('nominas', function (Blueprint $table) {
            $table->integer('nominas_id', true);
            $table->integer('usuarios_id')->nullable()->index('usuario_index');
            $table->date('fecha_corte')->nullable();
            $table->date('fecha_final')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nominas');
    }
};
