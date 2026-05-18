<?php

namespace App\Filament\Resources\TipoActivos\Pages;

use App\Filament\Resources\TipoActivos\TipoActivoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTipoActivos extends ListRecords
{
    protected static string $resource = TipoActivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
