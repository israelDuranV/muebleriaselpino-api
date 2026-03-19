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
        Schema::create('asignacion_muebleria', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('muebleria')->nullable();
            $table->integer('usuario')->nullable();
            $table->integer('estatus')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion_muebleria');
    }
};
