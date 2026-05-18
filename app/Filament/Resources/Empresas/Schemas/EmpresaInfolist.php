<?php

namespace App\Filament\Resources\Empresas\Schemas;

use App\Models\Empresa;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmpresaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información General')
                    ->schema([
                        Grid::make(4)
                            ->schema([

                                ImageEntry::make('logo')
                                    ->hiddenLabel()
                                    ->disk('public')
                                    ->circular()
                                    ->height(100)
                                    ->columnSpan(1),

                                TextEntry::make('nombre')
                                    ->label('Nombre de la empresa')
                                    ->weight('bold')
                                    ->size('lg')
                                    ->columnSpan(2),

                                TextEntry::make('es_externa')
                                    ->label('Es Externa')
                                    ->badge()
                                    ->formatStateUsing(fn(string $state): string => ucfirst($state))
                                    ->color(fn(string $state): string => match ($state) {
                                        '1' => 'success',
                                        '0' => 'danger',
                                        default => 'gray',
                                    })
                                    ->icon(fn(string $state): string => match ($state) {
                                        '1' => 'heroicon-m-check-badge',
                                        '0' => 'heroicon-m-x-circle',
                                        default => 'heroicon-m-question-mark-circle',
                                    })
                                    ->columnSpan(1),
                            ]),
                    ])
            ])
            ->columns(1);
    }
}
