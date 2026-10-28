<?php

namespace App\Filament\Widgets;

use App\Models\Tramo;
use Elemind\FilamentECharts\Widgets\EChartWidget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class CondicionActivoChart extends EChartWidget
{
    protected static ?string $chartId = 'condicionActivoChart';
    protected static ?string $heading = 'Condición Global de los Activos';

    public ?int $contratoId = null;
    public ?int $tramoId    = null;

    protected int | string | array $columnSpan = [
        'sm' => 'full',
        'md' => 2,
        'xl' => 2,
    ];

    #[On('filtrosDashboardActualizados')]
    public function actualizarFiltros(?int $contratoId = null, ?int $tramoId = null): void
    {
        $this->contratoId = $contratoId;
        $this->tramoId    = $tramoId;

        $this->options = $this->getOptions();
        $this->dispatch('updateOptions', options: $this->options)->self();
    }

    protected function getOptions(): array
    {
        // Resolvemos el universo de tramos autorizados
        $tramoIds = [];

        if ($this->tramoId) {
            $tramoIds = [$this->tramoId];
        } elseif ($this->contratoId) {
            $tramoIds = Tramo::where('contrato_id', $this->contratoId)->pluck('id')->toArray();
        } else {
            // Estado inicial: contratos del usuario autenticado
            $userContratoIds = auth()->user()->contratos()->pluck('contratos.id')->toArray();
            if (!empty($userContratoIds)) {
                $tramoIds = Tramo::whereIn('contrato_id', $userContratoIds)->pluck('id')->toArray();
            }
        }

        $datosRaw = DB::table('activos')
            ->select('condicion', DB::raw('count(*) as total'))
            ->whereNotNull('condicion')
            ->whereIn('tramo_id', !empty($tramoIds) ? $tramoIds : [0])
            ->groupBy('condicion')
            ->get();

        $colorMapping = [
            'excelente' => '#1b5e20',
            'bueno'     => '#4caf50',
            'aceptable' => '#ffeb3b',
            'regular'   => '#ff9800',
        ];

        $pieData    = [];
        $legendData = [];

        foreach ($datosRaw as $registro) {
            $condicionLower   = strtolower($registro->condicion);
            $nombreFormateado = ucfirst($registro->condicion);
            $legendData[]     = $nombreFormateado;
            $pieData[]        = [
                'value'     => $registro->total,
                'name'      => $nombreFormateado,
                'itemStyle' => ['color' => $colorMapping[$condicionLower] ?? '#757575'],
                'label'     => ['color' => '#FFFFFF'],
            ];
        }

        return [
            'grid' => ['top' => 20, 'bottom' => 60, 'left' => 20, 'right' => 120, 'containLabel' => true],
            'tooltip' => ['trigger' => 'item', 'formatter' => '{a}: <br/>{b}: {c} ({d}%)'],
            'legend' => [
                'orient'    => 'vertical',
                'left'      => 'left',
                'data'      => $legendData,
                'textStyle' => ['color' => '#FFFFFF', 'fontWeight' => 'bold'],
            ],
            'series' => [[
                'name'      => 'Condición',
                'type'      => 'pie',
                'radius'    => '80%',
                'data'      => !empty($pieData) ? $pieData : [['value' => 0, 'name' => 'Sin datos']],
                'label'     => ['show' => true, 'formatter' => '{b}: {d}%'],
                'emphasis'  => ['itemStyle' => ['shadowBlur' => 10, 'shadowOffsetX' => 0, 'shadowColor' => 'rgba(0,0,0,0.5)']],
            ]],
        ];
    }
}
