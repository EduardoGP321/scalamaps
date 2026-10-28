<?php

namespace App\Filament\Widgets;

use App\Models\Tramo;
use Elemind\FilamentECharts\Widgets\EChartWidget;
use Livewire\Attributes\On;

class MayorInversionChart extends EChartWidget
{
    protected static ?string $chartId = 'mayorInversionChart';
    protected static ?string $heading = 'Análisis de Inversión por Tramo';

    public ?int $contratoId = null;
    public ?int $tramoId    = null;

    protected int | string | array $columnSpan = [
    'sm' => 'full',
    'md' => 2,
    'xl' => 2, // Ocupan la mitad de la pantalla (2 de 4 columnas) lado a lado
];

    #[On('filtrosDashboardActualizados')]
    public function actualizarFiltros(?int $contratoId = null, ?int $tramoId = null): void
    {
        $this->contratoId = $contratoId;
        $this->tramoId    = $tramoId;

        $this->options = $this->getOptions();
        $this->dispatch('updateOptions', options: $this->options)->self();
    }

    protected function getHeight(): ?string
    {
        return '460px';
    }

    protected function getOptions(): array
    {
        $query = Tramo::query()
            ->with('contrato')
            ->withSum('conceptos as total_inversion', 'importe');

        if ($this->tramoId) {
            $query->where('id', $this->tramoId);
        } elseif ($this->contratoId) {
            $query->where('contrato_id', $this->contratoId);
        } else {
            // Estado inicial: Acotar únicamente a los contratos asignados al usuario autenticado
            $userContratoIds = auth()->user()->contratos()->pluck('contratos.id')->toArray();
            $query->whereIn('contrato_id', !empty($userContratoIds) ? $userContratoIds : [0]);
        }

        $tramos = $query->orderBy('total_inversion', 'asc')
            ->get();

        $datasetSource = [
            ['score', 'amount', 'product', 'formatted_amount']
        ];

        $categories = [];

        foreach ($tramos as $tramo) {
            $montoPuro = (float) ($tramo->total_inversion ?? 0);
            $montoFormateado = '$' . number_format($montoPuro, 2, '.', ',');

            $datasetSource[] = [
                $montoPuro,
                $montoPuro,
                $tramo->nombre_tramo,
                $montoFormateado
            ];

            $categories[] = $tramo->nombre_tramo;
        }

        $maxInversion = $tramos->max('total_inversion') ?? 100000;
        $hasData = $tramos->count() > 0 && $maxInversion > 0;

        if ($maxInversion <= 0) {
            $maxInversion = 100000;
        }

        $options = [
            'dataset' => [
                'source' => $datasetSource
            ],
            'backgroundColor' => '#000000',
            'grid' => [
                'top' => 20,
                'bottom' => 60,
                'left' => 20,
                'right' => 120,
                'containLabel' => true
            ],
            'xAxis' => [
                'type' => 'value',
                'axisLabel' => ['color' => '#FFFFFF', 'fontFamily' => 'sans-serif']
            ],
            'yAxis' => [
                'type' => 'category',
                'data' => !empty($categories) ? $categories : ['Sin datos registrados'],
                'axisLabel' => ['color' => '#FFFFFF', 'fontSize' => 12, 'fontFamily' => 'sans-serif']
            ],
            'series' => [
                [
                    'type' => 'bar',
                    'encode' => ['x' => 'amount', 'y' => 'product'],
                    'label' => [
                        'show' => true,
                        'position' => 'right',
                        'fontWeight' => 'bold',
                        'color' => '#FFFFFF',
                        'fontSize' => 12,
                        'formatter' => '{@[3]}'
                    ]
                ]
            ]
        ];

        if ($hasData) {
            $options['visualMap'] = [
                'orient' => 'horizontal',
                'left' => 'center',
                'bottom' => 0,
                'min' => 0,
                'max' => $maxInversion,
                'text' => ['Inversión Alta', 'Inversión Baja'],
                'textStyle' => ['color' => '#FFFFFF'],
                'dimension' => 0,
                'inRange' => ['color' => ['#65B581', '#FFCE34', '#FD665F']]
            ];
        }

        return $options;
    }
}
