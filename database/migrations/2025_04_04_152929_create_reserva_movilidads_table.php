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
        Schema::create('reserva_movilidads', function (Blueprint $table) {
            $table->foreignId('id_reserva')->constrained('reservas', 'id_reserva');
            $table->foreignId('id_movilidad')->constrained('movilidads', 'id_movilidad');
            $table->primary(['id_reserva', 'id_movilidad']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva_movilidads');
    }
};
