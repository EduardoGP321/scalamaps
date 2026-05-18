<?php

namespace App\Filament\Resources\Tramos\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TramoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información Detallada del Tramo')
                    ->icon('tabler-road')
                    ->schema([

                        Grid::make(3)
                            ->schema([
                                TextEntry::make('nombre_tramo')
                                    ->label('Nombre del Tramo')
                                    ->weight('bold')
                                    ->size('lg')
                                    ->columnSpan(1),

                                TextEntry::make('carretera.nombre')
                                    ->label('Carretera Base')
                                    ->badge()
                                    ->color('gray')
                                    ->columnSpan(1),

                                TextEntry::make('contrato.nombre_corto')
                                    ->label('Contrato Asignado')
                                    ->columnSpan(1),
                            ]),

                        Grid::make(4)
                            ->extraAttributes(['class' => 'border-t border-gray-100 dark:border-gray-800 pt-4 mt-4'])
                            ->schema([
                                TextEntry::make('latitud_inicio')
                                    ->label('Latitud Origen')
                                    ->numeric()
                                    ->placeholder('-'),

                                TextEntry::make('longitud_inicio')
                                    ->label('Longitud Origen')
                                    ->numeric()
                                    ->placeholder('-'),

                                TextEntry::make('latitud_fin')
                                    ->label('Latitud Destino')
                                    ->numeric()
                                    ->placeholder('-'),

                                TextEntry::make('longitud_fin')
                                    ->label('Longitud Destino')
                                    ->numeric()
                                    ->placeholder('-'),
                            ]),

                        Grid::make(3)
                            ->extraAttributes(['class' => 'border-t border-gray-100 dark:border-gray-800 pt-4 mt-4'])
                            ->schema([

                                IconEntry::make('polyline_google')
                                    ->label('Polilínea Google')
                                    ->getStateUsing(fn($record) => !empty($record->polyline_google))
                                    ->boolean()
                                    ->trueIcon('heroicon-o-check-circle')
                                    ->falseIcon('heroicon-o-x-circle')
                                    ->trueColor('success')
                                    ->falseColor('danger')
                                    ->columnSpan(1),
                            ]),
                    ]),
            ])
            ->columns(1);   
    }
}
