<?php

namespace App\Filament\Resources\Carreteras\Pages;

use App\Filament\Resources\Carreteras\CarreteraResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCarretera extends EditRecord
{
    protected static string $resource = CarreteraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
