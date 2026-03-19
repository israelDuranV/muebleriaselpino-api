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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->integer('usuarios_id', true);
            $table->string('usuario', 30)->nullable();
            $table->string('alias', 50)->nullable();
            $table->string('secret', 32)->nullable();
            $table->string('nombres', 40)->nullable();
            $table->string('paterno', 20)->nullable();
            $table->string('materno', 20)->nullable();
            $table->integer('telefono')->nullable();
            $table->integer('sueldo')->nullable();
            $table->string('nss', 15)->nullable();
            $table->string('curp', 30)->nullable();
            $table->string('cartilla', 20)->nullable();
            $table->string('licencia', 50)->nullable();
            $table->string('rfc', 16)->nullable();
            $table->string('estudios', 60)->nullable();
            $table->date('fecha_alta')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->integer('fotografia_id')->nullable()->index('foto_index');
            $table->string('fotografia', 100)->nullable();
            $table->string('email', 50)->nullable();
            $table->integer('mueblerias_id')->nullable()->index('muebleria_index');
            $table->integer('direccion_id')->nullable()->index('direccion_index');
            $table->integer('roles_id')->nullable()->index('rol_index');
            $table->integer('calificacion')->nullable();
            $table->string('comentario', 50)->nullable();
            $table->string('sobremi', 500)->nullable();
            $table->string('mueblerias', 100)->nullable();
            $table->integer('estatus')->nullable()->default(0);
            $table->integer('darkmode')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
