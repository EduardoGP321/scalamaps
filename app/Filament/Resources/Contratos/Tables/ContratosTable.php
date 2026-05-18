<?php

namespace App\Filament\Resources\Contratos\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;

class ContratosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Identificación Ejecutiva
                TextColumn::make('numero_contrato')
                    ->label('No. Contrato')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->description(fn($record) => $record->nombre_corto)
                    ->toggleable(),

                // Entidades (Usando nombres, no IDs)
                TextColumn::make('dependencia.siglas')
                    ->label('Dependencia')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('empresa.nombre')
                    ->label('Contratista')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('carretera.nombre')
                    ->label('Carretera')
                    ->sortable()
                    ->toggleable(),

                // Datos de Ingeniería (Tramos)
                TextColumn::make('tramos_count')
                    ->label('Tramos')
                    ->counts('tramos') // Requiere relación hasMany 'tramos'
                    ->badge()
                    ->color('info')
                    ->alignCenter()
                    ->toggleable(),

                // Vigencia y Control de Tiempo
                TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('fecha_fin')
                    ->label('Término')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),

                // Cálculo de días restantes o transcurridos
                TextColumn::make('dias_contrato')
                    ->label('Duración')
                    ->suffix(' días')
                    ->sortable()
                    ->alignRight()
                    ->toggleable(),

            ])
            ->filters([
                // Filtro por Dependencia (SCT, CAPUFE, etc)
                SelectFilter::make('dependencia')
                    ->relationship('dependencia', 'nombre')
                    ->searchable()
                    ->preload(),

                // Filtro por Carretera
                SelectFilter::make('carretera')
                    ->relationship('carretera', 'nombre')
                    ->searchable()
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
                        ->modalHeading('¿Eliminar contrato?')
                        ->modalDescription('Esta acción borrará el contrato permanentemente. ¿Estás seguro?')
                ])
                    ->tooltip('Acciones')
            ]);
    }
}
