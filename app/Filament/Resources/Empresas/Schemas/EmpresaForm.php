<?php

namespace App\Filament\Resources\Empresas\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EmpresaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                FileUpload::make('logo')
                    ->image()
                    ->disk('public')
                    ->directory('empresas/logo')
                    ->visibility('public')
                    ->imageEditor()
                    ->optimize('webp', 85)
                    ->maxImageWidth('1200')
                    ->maxImageHeight('1200'),
                Toggle::make('es_externa')
                    ->required(),
            ]);
    }
}
