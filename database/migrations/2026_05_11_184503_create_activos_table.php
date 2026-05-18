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
Schema::create('activos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tramo_id')->constrained()->onDelete('cascade');
    $table->foreignId('tipo_activo_id')->constrained('tipos_activos');
    $table->string('kilometraje');

    // Punto de referencia para el marcador (Pin) del mapa
    $table->decimal('latitud', 10, 8);
    $table->decimal('longitud', 11, 8);

    $table->enum('lado', ['Izquierdo', 'Derecho', 'Centro', 'Ambos'])->default('Centro');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activos');
    }
};
