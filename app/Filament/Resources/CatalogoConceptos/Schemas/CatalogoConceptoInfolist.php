<?php

namespace App\Filament\Resources\CatalogoConceptos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CatalogoConceptoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información General del Concepto')
                    ->icon('tabler-list-details') // Icono consistente con tu sidebar
                    ->schema([
                        Grid::make(4) // Dividimos en 4 columnas para una distribución horizontal limpia
                            ->schema([

                                TextEntry::make('clave')
                                    ->label('Clave')
                                    ->weight('bold')
                                    ->copyable()
                                    ->columnSpan(1),

                                TextEntry::make('parteObra.nombre')
                                    ->label('Parte de Obra')
                                    ->badge()
                                    ->color('gray')
                                    ->columnSpan(1),

                                TextEntry::make('partida.nombre')
                                    ->label('Partida')
                                    ->columnSpan(2),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextEntry::make('concepto')
                                    ->label('Concepto')
                                    ->columnSpan(2)
                                    ->markdown(),

                                Grid::make(1)
                                    ->columnSpan(1)
                                    ->schema([
                                        TextEntry::make('unidad')
                                            ->label('Unidad de Medida')
                                            ->badge()
                                            ->color('info'),

                                        TextEntry::make('precio_unitario')
                                            ->label('Precio Unitario')
                                            ->money('MXN')
                                            ->weight('bold')
                                            ->size('lg')
                                            ->color('success'),
                                    ]),
                            ]),
                    ]),
            ])
            ->columns(1);
    }
}
