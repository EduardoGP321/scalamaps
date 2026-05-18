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
        Schema::create('tramos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrato_id')->constrained()->onDelete('cascade');
            $table->foreignId('carretera_id')->constrained()->onDelete('cascade');
            $table->string('nombre_tramo');

            $table->decimal('latitud_inicio', 10, 8)->nullable();
            $table->decimal('longitud_inicio', 11, 8)->nullable();
            $table->decimal('latitud_fin', 10, 8)->nullable();
            $table->decimal('longitud_fin', 11, 8)->nullable();
            $table->text('polyline_google')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tramos');
    }
};
