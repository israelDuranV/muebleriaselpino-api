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
        Schema::create('direccion', function (Blueprint $table) {
            $table->integer('direccion_id', true);
            $table->string('calle', 50)->nullable();
            $table->integer('numero')->nullable();
            $table->string('colonia', 40)->nullable();
            $table->string('municipio', 50)->nullable();
            $table->string('estado', 50)->nullable();
            $table->integer('cp')->nullable();
            $table->double('longitud')->nullable();
            $table->double('latitud')->nullable();
            $table->integer('fotografia_id')->nullable()->index('foto_index');
            $table->string('referencia', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direccion');
    }
};
