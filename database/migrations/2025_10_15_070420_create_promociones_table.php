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
        Schema::create('promociones', function (Blueprint $table) {
            $table->integer('reditos_id', true);
            $table->integer('contado')->nullable()->default(0);
            $table->integer('contado_barn')->nullable()->default(0);
            $table->integer('mes')->nullable()->default(0);
            $table->integer('tres_meses')->nullable()->default(0);
            $table->integer('seis_meses')->nullable()->default(0);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_final')->nullable();
            $table->string('temporada', 200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promociones');
    }
};
