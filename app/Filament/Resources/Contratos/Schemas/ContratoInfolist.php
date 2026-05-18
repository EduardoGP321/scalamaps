<?php

namespace App\Filament\Resources\Contratos\Schemas;

use Dom\Text;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContratoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Contrato')
                    ->description('Datos generales y entidades responsables')
                    ->icon('heroicon-m-document-text')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('numero_contrato')
                                    ->label('No. de Contrato')
                                    ->copyable()
                                    ->weight('bold'),

                                TextEntry::make('nombre_corto')
                                    ->label('Nombre Corto del Contrato'),

                                TextEntry::make('carretera.nombre')
                                    ->label('Nombre de la Carretera')
                                    ->placeholder('No especificada'),

                                TextEntry::make('nombre_completo')
                                    ->label('Nombre Completo del Contrato')
                                    ->placeholder('No especificada')
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Section::make('Participantes')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('dependencia.siglas') // Usando relación
                                    ->label('Dependencia/Cliente')
                                    ->color('primary'),

                                TextEntry::make('empresa.nombre') // Usando relación
                                    ->label('Empresa Contratada'),
                            ]),
                    ])->compact(),

                Section::make('Vigencia del Contrato')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('fecha_inicio')
                                    ->label('Fecha de Inicio del Contrato')
                                    ->date('d M Y')
                                    ->color('success'),

                                TextEntry::make('fecha_fin')
                                    ->label('Terminación del Contrato')
                                    ->date('d M Y')
                                    ->color('danger'),

                                TextEntry::make('dias_contrato')
                                    ->label('Duracion en Días')
                            ]),
                    ]),

                Section::make('Tramos del Contrato')
                    ->icon('heroicon-m-map')
                    ->schema([
                        RepeatableEntry::make('tramos')
                            ->label('Desglose de Tramos e Inspección')
                            ->schema([
                                Grid::make(5)
                                    ->schema([
                                        TextEntry::make('nombre_tramo')
                                            ->label('Nombre del Tramo')
                                            ->weight('bold')
                                            ->columnSpan(1),

                                        TextEntry::make('inicio')
                                            ->label('Referencia Inicio')
                                            ->getStateUsing(fn($record) => "Lat: {$record->latitud_inicio} | Lon: {$record->longitud_inicio}")
                                            ->color('success')
                                            ->columnSpan(2),

                                        TextEntry::make('fin')
                                            ->label('Referencia Fin')
                                            ->getStateUsing(fn($record) => "Lat: {$record->latitud_fin} | Lon: {$record->longitud_fin}")
                                            ->color('danger')
                                            ->columnSpan(2),
                                    ]),
                            ])
                            ->columns(1)
                    ]),

            ])
            ->columns(1);
    }
}
