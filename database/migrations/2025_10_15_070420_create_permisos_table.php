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
        Schema::create('permisos', function (Blueprint $table) {
            $table->integer('permisos_id', true);
            $table->integer('roles_id')->nullable()->index('roles_index');
            $table->integer('modulos_id')->nullable()->index('modulos_index');
            $table->integer('editar')->nullable();
            $table->integer('ver')->nullable();
            $table->integer('insertar');
            $table->integer('eliminar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permisos');
    }
};
