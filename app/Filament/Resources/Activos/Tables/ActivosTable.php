<?php

namespace App\Filament\Resources\Activos\Tables;

use App\Models\Empresa;
use App\Models\Tramo;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. TRAMO (Relación directa para ver el nombre, no el ID numérico)
                TextColumn::make('tramo.nombre_tramo')
                    ->label('Tramo')
                    ->searchable()
                    ->sortable(),

                // 2. TIPO DE ACTIVO (Estructura vial identificable)
                TextColumn::make('tipoActivo.nombre')
                    ->label('Tipo de Estructura')
                    ->searchable()
                    ->sortable(),

                // 3. CADENAMIENTO (Formateado como kilometraje real: km 12+340)
                TextColumn::make('cadenamiento')
                    ->label('Ubicación (Cadenamiento)')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('lado')
                    ->label('Lado')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'izquierdo', 'izq' => 'info',
                        'derecho', 'der' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => strtoupper($state)),

                TextColumn::make('condicion')
                    ->label('Condición')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'excelente' => 'success',
                        'bueno' => 'info',
                        'aceptable' => 'warning',
                        'regular' => 'danger',
                        'malo' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state) => ucfirst(strtolower($state))),

                TextColumn::make('obraMenor.material')
                    ->label('Material Principal')
                    ->placeholder('Sin Material registrado')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn(string $state) => ucfirst(strtolower($state))),

                TextColumn::make('conceptos_sum_importe')
                    ->label('Costo Estimado (Mantenimiento)')
                    ->sum('conceptos', 'importe')
                    ->money('MXN')
                    ->sortable(),
            ])

            ->filters([
                Filter::make('ubicacion')
                    ->schema([
                        Select::make('empresa_id')
                            ->label('Empresa Supervisora')
                            ->placeholder('Todas las empresas')
                            ->options(function () {
                                // Si es super_admin, ve absolutamente todas las empresas registradas
                                if (auth()->user()->hasRole('super_admin')) {
                                    return Empresa::pluck('name', 'id');
                                }

                                // Si es cualquier otro usuario, se limita a sus contratos y asignaciones
                                $userContratoIds = auth()->user()->contratos()->pluck('contratos.id')->toArray();
                                return Empresa::whereHas('users', fn($q) => $q->where('user_id', auth()->id()))
                                    ->orWhereIn('id', function ($query) use ($userContratoIds) {
                                        $query->select('empresa_id')->from('contratos')->whereIn('id', $userContratoIds);
                                    })
                                    ->pluck('name', 'id');
                            })
                            ->live()
                            ->afterStateUpdated(fn(callable $set) => $set('tramo_id', null)),

                        Select::make('tramo_id')
                            ->label('Tramo')
                            ->placeholder(fn(callable $get) => !$get('empresa_id') ? 'Selecciona una empresa primero...' : 'Todos los tramos')
                            ->disabled(fn(callable $get) => !$get('empresa_id'))
                            ->options(function (callable $get) {
                                $empresaId = $get('empresa_id');
                                if (!$empresaId) {
                                    return [];
                                }

                                // Filtramos los tramos cuyos contratos pertenezcan a la empresa seleccionada
                                return Tramo::whereHas('contrato', fn($q) => $q->where('empresa_id', $empresaId))
                                    ->orderBy('nombre_tramo')
                                    ->pluck('nombre_tramo', 'id');
                            }),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            // Si se seleccionó una empresa pero no un tramo específico
                            ->when(
                                $data['empresa_id'] && !$data['tramo_id'],
                                fn(Builder $q) => $q->whereHas('tramo.contrato', function (Builder $subQ) use ($data) {
                                    $subQ->where('empresa_id', $data['empresa_id']);
                                })
                            )
                            // Si se seleccionó un tramo específico
                            ->when(
                                $data['tramo_id'],
                                fn(Builder $q) => $q->where('tramo_id', $data['tramo_id'])
                            );
                    }),

                SelectFilter::make('condicion')
                    ->label('Filtrar por Condición')
                    ->options([
                        'excelente' => 'Excelente',
                        'bueno' => 'Bueno',
                        'aceptable' => 'Aceptable',
                        'regular' => 'Regular',
                        'malo' => 'Malo',
                        'sin_datos' => 'Sin Datos',
                    ]),

                // Filtro por Tipo de Estructura Vial
                SelectFilter::make('subtipo')
                    ->label('Tipo de Obra Menor')
                    ->options([
                        'tubo' => 'Tubo',
                        'bateria_2_tubos' => 'Batería 2 tubos',
                        'bateria_3_tubos' => 'Batería 3 tubos',
                        'losa' => 'Losa',
                        'cajon' => 'Cajón',
                        'boveda' => 'Bóveda',
                        'sifon' => 'Sifón',
                        'mixto' => 'Mixto',
                    ])
                    ->query(fn(Builder $query, array $data): Builder => $query->when(
                        $data['value'],
                        fn(Builder $q) => $q->whereHas('obraMenor', fn(Builder $subQ) => $subQ->where('subtipo', $data['value']))
                    )),
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
