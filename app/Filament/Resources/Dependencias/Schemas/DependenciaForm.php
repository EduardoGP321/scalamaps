<?php

namespace App\Filament\Resources\Dependencias\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DependenciaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Cliente/Dependencia')
                    ->columns(3)
                    ->schema([
                        TextInput::make('nombre')
                            ->required(),
                        TextInput::make('siglas'),
                        Select::make('tipo')
                            ->options([
                                'concesionado' => 'Concesionado',
                                'federal' => 'Federal'
                            ])
                            ->default('concesionado')
                            ->required(),

                        FileUpload::make('logo')
                            ->image()
                            ->disk('public')
                            ->directory('dependecias/logo')
                            ->visibility('public')
                            ->optimize('webp', 85)
                            ->maxImageWidth('1200')
                            ->maxImageHeight('1200')
                            ->columnSpanFull(),

                    ]),

            ])
            ->columns(1);
    }
}
