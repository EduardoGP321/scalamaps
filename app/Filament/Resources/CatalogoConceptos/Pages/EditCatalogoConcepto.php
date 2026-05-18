<?php

namespace App\Filament\Resources\CatalogoConceptos\Pages;

use App\Filament\Resources\CatalogoConceptos\CatalogoConceptoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCatalogoConcepto extends EditRecord
{
    protected static string $resource = CatalogoConceptoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
