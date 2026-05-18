<?php

namespace App\Filament\Resources\Carreteras\Pages;

use App\Filament\Resources\Carreteras\CarreteraResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCarreteras extends ListRecords
{
    protected static string $resource = CarreteraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
