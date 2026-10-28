<?php

namespace App\Services;

use App\Filament\Exports\ObraMenorExporter;
use App\Models\TipoActivo;
use App\Models\ObraMenor;
use Filament\Actions\ExportAction;
use Illuminate\Support\Facades\Auth;

class ReportExportFactory
{
    public static function export(?int $tipoActivoId, array $reportData)
    {
        // 1. Extraemos los IDs de los activos que arrojó tu ReportEngineService
        $activoIds = collect($reportData['activos'] ?? [])->pluck('id')->toArray();

        // 2. Si no seleccionaron un tipo de activo específico
        if (! $tipoActivoId) {
            return session()->flash('warning', 'Seleccione un tipo de activo específico para exportar.');
        }

        $slug = TipoActivo::where('id', $tipoActivoId)->value('slug');

        // 3. Inicializamos la query de los registros que queremos exportar
        $query = ObraMenor::whereIn('activo_id', $activoIds);

        // 4. Despachamos la exportación con la API nativa de Filament v5
        match ($slug) {
            'obra-menor', 'alcantarilla' => ExportAction::make()
                ->exporter(ObraMenorExporter::class)
                ->modifyQueryUsing(fn() => $query)
                ->user(Auth::user())
                ->options([
                    'contrato_nombre' => $reportData['info']['nombre'] ?? 'Contrato',
                ])
                ->dispatch(), // Dispara el Job nativo de Filament v5 sin errores de linter

            default => session()->flash('error', 'No hay un exportador configurado para este tipo de activo.'),
        };

        return session()->flash('success', 'La exportación por lotes ha comenzado en segundo plano. Recibirás una notificación en la campana cuando esté lista.');
    }
}
