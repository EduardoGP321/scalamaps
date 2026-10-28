<?php

namespace App\Filament\Widgets;

use Elemind\FilamentECharts\Widgets\EChartWidget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class TramoConceptoSankeyChart extends EChartWidget
{
    protected static ?string $chartId = 'tramoConceptoSankeyChart';
    protected static ?string $heading = 'Frecuencia de Conceptos Operativos por Tramo';

    public ?int $contratoId = null;
    public ?int $tramoId    = null;

    protected function getHeight(): ?string { return '500px'; }

    // 1. Inicialización idéntica a tu widget de materiales para la primera carga
    protected function init(): void
    {
        $this->options = $this->getOptions();
    }

    // 2. Escucha reactiva idéntica para actualizar al cambiar los filtros del Dashboard
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
        // 3. ENFOQUE NUEVO: Contamos repeticiones (COUNT) en lugar de sumar dinero
        $query = DB::table('activo_concepto')
            ->join('activos', 'activo_concepto.activo_id', '=', 'activos.id')
            ->join('tramos', 'activos.tramo_id', '=', 'tramos.id')
            ->join('catalogo_conceptos', 'activo_concepto.catalogo_concepto_id', '=', 'catalogo_conceptos.id')
            ->select(
                'tramos.nombre_tramo as tramo',
                'catalogo_conceptos.clave as clave_concepto',
                'catalogo_conceptos.concepto as descripcion_completa',
                DB::raw('COUNT(activo_concepto.id) as total_repeticiones')
            );

        if ($this->tramoId) {
            $query->where('tramos.id', $this->tramoId);
        } elseif ($this->contratoId) {
            $query->where('tramos.contrato_id', $this->contratoId);
        }

        $datosRaw = $query
            ->groupBy('tramos.nombre_tramo', 'catalogo_conceptos.clave', 'catalogo_conceptos.concepto')
            ->orderBy('total_repeticiones', 'desc')
            ->get();

        // Control para evitar que la gráfica truene si no hay datos en el filtro
        if ($datosRaw->isEmpty()) {
            return [
                'series' => [[
                    'type' => 'sankey',
                    'data' => [],
                    'links' => []
                ]]
            ];
        }

        $nodosTramos = $datosRaw->pluck('tramo')->unique()->values()->toArray();
        $nodosClaves = $datosRaw->pluck('clave_concepto')->unique()->values()->toArray();

        $nodes = [];
        foreach ($nodosTramos as $t) { $nodes[] = ['name' => $t]; }
        foreach ($nodosClaves  as $c) { $nodes[] = ['name' => $c]; }

        $links = [];
        $descripcionesMapeadas = [];

        foreach ($datosRaw as $linea) {
            $repeticiones = (int) $linea->total_repeticiones;

            if ($repeticiones > 0) {
                // Guardamos la descripción larga asociada a la clave para el tooltip en JS
                $descripcionesMapeadas[$linea->clave_concepto] = $linea->descripcion_completa;

                $links[] = [
                    'source' => $linea->tramo,
                    'target' => $linea->clave_concepto,
                    'value'  => $repeticiones, // El grosor del flujo representa la frecuencia de veces repetida
                ];
            }
        }

        // Convertimos las descripciones a JSON seguro para inyectar en el string de JavaScript
        $descripcionesJson = json_encode($descripcionesMapeadas, JSON_HEX_APOS | JSON_HEX_QUOT);

        return [
            'tooltip' => [
                'trigger' => 'item',
                'triggerOn' => 'mousemove'
            ],
            'series'  => [[
                'type'      => 'sankey',
                'layout'    => 'none',
                'emphasis'  => ['focus' => 'adjacency'],
                'nodeGap'   => 18,
                'nodeWidth' => 20,
                'itemStyle' => ['borderWidth' => 1, 'borderColor' => '#333'],
                'lineStyle' => ['color' => 'gradient', 'curveness' => 0.5, 'opacity' => 0.3],
                'label'     => ['color' => '#FFFFFF', 'fontFamily' => 'sans-serif', 'fontSize' => 11, 'fontWeight' => 'bold'],
                'data'      => $nodes,
                'links'     => $links,
                // SOLUCIÓN EXCEPCIÓN LIVEWIRE: Formateador escrito como string de JS puro
                'tooltip'   => [
                    'formatter' => "function (params) {
                        if (params.dataType === 'edge') {
                            var descripciones = {$descripcionesJson};
                            var clave = params.data.target;
                            var tramo = params.data.source;
                            var valor = params.data.value;
                            var descCompleta = descripciones[clave] || '';

                            // Ajustamos el texto largo para que no rompa el diseño del dashboard
                            var descFormateada = descCompleta.match(/.{1,60}(\s|$)/g)?.join('<br />') || descCompleta;

                            return '<b>Tramo:</b> ' + tramo + '<br/>' +
                                   '<b>Clave:</b> <span style=\"color:#F7BE00;font-weight:bold;\">' + clave + '</span><br/>' +
                                   '<b>Descripción:</b><br/><span style=\"font-size:11px;color:#333;\">' + descFormateada + '</span><br/><br/>' +
                                   '<b>Frecuencia:</b> <span style=\"color:#ED7C5B;font-weight:bold;\">' + valor + ' veces registrado</span>';
                        }
                        return params.name;
                    }"
                ]
            ]],
        ];
    }
}
