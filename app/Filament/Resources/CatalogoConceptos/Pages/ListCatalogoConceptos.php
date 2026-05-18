<?php

namespace App\Filament\Resources\CatalogoConceptos\Pages;

use App\Filament\Resources\CatalogoConceptos\CatalogoConceptoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCatalogoConceptos extends ListRecords
{
    protected static string $resource = CatalogoConceptoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
