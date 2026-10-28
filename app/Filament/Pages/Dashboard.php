<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CondicionActivoChart;
use App\Filament\Widgets\Kpis;
use App\Filament\Widgets\MayorInversionChart;
use App\Filament\Widgets\TramoConceptoSankeyChart;
use App\Filament\Widgets\TramoCondicionChart;
use App\Filament\Widgets\TramoMaterialChart;
use App\Filament\Widgets\PresupuestoTotalKpi;
use App\Filament\Widgets\RealizoSupervision;
use App\Models\Contrato;
use App\Models\Tramo;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChartPie;

    protected static ?string $navigationLabel = 'Dashboard';
    protected string $view = 'filament.pages.dashboard';

    public ?string $contrato_id = null;
    public ?string $tramo_id    = null;

    public function getContratos(): array
    {
        return Contrato::query()
            ->select([
                'id',
                DB::raw("CONCAT(nombre_corto, ' (', YEAR(fecha_inicio), ')') as nombre_con_anio")
            ])
            ->orderBy('nombre_corto')
            ->pluck('nombre_con_anio', 'id')
            ->toArray();
    }

    public function getTramos(): array
    {
        // Si no hay contrato seleccionado, no consultamos nada y regresamos vacío
        if (empty($this->contrato_id)) {
            return [];
        }

        return Tramo::query()
            ->where('contrato_id', $this->contrato_id)
            ->orderBy('nombre_tramo')
            ->pluck('nombre_tramo', 'id')
            ->toArray();
    }

    public function updatedContratoId(): void
    {
        $this->tramo_id = null;
    }

    public function aplicarFiltros(): void
    {
        $this->dispatch(
            'filtrosDashboardActualizados',
            contratoId: $this->contrato_id ? (int) $this->contrato_id : null,
            tramoId: $this->tramo_id    ? (int) $this->tramo_id    : null,
        );
    }

    public function limpiarFiltros(): void
    {
        $this->contrato_id = null;
        $this->tramo_id    = null;

        $this->dispatch(
            'filtrosDashboardActualizados',
            contratoId: null,
            tramoId: null,
        );
    }

    protected function getWidgets(): array
    {
        return [
            RealizoSupervision::class,
            Kpis::class,
            MayorInversionChart::class,
            TramoMaterialChart::class,
            TramoCondicionChart::class,
            CondicionActivoChart::class,
            PresupuestoTotalKpi::class,
        ];
    }
}
