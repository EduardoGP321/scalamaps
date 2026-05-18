<?php

namespace App\Filament\Resources\CatalogoConceptos\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CatalogoConceptosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Identificador único del concepto
                TextColumn::make('clave')
                    ->label('Clave')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                // Clasificación Técnica
                TextColumn::make('parteObra.nombre')
                    ->label('Parte de Obra')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('partida.nombre')
                    ->label('Partida')
                    ->sortable()
                    ->searchable(),

                // Descripción (con límite de caracteres para no romper la tabla)
                TextColumn::make('concepto')
                    ->label('Concepto')
                    ->limit(50)
                    ->searchable()
                    ->tooltip(fn($record) => $record->concepto),

                // Datos de Ingeniería y Costos
                TextColumn::make('unidad')
                    ->label('Unidad')
                    ->badge()
                    ->color('info')
                    ->alignCenter(),

                TextColumn::make('precio_unitario')
                    ->label('P.U.')
                    ->money('MXN') // O la moneda que manejes, añade formato $ 0.00
                    ->sortable()
                    ->alignRight(),

                // Auditoría (Oculta por defecto)
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
                        ->modalHeading('¿Eliminar la parte de obra?')
                        ->modalDescription('Esta acción borrará la parte de obra permanentemente. ¿Estás seguro?')
                ])
                    ->tooltip('Acciones')
            ]);
    }
}
