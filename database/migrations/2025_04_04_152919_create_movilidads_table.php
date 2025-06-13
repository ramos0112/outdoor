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
        Schema::create('movilidads', function (Blueprint $table) {
            $table->id('id_movilidad');
            $table->string('ruta');
            $table->string('tipo_movilidad');
            $table->integer('capacidad');
            $table->enum('estado', ['Disponible', 'Ocupado'])->default('Disponible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movilidads');
    }
};
