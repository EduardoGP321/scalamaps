<?php

namespace App\Filament\Resources\Activos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActivosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tramo_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tipo_activo_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('kilometraje')
                    ->searchable(),
                TextColumn::make('latitud')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('longitud')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('lado')
                    ->badge(),
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
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
