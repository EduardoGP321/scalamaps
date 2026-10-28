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
        Schema::create('dependencia_tipo_activo', function (Blueprint $table) {
            $table->id();

            $table->foreignId('dependencia_id')
                ->constrained('dependencias')
                ->cascadeOnDelete();
            $table->foreignId('tipo_activo_id')
                ->constrained('tipos_activos')
                ->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['dependencia_id', 'tipo_activo_id'], 'dep_activo_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dependencia_tipo_activo');
    }
};
