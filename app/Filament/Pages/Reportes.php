<?php

namespace App\Filament\Pages;

use App\Models\Contrato;
use App\Models\TipoActivo;
use App\Models\Tramo;
use App\Services\ReportExportFactory;
use App\Services\ReportEngineService;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Daljo25\FilamentTablerIcons\Enums\TablerIcon;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use UnitEnum;

class Reportes extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.pages.reportes';
    protected static string|BackedEnum|null $navigationIcon = TablerIcon::ReportAnalytics;
    protected static string|UnitEnum|null $navigationGroup = 'Monitoreo';
    public ?array $data = [];
    public ?array $reportData = null;

    public function mount(): void
    {
        $this->schema->fill([]);
        $this->reportData = null;
    }


    public function getHeaderActions(): array
    {
        return [
            /*
            Action::make('ReportePDF')
                ->label('Generar Reporte PDF')
                ->icon(Heroicon::DocumentText)
                ->color('danger')
                ->disabled(fn() => $this->reportData === null)
                ->action('descargarPdf'),
                */
            Action::make('ReporteExcel')
                ->label('Generar Reporte EXCEL')
                ->icon(TablerIcon::FileExcel)
                ->color('success')
                ->action('descargarExcel'),
        ];
    }

    public function schema(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Reporte de Contrato')
                    ->description('Defina qué tramos y tipos de activos se incluirán en los reportes.')
                    ->components([
                        Grid::make(3)
                            ->schema([
                                Select::make('contrato_id')
                                    ->label('Seleccione el Contrato')
                                    ->options(Contrato::query()->pluck('numero_contrato', 'id'))
                                    ->placeholder('— Seleccione un contrato —')
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set) {

                                        $set('tramo_ids', []);
                                        $set('tipo_activo_id', null);
                                        $this->reportData = null;
                                    }),

                                Select::make('tramo_ids')
                                    ->label('Seleccione el/los Tramo(s)')
                                    ->multiple()
                                    ->required()
                                    ->live()
                                    ->options(function (Get $get) {
                                        $contratoId = $get('contrato_id');
                                        if (! $contratoId) {
                                            return [];
                                        }
                                        return Tramo::where('contrato_id', $contratoId)
                                            ->pluck('nombre_tramo', 'id');
                                    })
                                    ->disabled(fn(Get $get) => ! $get('contrato_id'))
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $set('tipo_activo_id', null);
                                        $this->recalcularReporte($get);
                                    }),

                                Select::make('tipo_activo_id')
                                    ->label('Activo del Tramo')
                                    ->placeholder('Todos los Activos')
                                    ->live()
                                    ->options(function (Get $get) {
                                        $tramoIds = $get('tramo_ids');
                                        if (empty($tramoIds)) {
                                            return [];
                                        }
                                        return TipoActivo::whereIn('id', function ($query) use ($tramoIds) {
                                            $query->select('tipo_activo_id')
                                                ->from('activos')
                                                ->whereIn('tramo_id', $tramoIds)
                                                ->whereNotNull('tipo_activo_id');
                                        })->pluck('nombre', 'id');
                                    })
                                    ->disabled(fn(Get $get) => empty($get('tramo_ids')))
                                    ->afterStateUpdated(function (Get $get) {
                                        $this->recalcularReporte($get);
                                    }),
                            ]),
                    ]),
            ]);
    }

    private function recalcularReporte(Get $get): void
    {
        $filtros = [
            'tramo_ids'      => $get('tramo_ids')      ?? [],
            'tipo_activo_id' => $get('tipo_activo_id') ?? null,
        ];

        $this->reportData = ReportEngineService::getContratoReportData($filtros);
    }

    public function descargarPdf()
    {
        if (!$this->reportData) return;

        $pdf = Pdf::loadView('pdf.pdf-report', ['reporte' => $this->reportData])
            ->setPaper('letter', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Reporte_CAPUFE_' . now()->format('Ymd_His') . '.pdf');
    }

    public function descargarExcel()
    {
        // Forzamos la extracción del estado más fresco del formulario antes de procesar
        $this->data = $this->schema->getState();

        // Si por alguna razón reportData se vació, forzamos su cálculo manual con los datos reales
        if (!$this->reportData && !empty($this->data['tramo_ids'])) {
            $filtros = [
                'tramo_ids'      => $this->data['tramo_ids'],
                'tipo_activo_id' => $this->data['tipo_activo_id'] ?? null,
            ];
            $this->reportData = ReportEngineService::getContratoReportData($filtros);
        }

        if (!$this->reportData) {
            return session()->flash('error', 'No hay información suficiente para estructurar el reporte.');
        }

        // Ejecuta el despachador pasando las variables mapeadas
        return ReportExportFactory::export(
            $this->data['tipo_activo_id'] ?? null,
            $this->reportData
        );
    }
}
