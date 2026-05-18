<?php

namespace App\Filament\Resources\TipoActivos\Pages;

use App\Filament\Resources\TipoActivos\TipoActivoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTipoActivo extends ViewRecord
{
    protected static string $resource = TipoActivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
