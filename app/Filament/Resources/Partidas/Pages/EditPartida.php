<?php

namespace App\Filament\Resources\Partidas\Pages;

use App\Filament\Resources\Partidas\PartidaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPartida extends EditRecord
{
    protected static string $resource = PartidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
