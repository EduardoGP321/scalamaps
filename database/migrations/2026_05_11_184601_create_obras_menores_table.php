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
        Schema::create('obras_menores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activo_id')->unique()->constrained('activos')->onDelete('cascade');


            $table->enum('cuerpo', ['a', 'b', 'sobre_eje']);
            $table->string('area_hidraulica');
            $table->string('altura_terraplen_entrada');
            $table->string('altura_terraplen_salida');
            $table->string('longitud_estructura');
            $table->string('esviaje');
            $table->string('pendiente');

            // Características técnicas
            $table->string('subtipo'); // Ej: Tubo, Losa, Cajón
            $table->string('material'); // Ej: Concreto, Lámina
            $table->string('dimensiones'); // Ej: 1.20m x 1.20m
            $table->text('observaciones')->nullable();
            $table->enum('condicion', ['excelente', 'bueno', 'aceptable', 'regular']);

            $table->decimal('latitud_inicio', 10, 8)->nullable();
            $table->decimal('longitud_inicio', 11, 8)->nullable();
            $table->decimal('latitud_fin', 10, 8)->nullable();
            $table->decimal('longitud_fin', 11, 8)->nullable();

            // Las 2 Fotos Obligatorias
            $table->string('foto_entrada')->nullable();// Ruta del archivo
            $table->string('foto_salida')->nullable(); // Ruta del archivo

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obras_menores');
    }
};
