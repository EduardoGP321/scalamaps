<?php

namespace App\Filament\Resources\ParteObras\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ParteObraInfolist
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
                                    ->label('Parte de Obra')
                                    ->weight('bold')
                                    ->size('lg')
                                    ->columnSpan(2),
                            ]),
                    ])
            ])
            ->columns(1);
    }
}
