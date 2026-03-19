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
        Schema::table('direccion', function (Blueprint $table) {
            $table->foreign(['fotografia_id'], 'direccion_ibfk_1')->references(['fotografia_id'])->on('fotografia')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('direccion', function (Blueprint $table) {
            $table->dropForeign('direccion_ibfk_1');
        });
    }
};
