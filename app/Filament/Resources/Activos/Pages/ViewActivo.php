<?php

namespace App\Filament\Resources\Activos\Pages;

use App\Filament\Resources\Activos\ActivoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewActivo extends ViewRecord
{
    protected static string $resource = ActivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
