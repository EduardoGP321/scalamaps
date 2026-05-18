<?php

namespace App\Filament\Resources\Tramos\Pages;

use App\Filament\Resources\Tramos\TramoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTramo extends EditRecord
{
    protected static string $resource = TramoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
