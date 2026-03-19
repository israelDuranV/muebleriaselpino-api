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
        // Migración de muebles
        Schema::dropIfExists('muebles');
        Schema::create('muebles', function (Blueprint $table) {
            $table->id('muebles_id'); // BIGINT UNSIGNED PRIMARY KEY
            $table->string('nombre', 150)->nullable();
            $table->integer('materiales_id')->nullable()->index('material_index');
            $table->integer('terminado_id')->nullable()->index('terminado_index');
            $table->integer('departamento_id')->nullable()->index('departamento_index');
            $table->integer('sincera')->nullable();
            $table->integer('encerado')->nullable();
            $table->integer('costo')->nullable();
            $table->integer('barniz')->nullable();
            $table->string('fotografia', 3000)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muebles');
    }
};
