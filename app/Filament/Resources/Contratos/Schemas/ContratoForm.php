<?php

namespace App\Filament\Resources\Contratos\Schemas;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\Column;

class ContratoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificación del Contrato')
                    ->columns(2)
                    ->schema([
                        TextInput::make('numero_contrato')
                            ->label('Número de Contrato (Oficial)')
                            ->placeholder('Ej. 2024-H-CF-A-001-W-0-2024')
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('nombre_corto')
                            ->label('Nombre corto del Contrato')
                            ->placeholder('Ej. Conservación Puebla 2024')
                            ->required(),

                        Textarea::make('nombre_completo')
                            ->label('Nombre Completo del Contrato')
                            ->placeholder('Ej. Conservación Puebla 2024')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Asociaciones')
                    ->columns(3)
                    ->schema([
                        Select::make('dependencia_id')
                            ->label('Dependencia')
                            ->relationship('dependencia', 'siglas')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('empresa_id')
                            ->label('Empresa Ejecutora')
                            ->relationship('empresa', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('carreteras')
                            ->label('Carretera(s)')
                            ->relationship('carreteras', 'nombre')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),

                Section::make('Vigencia (Define el Año)')
                    ->columns(3)
                    ->schema([
                        DatePicker::make('fecha_inicio')
                            ->label('Fecha de Inicio')
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(fn($set, $get) => self::syncDiasNaturales($set, $get))
                            ->displayFormat('d/m/Y'),

                        DatePicker::make('fecha_fin')
                            ->label('Fecha de Término')
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(fn($set, $get) => self::syncDiasNaturales($set, $get))
                            ->displayFormat('d/m/Y'),

                        TextInput::make('dias_contrato')
                            ->label('Duración en Días')
                            ->readonly()
                            ->dehydrated()
                            ->helperText('Calculado automáticamente a partir de las fechas de inicio y término')

                    ]),
            ])
            ->columns(1);
    }

    public static function syncDiasNaturales($set, $get): void
    {
        $inicio = $get('fecha_inicio');
        $fin = $get('fecha_fin');

        if ($inicio && $fin) {
            $fechaInicio = Carbon::parse($inicio);
            $fechaFin = Carbon::parse($fin);
            $dias = $fechaInicio->diffInDays($fechaFin, false);
            $set('dias_contrato', $dias >= 0 ? $dias + 1 : 0);
        } else {
            $set('dias_contrato', null);
        }
    }
}
