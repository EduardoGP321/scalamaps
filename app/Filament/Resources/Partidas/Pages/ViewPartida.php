<?php

namespace App\Filament\Resources\Partidas\Pages;

use App\Filament\Resources\Partidas\PartidaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPartida extends ViewRecord
{
    protected static string $resource = PartidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
