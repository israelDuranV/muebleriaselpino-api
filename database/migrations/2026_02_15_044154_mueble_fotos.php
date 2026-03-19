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
        Schema::dropIfExists('mueble_fotos');
        Schema::create('mueble_fotos', function (Blueprint $table) {
            $table->id();
            $table->string('url', 500);
            
            // Foreign key simplificada y segura
            $table->foreignId('muebles_id')
                  ->constrained('muebles', 'muebles_id')
                  ->onDelete('cascade');
            
            $table->integer('orden')->default(0)->comment('Orden de visualización de la foto');
            $table->string('descripcion', 200)->nullable();
            $table->timestamps();
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mueble_fotos');
    }
};

