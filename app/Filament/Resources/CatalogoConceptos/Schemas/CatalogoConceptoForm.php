<?php

namespace App\Filament\Resources\CatalogoConceptos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CatalogoConceptoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Clasificación del Concepto')
                    ->description('Ubica el concepto dentro de la estructura de la obra.')
                    ->icon('heroicon-m-tag')
                    ->columns(2)
                    ->schema([
                        Select::make('parte_obra_id')
                            ->label('Parte de la Obra')
                            ->relationship('parteObra', 'nombre') // Relación con el modelo ParteObra
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('partida_id')
                            ->label('Partida')
                            ->relationship('partida', 'nombre') // Relación con el modelo Partida
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),

                Section::make('Detalle Técnico')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('clave')
                                    ->label('Clave del Concepto')
                                    ->required()
                                    ->placeholder('EJ: PRE-01')
                                    ->unique(ignoreRecord: true),

                                TextInput::make('unidad')
                                    ->label('Unidad de Medida')
                                    ->required()
                                    ->placeholder('m3, m, pza, kg')
                                    ->datalist([
                                        'm',
                                        'm2',
                                        'm3',
                                        'kg',
                                        'ton',
                                        'pza',
                                        'lote',
                                        'km'
                                    ]),

                                TextInput::make('precio_unitario')
                                    ->label('Precio Unitario')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$') // Visualmente ayuda al ingeniero
                                    ->placeholder('0.00'),
                            ]),

                        Textarea::make('concepto')
                            ->label('Descripción del Concepto')
                            ->required()
                            ->rows(4)
                            ->placeholder('Describa el alcance del concepto, materiales y mano de obra incluidos...')
                            ->columnSpanFull(),
                    ]),
            ])
            ->columns(1);
    }
}
