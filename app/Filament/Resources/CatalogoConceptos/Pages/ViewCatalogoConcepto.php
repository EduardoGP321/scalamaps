<?php

namespace App\Filament\Resources\CatalogoConceptos\Pages;

use App\Filament\Resources\CatalogoConceptos\CatalogoConceptoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCatalogoConcepto extends ViewRecord
{
    protected static string $resource = CatalogoConceptoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
