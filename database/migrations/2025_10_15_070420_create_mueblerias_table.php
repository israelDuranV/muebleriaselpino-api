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
        Schema::create('mueblerias', function (Blueprint $table) {
            $table->integer('mueblerias_id', true);
            $table->string('nombre', 180)->nullable();
            $table->string('tipo', 15)->nullable();
            $table->integer('direccion_id')->nullable()->index('direccion_index');
            $table->integer('fotografia_id')->nullable()->index('foto_index');
            $table->integer('estatus')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mueblerias');
    }
};
