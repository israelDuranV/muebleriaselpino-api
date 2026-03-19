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
        Schema::dropIfExists('user_muebleria');
        Schema::create('user_muebleria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedInteger('mueblerias_id')->constrained('mueblerias')->onDelete('cascade');
            $table->boolean('is_primary')->default(false)->comment('Indica si es la mueblería principal del usuario');
            $table->timestamps();
            $table->unique(['user_id', 'mueblerias_id']);
            $table->index('user_id');
            $table->index('mueblerias_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_muebleria');
    }
};
