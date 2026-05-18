<?php

namespace App\Filament\Resources\Empresas\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmpresasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->imageHeight(40)
                    ->disk('public')
                    ->circular()
                    ->extraImgAttributes([
                        'alt' => 'Logo',
                        'loading' => 'lazy'
                    ]),
                TextColumn::make('nombre')
                    ->searchable(),
                IconColumn::make('es_externa')
                    ->boolean()
                    ->color(fn(string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        '0' => 'heroicon-m-x-circle',
                        '1' => 'heroicon-m-check-badge',
                        default => 'heroicon-m-question-mark-circle',
                    }),


                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
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
                        ->modalHeading('¿Eliminar empresa?')
                        ->modalDescription('Esta acción borrará la empresa permanentemente. ¿Estás seguro?')
                ])
                    ->tooltip('Acciones')
            ]);
    }
}
