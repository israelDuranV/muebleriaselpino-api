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
        Schema::create('abonos_programados', function (Blueprint $table) {
            $table->integer('abonos_id', true);
            $table->integer('usuarios_id')->nullable();
            $table->date('fecha')->nullable();
            $table->integer('codigo_ventas')->nullable();
            $table->integer('pago')->nullable();
            $table->integer('pagado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abonos_programados');
    }
};
