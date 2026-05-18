<?php

namespace App\Filament\Resources\ParteObras\Pages;

use App\Filament\Resources\ParteObras\ParteObraResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListParteObras extends ListRecords
{
    protected static string $resource = ParteObraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
