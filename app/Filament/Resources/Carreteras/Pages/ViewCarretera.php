<?php

namespace App\Filament\Resources\Carreteras\Pages;

use App\Filament\Resources\Carreteras\CarreteraResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCarretera extends ViewRecord
{
    protected static string $resource = CarreteraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
