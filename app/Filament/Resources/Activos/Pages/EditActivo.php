<?php

namespace App\Filament\Resources\Activos\Pages;

use App\Filament\Resources\Activos\ActivoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditActivo extends EditRecord
{
    protected static string $resource = ActivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

}
