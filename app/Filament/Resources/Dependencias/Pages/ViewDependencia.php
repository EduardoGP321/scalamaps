<?php

namespace App\Filament\Resources\Dependencias\Pages;

use App\Filament\Resources\Dependencias\DependenciaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDependencia extends ViewRecord
{
    protected static string $resource = DependenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
