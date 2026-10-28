<?php

namespace App\Filament\Resources\Carreteras\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CarreteraForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información General')
                    ->columns(1)
                    ->schema([
                        TextInput::make('nombre')
                            ->required(),

                    ]),

                Section::make('Georreferenciación del Segmento')
                    ->description('Define los puntos A y B para el trazo en el mapa de la carretera.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('latitud_inicio')
                            ->label('Latitud Inicio')
                            ->numeric()
                            ->required(),

                        TextInput::make('longitud_inicio')
                            ->label('Longitud Inicio')
                            ->numeric()
                            ->required(),

                        TextInput::make('latitud_fin')
                            ->label('Latitud Fin')
                            ->numeric()
                            ->required(),

                        TextInput::make('longitud_fin')
                            ->label('Longitud Fin')
                            ->numeric()
                            ->required(),
                    ]),

                Section::make('Datos de la API de Google')
                    ->collapsed()
                    ->schema([
                        Textarea::make('polyline_google')
                            ->label('Polyline String')
                            ->rows(3)
                            ->readOnly() // El usuario no puede escribir, pero el componente escucha validaciones
                            ->dehydrated()
                            ->placeholder('Este campo se genera mediante la Routes API'),
                    ]),
            ])
            ->columns(1);
    }
}
