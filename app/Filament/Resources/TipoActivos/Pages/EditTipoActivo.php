<?php

namespace App\Filament\Resources\TipoActivos\Pages;

use App\Filament\Resources\TipoActivos\TipoActivoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTipoActivo extends EditRecord
{
    protected static string $resource = TipoActivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
