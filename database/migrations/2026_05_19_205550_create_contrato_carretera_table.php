<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contrato_carretera', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrato_id')->constrained('contratos')->onDelete('cascade');
            $table->foreignId('carretera_id')->constrained('carreteras')->onDelete('cascade');
            $table->timestamps();

            // Evitar duplicados: un contrato no puede tener la misma carretera dos veces
            $table->unique(['contrato_id', 'carretera_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrato_carretera');
    }
};
