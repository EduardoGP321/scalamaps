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
        Schema::create('carreteras', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->decimal('latitud_inicio', 10, 8);
            $table->decimal('longitud_inicio', 11, 8);
            $table->decimal('latitud_fin', 10, 8);
            $table->decimal('longitud_fin', 11, 8);
            $table->text('polyline_google');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carreteras');
    }
};
