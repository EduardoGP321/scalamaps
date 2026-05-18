<?php

namespace App\Filament\Resources\Tramos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TramoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Vinculación Operativa')
                    ->columns(2)
                    ->schema([
                        Select::make('contrato_id')
                            ->label('Contrato / Año')
                            ->relationship('contrato', 'numero_contrato')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('carretera_id')
                            ->label('Carretera')
                            ->relationship('carretera', 'nombre')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),

                Section::make('Detalles del Tramo')
                    ->schema([
                        TextInput::make('nombre_tramo')
                            ->label('Nombre del Tramo')
                            ->placeholder('Ej. Entronque Atlixco - Izúcar')
                            ->required(),
                    ]),

                Section::make('Georreferenciación del Segmento')
                    ->description('Define los puntos A y B para el trazo en el mapa.')
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
                    ->collapsed() // Mantenlo cerrado para no estorbar
                    ->schema([
                        Textarea::make('polyline_google')
                            ->label('Polyline String')
                            ->rows(3)
                            ->disabled() // Se llenará automáticamente después
                            ->placeholder('Este campo se genera mediante la Routes API'),
                    ]),
            ])
            ->columns(1);
    }
}
