<?php

namespace App\Filament\Resources\TipoActivos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TipoActivoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Configuración del Tipo de Activo')
                    ->icon('tabler-components') // Icono consistente con tu catálogo base
                    ->schema([
                        Grid::make(3) // 3 columnas para la parte superior
                            ->schema([

                                // Nombre del Tipo (Ej: Alcantarilla, Señalamiento)
                                TextEntry::make('nombre')
                                    ->label('Nombre de la Categoría')
                                    ->weight('bold')
                                    ->size('lg')
                                    ->columnSpan(2),

                                // Slug en formato código/monospaced para que se vea técnico
                                TextEntry::make('slug')
                                    ->label('Identificador (Slug)')
                                    ->fontFamily('mono') // Fuente tipo código de programación
                                    ->badge()
                                    ->color('gray')
                                    ->columnSpan(1),
                            ]),

                        // Separador sutil para la descripción
                        Grid::make(1)
                            ->schema([
                                TextEntry::make('descripcion')
                                    ->label('Descripción y Alcance del Activo')
                                    ->placeholder('Sin descripción técnica registrada.')
                                    ->columnSpanFull()
                                    ->html(), // Por si quieres meter saltos de línea (<br>) en la BD
                            ]),
                    ]),
            ])
            ->columns(1);
    }
}
