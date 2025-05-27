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
        Schema::create('imagens', function (Blueprint $table) {
            $table->id('id_imagen');
            $table->foreignId('id_ruta')->constrained('rutas', 'id_ruta')->onDelete('cascade');
            $table->string('url_imagen', 500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagens');
    }
};
