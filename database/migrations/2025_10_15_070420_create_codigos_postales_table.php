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
        Schema::create('codigos_postales', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('codigoPostal')->nullable();
            $table->string('estado', 300)->nullable();
            $table->string('municipio', 300)->nullable();
            $table->string('ciudad', 300)->nullable();
            $table->string('tipoAsentamiento', 300)->nullable();
            $table->string('asentamiento', 300)->nullable();
            $table->integer('claveOficina')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codigos_postales');
    }
};
