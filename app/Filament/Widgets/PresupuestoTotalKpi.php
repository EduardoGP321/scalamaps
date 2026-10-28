<?php

namespace App\Filament\Widgets;

use App\Models\Tramo;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class PresupuestoTotalKpi extends BaseWidget
{
    protected static ?string $widgetId = 'presupuestoTotalKpi';
    protected ?string $pollingInterval = null;

    public ?int $contratoId = null;
    public ?int $tramoId    = null;
    protected int | string | array $columnSpan = 'full';

    #[On('filtrosDashboardActualizados')]
    public function actualizarFiltros(?int $contratoId = null, ?int $tramoId = null): void
    {
        $this->contratoId = $contratoId;
        $this->tramoId    = $tramoId;

        // Forzamos a Livewire a ejecutar de nuevo la vista y recalcular getStats()
        $this->render();
    }

    protected function getStats(): array
    {
        $tramoIds = $this->resolveTramoIds($this->contratoId, $this->tramoId);

        $presupuestoTotal = DB::table('activo_concepto')
            ->whereIn('activo_id', function ($q) use ($tramoIds) {
                $q->select('id')
                    ->from('activos')
                    // Filtramos estrictamente por el listado de tramos calculados
                    // Si el array viene vacío [0], la consulta de forma segura dará $0.00
                    ->whereIn('tramo_id', !empty($tramoIds) ? $tramoIds : [0]);
            })
            ->sum('importe');

        return [
            Stat::make(
                'Presupuesto Requerido Total',
                '$' . number_format($presupuestoTotal, 2, '.', ',') . ' MXN'
            )
                ->description('Costo estimado de inversión')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning')
                ->columnSpanFull(),
        ];
    }

    private function resolveTramoIds(?int $contratoId, ?int $tramoId): array
    {
        // Caso 1: Filtro de tramo específico activo
        if ($tramoId) {
            return [$tramoId];
        }

        // Caso 2: Filtro de contrato específico activo
        if ($contratoId) {
            return Tramo::where('contrato_id', $contratoId)->pluck('id')->toArray();
        }

        // Caso 3: Estado inicial (Ningún filtro seleccionado)
        // Obtenemos única y exclusivamente los contratos que el usuario logueado tiene asignados
        $userContratoIds = auth()->user()->contratos()->pluck('contratos.id')->toArray();

        if (!empty($userContratoIds)) {
            return Tramo::whereIn('contrato_id', $userContratoIds)->pluck('id')->toArray();
        }

        // Si el usuario no cuenta con contratos asignados, devolvemos un array vacío
        return [];
    }
}
