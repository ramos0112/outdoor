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
        Schema::create('servicio_incluidos', function (Blueprint $table) {
            $table->id('id_servicio');
            $table->foreignId('id_ruta')->constrained('rutas', 'id_ruta')->onDelete('cascade');
            $table->string('servicio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio_incluidos');
    }
};
