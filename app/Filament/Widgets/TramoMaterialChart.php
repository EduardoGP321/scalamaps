<?php

namespace App\Filament\Widgets;

use App\Models\Tramo;
use Elemind\FilamentECharts\Widgets\EChartWidget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class TramoMaterialChart extends EChartWidget
{
    protected static ?string $chartId = 'tramoMaterialChart';
    protected static ?string $heading = 'Inversión por Tramo y Tipo de Material';

    public ?int $contratoId = null;
    public ?int $tramoId    = null;

    protected int | string | array $columnSpan = [
        'sm' => 'full',
        'md' => 2,
        'xl' => 2, // Ocupan la mitad de la pantalla (2 de 4 columnas) lado a lado
    ];

    protected function getHeight(): ?string
    {
        return '520px';
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
        // 1. Resolvemos el universo de tramos según el estado de los filtros
        $tramoIds = [];

        if ($this->tramoId) {
            $tramoIds = [$this->tramoId];
        } elseif ($this->contratoId) {
            $tramoIds = Tramo::where('contrato_id', $this->contratoId)->pluck('id')->toArray();
        } else {
            // Estado inicial: contratos asignados al usuario autenticado
            $userContratoIds = auth()->user()->contratos()->pluck('contratos.id')->toArray();
            if (!empty($userContratoIds)) {
                $tramoIds = Tramo::whereIn('contrato_id', $userContratoIds)->pluck('id')->toArray();
            }
        }

        // Si el usuario no tiene tramos válidos asignados, evitamos consultar toda la base de datos
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

        // 2. Ejecutamos la consulta restringida a los tramos calculados
        $datosRaw = DB::table('activos')
            ->join('obras_menores', 'activos.id', '=', 'obras_menores.activo_id')
            ->join('activo_concepto', 'activos.id', '=', 'activo_concepto.activo_id')
            ->join('tramos', 'activos.tramo_id', '=', 'tramos.id')
            ->select(
                'tramos.nombre_tramo as tramo',
                'obras_menores.material as material',
                DB::raw('SUM(activo_concepto.importe) as total_inversion')
            )
            ->whereNotNull('obras_menores.material')
            ->whereIn('activos.tramo_id', $tramoIds)
            ->groupBy('tramos.nombre_tramo', 'obras_menores.material')
            ->get();

        $tramos     = $datosRaw->pluck('tramo')->unique()->values()->toArray();
        $materiales = $datosRaw->pluck('material')->unique()->values()->toArray();

        // Conservamos los nombres de los tramos que se van a pintar para limpiar el eje Y de residuos
        $categories = !empty($tramos) ? $tramos : ['Sin datos registrados'];

        if (empty($tramos)) {
            $tramos = ['Sin datos registrados'];
        }

        $header = ['product'];
        foreach ($materiales as $m) {
            $header[] = $m;
            $header[] = $m . '_txt';
        }

        $datasetSource = [$header];
        foreach ($tramos as $tramo) {
            $fila = [$tramo];
            foreach ($materiales as $material) {
                $r      = $datosRaw->where('tramo', $tramo)->where('material', $material)->first();
                $monto  = $r ? (float) $r->total_inversion : 0.0;
                $fila[] = $monto;
                $fila[] = $r ? '$' . number_format($monto, 2, '.', ',') : '';
            }
            $datasetSource[] = $fila;
        }

        $series = [];
        $col    = 1;
        foreach ($materiales as $material) {
            $series[] = [
                'name'   => ucfirst($material),
                'type'   => 'bar',
                'encode' => ['x' => $col, 'y' => 0],
                'label'  => ['show' => true, 'position' => 'right', 'fontWeight' => 'bold', 'fontFamily' => 'sans-serif', 'fontSize' => 11, 'color' => '#FFFFFF', 'formatter' => '{@[' . ($col + 1) . ']}'],
            ];
            $col += 2;
        }

        return [
            'legend'  => ['data' => array_map('ucfirst', $materiales), 'textStyle' => ['color' => '#FFFFFF']],
            'dataset' => ['source' => $datasetSource],
            'grid'    => ['top' => 50, 'bottom' => 40, 'left' => 20, 'right' => 40, 'containLabel' => true],
            'xAxis'   => ['type' => 'value', 'axisLabel' => ['color' => '#FFFFFF', 'fontFamily' => 'sans-serif', 'formatter' => '${value}'], 'splitLine' => ['lineStyle' => ['color' => '#333333', 'type' => 'dashed']]],
            'yAxis'   => [
                'type' => 'category',
                'inverse' => true,
                'data' => $categories, // Forzar la actualización limpia del listado de tramos
                'axisLabel' => ['color' => '#FFFFFF', 'fontSize' => 12, 'fontWeight' => 'bold', 'fontFamily' => 'sans-serif']
            ],
            'series'  => $series,
        ];
    }
}
