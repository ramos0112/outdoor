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
        Schema::create('detalle_rutas', function (Blueprint $table) {
            $table->id('id_detalle'); // id_detalle como clave primaria
            $table->foreignId('id_ruta')->constrained('rutas', 'id_ruta')->onDelete('cascade');
            $table->text('descripcion')->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_rutas');
    }
};
