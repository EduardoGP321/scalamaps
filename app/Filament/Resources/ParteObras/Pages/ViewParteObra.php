<?php

namespace App\Filament\Resources\ParteObras\Pages;

use App\Filament\Resources\ParteObras\ParteObraResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewParteObra extends ViewRecord
{
    protected static string $resource = ParteObraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
