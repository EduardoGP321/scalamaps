<?php

namespace App\Filament\Resources\ParteObras\Pages;

use App\Filament\Resources\ParteObras\ParteObraResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditParteObra extends EditRecord
{
    protected static string $resource = ParteObraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
