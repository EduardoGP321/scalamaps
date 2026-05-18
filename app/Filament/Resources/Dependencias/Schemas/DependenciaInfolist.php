<?php

namespace App\Filament\Resources\Dependencias\Schemas;

use App\Models\Dependencia;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DependenciaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información General')
                    ->schema([
                        Grid::make(4) 
                            ->schema([

                                // Logo ocupando 1 columna
                                ImageEntry::make('logo')
                                    ->hiddenLabel()
                                    ->disk('public')
                                    ->circular()
                                    ->height(100)
                                    ->columnSpan(1),

                                // Nombre y Siglas ocupando 2 columnas
                                TextEntry::make('nombre')
                                    ->label('Dependencia')
                                    ->weight('bold')
                                    ->size('lg')
                                    ->columnSpan(2),

                                // Tipo con Badge ocupando 1 columna
                                TextEntry::make('tipo')
                                    ->label('Administración')
                                    ->badge()
                                    ->formatStateUsing(fn(string $state): string => ucfirst($state))
                                    ->color(fn(string $state): string => match ($state) {
                                        'concesionado' => 'warning',
                                        'federal' => 'success',
                                        'estatal' => 'info',
                                        default => 'gray',
                                    })
                                    ->icon(fn(string $state): string => match ($state) {
                                        'concesionado' => 'heroicon-m-receipt-refund',
                                        'federal' => 'heroicon-m-building-library',
                                        default => 'heroicon-m-tag',
                                    })
                                    ->columnSpan(1),
                            ]),
                    ])
            ])
            ->columns(1);
    }
}
