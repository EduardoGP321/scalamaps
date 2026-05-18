<?php

namespace App\Filament\Resources\Tramos\Pages;

use App\Filament\Resources\Tramos\TramoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTramo extends ViewRecord
{
    protected static string $resource = TramoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
