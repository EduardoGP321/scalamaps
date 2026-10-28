<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Primero migrar los datos existentes a la tabla pivot
        $contratos = DB::table('contratos')->whereNotNull('carretera_id')->get();
        foreach ($contratos as $contrato) {
            DB::table('contrato_carretera')->insertOrIgnore([
                'contrato_id'  => $contrato->id,
                'carretera_id' => $contrato->carretera_id,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        // 2. Eliminar la FK y la columna obsoleta
        Schema::table('contratos', function (Blueprint $table) {
            $table->dropForeign(['carretera_id']);
            $table->dropColumn('carretera_id');
        });
    }

    public function down(): void
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->foreignId('carretera_id')
                ->nullable()
                ->constrained('carreteras')
                ->onDelete('cascade');
        });

        // Restaurar datos desde la pivot (solo primer registro por contrato)
        $pivots = DB::table('contrato_carretera')
            ->select('contrato_id', DB::raw('MIN(carretera_id) as carretera_id'))
            ->groupBy('contrato_id')
            ->get();

        foreach ($pivots as $pivot) {
            DB::table('contratos')
                ->where('id', $pivot->contrato_id)
                ->update(['carretera_id' => $pivot->carretera_id]);
        }
    }
};
