<?php

namespace App\Filament\Resources\Carreteras\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CarreteraInfolist
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
                                TextEntry::make('nombre')
                                    ->label('Nombre de la Carretera')
                                    ->weight('bold')
                                    ->size('lg')
                                    ->columnSpanFull(),
                            ]),

                        Grid::make(4)
                            ->extraAttributes(['class' => 'border-t border-gray-100 dark:border-gray-800 pt-4 mt-4'])
                            ->schema([
                                TextEntry::make('latitud_inicio')
                                    ->label('Latitud Inicio')
                                    ->numeric()
                                    ->placeholder('-'),

                                TextEntry::make('longitud_inicio')
                                    ->label('Longitud Inicio')
                                    ->numeric()
                                    ->placeholder('-'),

                                TextEntry::make('latitud_fin')
                                    ->label('Latitud Final')
                                    ->numeric()
                                    ->placeholder('-'),

                                TextEntry::make('longitud_fin')
                                    ->label('Longitud Final')
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
