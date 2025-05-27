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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id('id_reserva');
            $table->foreignId('id_fecha')->constrained('fecha_disponibles', 'id_fecha')->onDelete('cascade');
            $table->dateTime('fecha_reserva');
            $table->integer('cantidad_personas');
            $table->decimal('precio_total', 10, 2);
            $table->decimal('saldo', 10, 2);
            $table->enum('estado', ['Pendiente', 'Pagado', 'Abordo', 'Cancelado'])->default('Pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
