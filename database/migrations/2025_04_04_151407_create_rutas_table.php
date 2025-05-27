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
        Schema::create('rutas', function (Blueprint $table) {
            $table->id('id_ruta'); // id_ruta como clave primaria
            $table->string('nombre_ruta');
            $table->text('descripcion_general')->nullable();
            $table->string('tipo')->nullable();
            $table->decimal('precio_regular', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('precio_actual', 10, 2);
            $table->string('dificultad')->nullable();
            $table->string('estado')->default('Activo')->checkIn(['Activo', 'Inactivo']);
            $table->timestamps();
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rutas');
    }
};
