<?php

namespace App\Filament\Pages;

use App\Models\Contrato;
use App\Services\GisEngineService;
use Filament\Pages\Page;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class MapaGlobal extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Map;
    protected static ?string $navigationLabel = 'GIS';
    protected static ?string $title = 'GIS';
    protected string $view = 'filament.pages.mapa-global';
    protected static string|UnitEnum|null $navigationGroup = 'Monitoreo';


    public ?int $contratoId = null;

    // Sincroniza el ID con la URL
    protected $queryString = ['contratoId'];

    /**
     * Se ejecuta cuando el usuario cambia el selector de contrato
     */
    public function updatedContratoId($value): void
    {
        if (!$value) {
            $this->dispatch('update-map', payload: null);
            return;
        }

        $contrato = Contrato::find($value);
        if ($contrato) {
            $this->dispatch('update-map', payload: GisEngineService::getContratoGisData($contrato));
        }
    }

    /**
     * Prepara datos para la vista Blade (carga inicial)
     */
    protected function getViewData(): array
    {
        $contratos = Contrato::select('id', 'nombre_corto', 'numero_contrato')->get();

        $initialGisData = null;
        if ($this->contratoId) {
            $contrato = Contrato::find($this->contratoId);
            if ($contrato) {
                $initialGisData = GisEngineService::getContratoGisData($contrato);
            } else {
                $this->contratoId = null;
            }
        }

        return [
            'contratos' => $contratos,
            'gisData'   => $initialGisData,
        ];
    }

    public function mount(): void
    {
        if ($this->contratoId) {
            $contrato = Contrato::find($this->contratoId);
            if ($contrato) {
                $this->dispatch('update-map', payload: GisEngineService::getContratoGisData($contrato));
            }
        }
    }
}
