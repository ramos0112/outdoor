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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('id_cliente');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('tipo_documento', 15);
            $table->string('numero_documento',15);
            $table->date('fecha_nacimiento');
            $table->string('email') ->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('pais', 50)->nullable();
            $table->string('region', 50)->nullable();
            $table->string('ciudad', 50)->nullable();
            $table->timestamps();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
