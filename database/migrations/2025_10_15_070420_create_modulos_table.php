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
        Schema::create('modulos', function (Blueprint $table) {
            $table->integer('modulos_id', true);
            $table->integer('menu_id');
            $table->string('modulo', 50)->nullable();
            $table->string('icono', 100);
            $table->string('label', 20)->nullable();
            $table->string('url', 100);
            $table->string('description', 150)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modulos');
    }
};
