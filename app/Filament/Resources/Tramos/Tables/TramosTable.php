<?php

namespace App\Filament\Resources\Tramos\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TramosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Identificación Principal del Tramo
                TextColumn::make('nombre_tramo')
                    ->label('Nombre del Tramo')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                // 2. Relación con la red base (Muestra el nombre, no el ID)
                TextColumn::make('carretera.nombre') // Ajusta 'nombre' si tu columna en carreteras se llama diferente
                    ->label('Carretera')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('gray'),

                // 3. Relación con la Gestión Operativa (Muestra el alias del contrato)
                TextColumn::make('contrato.nombre_corto')
                    ->label('Contrato')
                    ->sortable()
                    ->searchable(),


                // 5. Coordenadas de Inicio y Fin (Ocultas por defecto para no saturar la vista)
                TextColumn::make('latitud_inicio')
                    ->label('Lat. Inicio')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('longitud_inicio')
                    ->label('Lon. Inicio')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('latitud_fin')
                    ->label('Lat. Fin')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('longitud_fin')
                    ->label('Lon. Fin')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                // 6. Auditoría de creación
                TextColumn::make('created_at')
                    ->label('Fecha Registro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filtros esenciales para que el ingeniero aísle tramos rápidamente
                SelectFilter::make('carretera_id')
                    ->relationship('carretera', 'nombre')
                    ->label('Filtrar por Carretera')
                    ->preload(),

                SelectFilter::make('contrato_id')
                    ->relationship('contrato', 'nombre_corto')
                    ->label('Filtrar por Contrato')
                    ->preload(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Editar')
                        ->icon(Heroicon::PencilSquare)
                        ->color('primary'),

                    ViewAction::make()
                        ->label('Ver')
                        ->icon(Heroicon::FolderOpen)
                        ->color('info'),

                    DeleteAction::make()
                        ->label('Eliminar')
                        ->icon(Heroicon::Trash)
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('¿Eliminar la partida?')
                        ->modalDescription('Esta acción borrará la partida permanentemente. ¿Estás seguro?')
                ])
                    ->tooltip('Acciones')
            ]);
    }
}
