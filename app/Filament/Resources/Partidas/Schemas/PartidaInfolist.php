<?php

namespace App\Filament\Resources\Partidas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartidaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información General')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                TextEntry::make('nombre')
                                    ->label('Partida')
                                    ->weight('bold')
                                    ->size('lg')
                                    ->columnSpan(2),
                            ]),
                    ])
            ])
            ->columns(1);
    }
}
