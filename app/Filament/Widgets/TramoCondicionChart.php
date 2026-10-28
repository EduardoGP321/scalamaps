<?php

namespace App\Filament\Widgets;

use App\Models\Tramo;
use Elemind\FilamentECharts\Widgets\EChartWidget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class TramoCondicionChart extends EChartWidget
{
    protected static ?string $chartId = 'tramoCondicionChart';
    protected static ?string $heading = 'Inversión por Tramo y Condición del Activo';

    public ?int $contratoId = null;
    public ?int $tramoId    = null;

    protected int | string | array $columnSpan = [
        'sm' => 'full',
        'md' => 2,
        'xl' => 2,
    ];

    protected function getHeight(): ?string
    {
        return '460px';
    }

    #[On('filtrosDashboardActualizados')]
    public function actualizarFiltros(?int $contratoId = null, ?int $tramoId = null): void
    {
        $this->contratoId = $contratoId;
        $this->tramoId    = $tramoId;
        $this->options    = $this->getOptions();
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
            $userContratoIds = auth()->user()->contracts ?? auth()->user()->contratos()->pluck('contratos.id')->toArray();
            if (!empty($userContratoIds)) {
                $tramoIds = Tramo::whereIn('contrato_id', $userContratoIds)->pluck('id')->toArray();
            }
        }

        if (empty($tramoIds)) {
            return [
                'legend'  => ['data' => [], 'textStyle' => ['color' => '#FFFFFF']],
                'dataset' => ['source' => [['product', 'Sin datos'], ['Sin datos registrados', 0]]],
                'grid'    => ['top' => 50, 'bottom' => 40, 'left' => 20, 'right' => 40, 'containLabel' => true],
                'xAxis'   => ['type' => 'value'],
                'yAxis'   => ['type' => 'category', 'data' => ['Sin datos registrados']],
                'series'  => [['type' => 'bar']]
            ];
        }

        $datosRaw = DB::table('activos')
            ->join('activo_concepto', 'activos.id', '=', 'activo_concepto.activo_id')
            ->join('tramos', 'activos.tramo_id', '=', 'tramos.id')
            ->select(
                'tramos.nombre_tramo as tramo',
                'activos.condicion as condicion',
                DB::raw('SUM(activo_concepto.importe) as total_inversion')
            )
            ->whereNotNull('activos.condicion')
            ->whereIn('activos.tramo_id', $tramoIds)
            ->groupBy('tramos.nombre_tramo', 'activos.condicion')
            ->get();

        $tramos      = $datosRaw->pluck('tramo')->unique()->values()->toArray();
        $condiciones = $datosRaw->pluck('condicion')->unique()->values()->toArray();

        $categories = !empty($tramos) ? $tramos : ['Sin datos registrados'];
        if (empty($tramos)) {
            $tramos = ['Sin datos registrados'];
        }

        $header = ['product'];
        foreach ($condiciones as $c) {
            $header[] = $c;
            $header[] = $c . '_txt';
        }

        $datasetSource = [$header];
        foreach ($tramos as $tramo) {
            $fila = [$tramo];
            foreach ($condiciones as $condicion) {
                $r      = $datosRaw->where('tramo', $tramo)->where('condicion', $condicion)->first();
                $monto  = $r ? (float) $r->total_inversion : 0.0;
                $fila[] = $monto;
                $fila[] = $r ? '$' . number_format($monto, 2, '.', ',') : '';
            }
            $datasetSource[] = $fila;
        }

        $colorMapping = ['excelente' => '#1b5e20', 'bueno' => '#4caf50', 'aceptable' => '#ffeb3b', 'regular' => '#ff9800'];
        $series       = [];
        $col          = 1;

        foreach ($condiciones as $condicion) {
            $lower      = strtolower($condicion);
            $series[]   = [
                'name'      => ucfirst($condicion),
                'type'      => 'bar',
                'itemStyle' => ['color' => $colorMapping[$lower] ?? '#757575'],
                'encode'    => ['x' => $col, 'y' => 0],
                'label'     => [
                    'show'       => true,
                    'position'   => 'right',
                    'fontWeight' => 'bold',
                    'fontFamily' => 'sans-serif',
                    'fontSize'   => 11,
                    'color'      => '#FFFFFF',
                    'formatter'  => '{@[' . ($col + 1) . ']}',
                ],
            ];
            $col += 2;
        }

        return [
            'legend'  => ['data' => array_map('ucfirst', $condiciones), 'textStyle' => ['color' => '#FFFFFF']],
            'dataset' => ['source' => $datasetSource],
            'grid'    => ['top' => 50, 'bottom' => 40, 'left' => 20, 'right' => 40, 'containLabel' => true],
            'xAxis'   => ['type' => 'value', 'axisLabel' => ['color' => '#FFFFFF', 'fontFamily' => 'sans-serif', 'formatter' => '${value}'], 'splitLine' => ['lineStyle' => ['color' => '#333333', 'type' => 'dashed']]],
            'yAxis'   => [
                'type' => 'category',
                'inverse' => true,
                'data' => $categories,
                'axisLabel' => ['color' => '#FFFFFF', 'fontSize' => 12, 'fontWeight' => 'bold', 'fontFamily' => 'sans-serif']
            ],
            'series'  => $series,
        ];
    }
}
