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
        Schema::create('catalogo_conceptos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parte_obra_id')->constrained('partes_obra')->onDelete('cascade');
            $table->foreignId('partida_id')->constrained('partidas')->onDelete('cascade');

            $table->string('clave'); // Ej: 1.1.1
            $table->text('descripcion');
            $table->string('unidad'); // Ej: m3, m2
            $table->decimal('precio_unitario', 18, 4);

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_conceptos');
    }
};
