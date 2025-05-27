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
        Schema::create('reserva_clientes', function (Blueprint $table) {
            $table->foreignId('id_reserva')->constrained('reservas', 'id_reserva');
            $table->foreignId('id_cliente')->constrained('clientes', 'id_cliente');
            $table->primary(['id_reserva', 'id_cliente']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva_clientes');
    }
};
