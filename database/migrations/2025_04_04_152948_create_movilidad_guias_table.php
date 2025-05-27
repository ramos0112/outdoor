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
        Schema::create('movilidad_guias', function (Blueprint $table) {
            $table->foreignId('id_movilidad')->constrained('movilidads', 'id_movilidad')->onDelete('cascade');
            $table->foreignId('id_guia')->constrained('guias', 'id_guia')->onDelete('cascade');
            $table->primary(['id_movilidad', 'id_guia']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movilidad_guias');
    }
};
