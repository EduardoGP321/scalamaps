<?php

namespace App\Filament\Resources\Activos\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ActivoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Registro de Activo')
                    ->tabs([
                        Tab::make('Ubicación y Tipo')
                            ->icon('heroicon-m-map-pin')
                            ->schema([
                                Grid::make(3)->schema([
                                    Select::make('tramo_id')
                                        ->relationship('tramo', 'nombre_tramo')
                                        ->required()
                                        ->searchable()
                                        ->preload(),

                                    Select::make('tipo_activo_id')
                                        ->label('Tipo de Activo')
                                        ->relationship('tipoActivo', 'nombre')
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function ($state, $set) {
                                            if ($state == 1) {
                                                $set('latitud', null);
                                                $set('longitud', null);
                                            } else {
                                                $set('obra_menor', []);
                                            }
                                        }),

                                    TextInput::make('cadenamiento')
                                        ->placeholder('9+571')
                                        ->required(),
                                ]),

                                Section::make('Coordenadas del Marcador')
                                    ->description(fn(Get $get) => $get('tipo_activo_id') == 1
                                        ? 'El PIN se calculará automáticamente en el centro geométrico de la Obra Menor.'
                                        : 'Ubicación puntual para el PIN del mapa.')
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('latitud')
                                            ->numeric()
                                            ->disabled(fn(Get $get) => $get('tipo_activo_id') == 1)
                                            ->hidden(fn(Get $get) => $get('tipo_activo_id') == 1)
                                            ->required(fn(Get $get) => $get('tipo_activo_id') != 1),

                                        TextInput::make('longitud')
                                            ->numeric()
                                            ->disabled(fn(Get $get) => $get('tipo_activo_id') == 1)
                                            ->hidden(fn(Get $get) => $get('tipo_activo_id') == 1)
                                            ->required(fn(Get $get) => $get('tipo_activo_id') != 1),

                                        // Si es Obra Menor, le mostramos al usuario un indicador de que el sistema hará el cálculo
                                        Placeholder::make('calculo_automatico')
                                            ->label('Coordenadas GIS')
                                            ->content('Auto-calculadas mediante el eje de entrada/salida')
                                            ->visible(fn(Get $get) => $get('tipo_activo_id') == 1)
                                            ->columnSpan(2),

                                        Select::make('lado')
                                            ->options([
                                                'Izquierdo' => 'Izquierdo',
                                                'Derecho' => 'Derecho',
                                                'Ambos' => 'Ambos',
                                            ])->required(),
                                    ]),
                            ]),

                        // PESTAÑA 2: DETALLES ESPECÍFICOS (La Polilínea o Atributos)
                        Tab::make('Detalles Técnicos')
                            ->icon('heroicon-m-wrench-screwdriver')
                            ->schema([
                                // SECCIÓN PARA OBRA MENOR (Se activa si el ID es 1 - Alcantarillas)
                                Section::make('Atributos de Obra Menor')
                                    ->description('Configuración de la polilínea y dimensiones')
                                    ->hidden(fn(Get $get) => $get('tipo_activo_id') != 1)
                                    ->relationship('obraMenor') // Relación hasOne
                                    ->columns(2)
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('latitud_inicio')->label('Lat. Entrada')->numeric()->required(fn(Get $get) => $get('../../tipo_activo_id') == 1),
                                            TextInput::make('longitud_inicio')->label('Lon. Entrada')->numeric()->required(fn(Get $get) => $get('../../tipo_activo_id') == 1),
                                            TextInput::make('latitud_fin')->label('Lat. Salida')->numeric()->required(fn(Get $get) => $get('../../tipo_activo_id') == 1),
                                            TextInput::make('longitud_fin')->label('Lon. Salida')->numeric()->required(fn(Get $get) => $get('../../tipo_activo_id') == 1),
                                        ]),
                                        TextInput::make('subtipo')->placeholder('Tubo, Cajón, Bóveda'),
                                        TextInput::make('material')->placeholder('Concreto, Chapa, etc.'),
                                        TextInput::make('dimensiones'),
                                        Select::make('condicion')
                                            ->options([
                                                'excelente' => 'Excelente',
                                                'bueno' => 'Bueno',
                                                'regular' => 'Regular',
                                                'malo' => 'Malo',
                                            ])->required(fn(Get $get) => $get('../../tipo_activo_id') == 1),
                                    ]),

                                // SECCIÓN PARA SEÑALAMIENTO (Se activa si el ID es 2 - Señales)
                                Section::make('Atributos de Señalamiento')
                                    ->hidden(fn(Get $get) => $get('tipo_activo_id') != 2)
                                    ->schema([
                                        TextInput::make('codigo_senal')->placeholder('SR-9'),
                                        Select::make('estado_reflejante')
                                            ->options(['Alto', 'Medio', 'Bajo']),
                                    ]),

                                // Placeholder si no se ha seleccionado tipo
                                Section::make('Aviso')
                                    ->hidden(fn(Get $get) => $get('tipo_activo_id') != null)
                                    ->schema([
                                        Placeholder::make('info')
                                            ->content('Por favor, selecciona un tipo de activo en la pestaña anterior.')
                                    ])
                            ]),
                    ])->columnSpanFull()
            ]);
    }
}
