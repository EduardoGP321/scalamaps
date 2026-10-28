<?php

namespace App\Filament\Widgets;

use App\Models\Contrato;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class RealizoSupervision extends BaseWidget
{
    protected static ?string $widgetId = 'realizoSupervisionKpi';
    protected ?string $pollingInterval = null;

    public ?int $contratoId = null;
    public ?int $tramoId    = null;

    protected int | string | array $columnSpan = 'full';
    
    #[On('filtrosDashboardActualizados')]
    public function actualizarFiltros(?int $contratoId = null, ?int $tramoId = null): void
    {
        $this->contratoId = $contratoId;
        $this->tramoId    = $tramoId;
        $this->render();
    }

    protected function getStats(): array
    {
        $nombreEmpresa = 'Sin seleccionar';

        if ($this->contratoId) {
            $contrato = Contrato::with('empresa')->find($this->contratoId);
            if ($contrato && $contrato->empresa) {
                $nombreEmpresa = $contrato->empresa->name;
            } else {
                $nombreEmpresa = 'No asignada';
            }
        }

        return [
            Stat::make('realizado_supervision', $nombreEmpresa)
                ->label(new HtmlString('<span class="text-sm font-medium text-gray-400">Empresa que realizó la supervisión</span>'))
                ->color('amber')
                ->columnSpanFull()
                ,
        ];
    }
}
