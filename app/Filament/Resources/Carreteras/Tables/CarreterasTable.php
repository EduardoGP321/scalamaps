<?php

namespace App\Filament\Resources\Carreteras\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CarreterasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->searchable(),
                TextColumn::make('latitud_inicio')
                    ->label('Lat. Inicio')
                    ->numeric(),

                TextColumn::make('longitud_inicio')
                    ->label('Lon. Inicio')
                    ->numeric(),

                TextColumn::make('latitud_fin')
                    ->label('Lat. Fin')
                    ->numeric(),

                TextColumn::make('longitud_fin')
                    ->label('Lon. Fin')
                    ->numeric(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
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
                        ->modalHeading('¿Eliminar la partida?')
                        ->modalDescription('Esta acción borrará la partida permanentemente. ¿Estás seguro?')
                ])
                    ->tooltip('Acciones')
            ]);
    }
}
