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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->integer('asistencias_id', true);
            $table->string('tipo', 11)->nullable();
            $table->dateTime('fecha')->nullable();
            $table->date('dia')->nullable();
            $table->double('latitud')->nullable();
            $table->double('longitud')->nullable();
            $table->integer('empleado')->nullable();
            $table->integer('muebleria')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
