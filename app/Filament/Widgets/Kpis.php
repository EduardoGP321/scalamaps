<?php

namespace App\Filament\Widgets;

use App\Models\Tramo;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class Kpis extends BaseWidget
{
    protected static ?string $widgetId = 'Kpis';
    protected ?string $pollingInterval = null;

    public ?int $contratoId = null;
    public ?int $tramoId    = null;

    protected int | string | array $columnSpan = 'full';

    #[On('filtrosDashboardActualizados')]
    public function actualizarFiltros(?int $contratoId = null, ?int $tramoId = null): void
    {
        $this->contratoId = $contratoId;
        $this->tramoId    = $tramoId;
    }

    protected function getStats(): array
    {
        $tramoIds = $this->resolveTramoIds($this->contratoId, $this->tramoId);

        $totalObrasQuery = DB::table('obras_menores')
            ->join('activos', 'obras_menores.activo_id', '=', 'activos.id');

        $atencionCriticaQuery = DB::table('activos')
            ->whereRaw('LOWER(condicion) = ?', ['regular']);

        $totalActivosQuery = DB::table('activos')
            ->whereNotNull('condicion');

        $activosSanosQuery = DB::table('activos')
            ->whereIn(DB::raw('LOWER(condicion)'), ['excelente', 'bueno', 'aceptable']);

        if ($tramoIds !== null) {
            $totalObrasQuery->whereIn('activos.tramo_id', $tramoIds);
            $atencionCriticaQuery->whereIn('tramo_id', $tramoIds);
            $totalActivosQuery->whereIn('tramo_id', $tramoIds);
            $activosSanosQuery->whereIn('tramo_id', $tramoIds);
        }

        $totalObras      = $totalObrasQuery->count();
        $totalTramos     = $tramoIds !== null ? count($tramoIds) : DB::table('tramos')->count();
        $atencionCritica = $atencionCriticaQuery->count();
        $totalActivos    = $totalActivosQuery->count();
        $activosSanos    = $activosSanosQuery->count();

        $porcentajeSalud = $totalActivos > 0
            ? round(($activosSanos / $totalActivos) * 100, 1)
            : 100;

        if ($porcentajeSalud >= 80) {
            $saludColor = 'success';
            $saludDesc  = 'Infraestructura estable';
        } elseif ($porcentajeSalud >= 60) {
            $saludColor = 'warning';
            $saludDesc  = 'Requiere mantenimiento preventivo';
        } else {
            $saludColor = 'danger';
            $saludDesc  = 'Deterioro crítico detectado';
        }

        return [
            Stat::make('obras_registradas', number_format($totalObras))
                ->label(new HtmlString('<span class="text-lg font-bold text-amber-400">Obras Registradas</span>'))
                ->description('Intervenciones totales en campo')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('info'),

            Stat::make('Tramos', number_format($totalTramos))
                ->label(new HtmlString('<span class="text-lg font-bold text-amber-400">Tramos</span>'))
                ->description('Seleccionados')
                ->descriptionIcon('heroicon-m-map')
                ->color('gray'),

            Stat::make('Atención Crítica', number_format($atencionCritica))
                ->label(new HtmlString('<span class="text-lg font-bold text-amber-400">Requieren Atención</span>'))
                ->description('Activos en estado Regular')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($atencionCritica > 0 ? 'danger' : 'success'),

            Stat::make('Salud Global', $porcentajeSalud . '%')
                ->label(new HtmlString('<span class="text-lg font-bold text-amber-400">Salud Global</span>'))
                ->description($saludDesc)
                ->descriptionIcon('heroicon-m-heart')
                ->color($saludColor)
                ->chart([
                    $porcentajeSalud,
                    $porcentajeSalud - 5,
                    $porcentajeSalud + 2,
                    $porcentajeSalud,
                ]),
        ];
    }

    private function resolveTramoIds(?int $contratoId, ?int $tramoId): array
    {
        // Caso 1: Filtro de tramo específico activo en el Dashboard
        if ($tramoId) {
            return [$tramoId];
        }

        // Caso 2: Filtro de contrato específico activo en el Dashboard
        if ($contratoId) {
            return Tramo::where('contrato_id', $contratoId)->pluck('id')->toArray();
        }

        // Caso 3: Estado inicial (Ningún filtro seleccionado)
        // Obtenemos única y exclusivamente los contratos que el usuario logueado tiene asignados
        $userContratoIds = auth()->user()->contratos()->pluck('contratos.id')->toArray();

        if (!empty($userContratoIds)) {
            return Tramo::whereIn('contrato_id', $userContratoIds)->pluck('id')->toArray();
        }

        // Si el usuario no tiene contratos asignados, retornamos vacío para que las queries den 0 y no muestren nada global
        return [];
    }
}
