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

    protected $queryString = ['contratoId'];


    public function updatedContratoId($value): void
    {
        if (! $value) {
            $this->dispatch('update-map', payload: null);
            return;
        }

        $contrato = Contrato::find($value);
        if ($contrato) {
            $this->dispatch('update-map', payload: GisEngineService::getContratoGisData($contrato));
        }
    }

    protected function getViewData(): array
    {
        // Cargamos la relación empresa para mostrar el nombre en el overlay
        $contratos = Contrato::with('empresa')
            ->select('id', 'nombre_corto', 'numero_contrato', 'empresa_id')
            ->orderBy('nombre_corto')
            ->get();



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
