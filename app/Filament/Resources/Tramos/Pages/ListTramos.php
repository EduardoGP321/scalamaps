<?php

namespace App\Filament\Resources\Tramos\Pages;

use App\Filament\Resources\Tramos\TramoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTramos extends ListRecords
{
    protected static string $resource = TramoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
