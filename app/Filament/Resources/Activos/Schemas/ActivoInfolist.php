<?php

namespace App\Filament\Resources\Activos\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ActivoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Ficha Técnica del Activo')
                    ->tabs([

                        // ─── PESTAÑA 1: INFORMACIÓN GENERAL (REDISEÑADA Y ALINEADA) ───
                        Tab::make('Información General')
                            ->icon('heroicon-m-information-circle')
                            ->schema([

                                // Bloque 1: Cabecera de Identificación Rápida
                                Section::make('Datos de Identificación')
                                    ->icon('heroicon-o-identification')
                                    ->schema([
                                        Grid::make(4)->schema([
                                            TextEntry::make('tramo.nombre_tramo')
                                                ->label('Tramo Carretero')
                                                ->weight('bold'),

                                            TextEntry::make('tipoActivo.nombre')
                                                ->label('Tipo de Estructura')
                                                ->badge()
                                                ->color('info'),

                                            TextEntry::make('cadenamiento')
                                                ->label('Cadenamiento')
                                                ->icon('heroicon-m-flag')
                                                ->fontFamily('mono'),

                                            TextEntry::make('condicion')
                                                ->label('Condición de Conservación')
                                                ->badge()
                                                ->color(fn($state) => match (strtolower($state ?? '')) {
                                                    'excelente' => 'success',
                                                    'bueno' => 'info',
                                                    'aceptable' => 'warning',
                                                    'regular', 'malo' => 'danger',
                                                    default => 'gray',
                                                }),
                                        ]),
                                    ]),

                                // Bloque 2: Distribución Simétrica en 2 Columnas (Datos Técnicos vs Ubicación)
                                Grid::make(2)
                                    ->schema([

                                        // COLUMNA IZQUIERDA: Geometría y Estructura Física
                                        Group::make([
                                            Section::make('Especificaciones Técnicas (Obra Menor)')
                                                ->icon('heroicon-o-wrench-screwdriver')
                                                ->visible(fn($record) => $record?->tipo_activo_id == 1 && $record?->obraMenor)
                                                ->relationship('obraMenor')
                                                ->schema([
                                                    Grid::make(2)->schema([
                                                        TextEntry::make('subtipo')
                                                            ->label('Subtipo de Obra')
                                                            ->placeholder('—'),

                                                        TextEntry::make('material')
                                                            ->label('Material Principal')
                                                            ->icon('heroicon-m-cube')
                                                            ->placeholder('—'),

                                                        TextEntry::make('dimensiones')
                                                            ->label('Dimensiones Nominales')
                                                            ->placeholder('—'),

                                                        TextEntry::make('longitud_estructura')
                                                            ->label('Longitud de Estructura')
                                                            ->placeholder('—'),

                                                        TextEntry::make('area_hidraulica')
                                                            ->label('Área Hidráulica')
                                                            ->placeholder('—'),

                                                        TextEntry::make('pendiente')
                                                            ->label('Pendiente Promedio')
                                                            ->placeholder('—'),
                                                    ]),
                                                ]),
                                        ]),

                                        // COLUMNA DERECHA: Posicionamiento, GIS y Alturas
                                        Group::make([
                                            Section::make('Ubicación, Ejes y Terraplenes')
                                                ->icon('heroicon-o-map-pin')
                                                ->schema([
                                                    Grid::make(2)->schema([
                                                        TextEntry::make('lado')
                                                            ->label('Cuerpo')
                                                            ->badge()
                                                            ->color(fn($state) => match (strtolower($state ?? '')) {
                                                                'izquierdo', 'izq', 'a' => 'success',
                                                                'derecho', 'der', 'b' => 'primary',
                                                                'sobre_eje' => 'warning',
                                                                default => 'gray',
                                                            }),

                                                        TextEntry::make('obraMenor.cuerpo')
                                                            ->label('Posición (Cuerpo)')
                                                            ->placeholder('—'),

                                                        TextEntry::make('obraMenor.altura_terraplen_entrada')
                                                            ->label('Terraplén Entrada')
                                                            ->placeholder('—'),

                                                        TextEntry::make('obraMenor.altura_terraplen_salida')
                                                            ->label('Terraplén Salida')
                                                            ->placeholder('—'),

                                                        TextEntry::make('obraMenor.esviaje')
                                                            ->label('Esviaje de Estructura')
                                                            ->placeholder('—'),
                                                    ]),
                                                ]),
                                        ]),
                                    ]),

                                // Bloque 3: Coordenadas de Trazo (Ocultas si no es Obra Menor)
                                Section::make('Georreferenciación de Extremos (Polilínea)')
                                    ->icon('heroicon-o-globe-alt')
                                    ->visible(fn($record) => $record?->tipo_activo_id == 1 && $record?->obraMenor)
                                    ->relationship('obraMenor')
                                    ->schema([
                                        Grid::make(4)->schema([
                                            TextEntry::make('latitud_inicio')->label('Lat. Entrada')->fontFamily('mono')->placeholder('—'),
                                            TextEntry::make('longitud_inicio')->label('Lon. Entrada')->fontFamily('mono')->placeholder('—'),
                                            TextEntry::make('latitud_fin')->label('Lat. Salida')->fontFamily('mono')->placeholder('—'),
                                            TextEntry::make('longitud_fin')->label('Lon. Salida')->fontFamily('mono')->placeholder('—'),

                                        ]),
                                    ]),

                                // Bloque 4: Notas de Campo (Ancho completo)
                                Section::make('Notas y Trazabilidad de Campo')
                                    ->icon('heroicon-o-chat-bubble-left-right')
                                    ->visible(fn($record) => $record?->tipo_activo_id == 1 && $record?->obraMenor)
                                    ->relationship('obraMenor')
                                    ->schema([
                                        TextEntry::make('observaciones')
                                            ->label('Observaciones Técnicas Detectadas')
                                            ->placeholder('Sin observaciones o comentarios adicionales registrados por la cuadrilla.')
                                            ->columnSpanFull(),
                                    ]),

                                // Mensaje de aviso por si es otro activo que no lleva Obra Menor
                                Section::make('Información Complementaria')
                                    ->visible(fn($record) => $record?->tipo_activo_id != 1)
                                    ->schema([
                                        Placeholder::make('aviso_complementario')
                                            ->label('Nota de Inventario')
                                            ->content('Esta estructura pertenece a una categoría vial distinta a Obras Menores (ej. Señalamiento / Barreras). Los detalles y componentes específicos se gestionan en sus respectivas bitácoras de campo.')
                                    ]),
                            ]),

                        // ─── PESTAÑA 2: CATÁLOGO DE CONCEPTOS APLICADOS ───
                        Tab::make('Conceptos')
                            ->icon('heroicon-m-currency-dollar')
                            ->schema([
                                Section::make('Catálogo de Conceptos')
                                    ->description('Desglose de los trabajos, cantidades e importes asignados a esta estructura.')
                                    ->schema([

                                        RepeatableEntry::make('conceptos')
                                            ->label('Conceptos Registrados')
                                            ->schema([
                                                Grid::make(5)->schema([
                                                    TextEntry::make('catalogoConcepto.clave')
                                                        ->label('Concepto / Tarea Vial')
                                                        ->weight('bold')
                                                        ->columnSpan(2)
                                                        ->formatStateUsing(function ($state, $record) {
                                                            $concepto = $record->catalogoConcepto;
                                                            return $concepto ? "{$concepto->clave} - {$concepto->concepto}" : '—';
                                                        }),

                                                    TextEntry::make('cantidad')
                                                        ->label('Cantidad'),

                                                    TextEntry::make('catalogoConcepto.precio_unitario')
                                                        ->label('Precio Unitario')
                                                        ->money('MXN', locale: 'es_MX')
                                                        ->color('info'),

                                                    TextEntry::make('importe')
                                                        ->label('Importe total')
                                                        ->money('MXN', locale: 'es_MX')
                                                        ->weight('bold')
                                                        ->extraAttributes(['class' => 'text-emerald-400']),
                                                ]),
                                            ]),

                                        // Bloque de la tarjeta del Gran Total Acumulado del Activo
                                        Section::make()
                                            ->schema([
                                                Placeholder::make('gran_total_activo')
                                                    ->label('Inversión Total Estimada para la Estructura:')
                                                    ->content(function ($record) {
                                                        $total = $record ? $record->conceptos->sum('importe') : 0.00;
                                                        return '$ ' . number_format($total, 2, '.', ',') . ' MXN';
                                                    })
                                                    ->extraAttributes([
                                                        'class' => 'text-xl font-black text-emerald-400 bg-emerald-500/5 p-4 rounded-xl border border-emerald-500/10 text-center'
                                                    ])
                                            ])
                                    ]),
                            ]),



                        // ─── PESTAÑA 3: EVIDENCIAS FOTOGRÁFICAS (CON LOG DE DIAGNÓSTICO) ─────
                        Tab::make('Evidencias en Campo')
                            ->icon('heroicon-m-camera')
                            ->schema([
                                Section::make('Fotografías del Inventario')
                                    ->description('Evidencias de portales de entrada y salida.')
                                    ->schema([
                                        RepeatableEntry::make('obraMenor.imagenes')
                                            ->label(false)
                                            ->grid(2)
                                            ->schema([
                                                Grid::make(3)->schema([
                                                    ImageEntry::make('url')
                                                        ->label(false)
                                                        ->height(160)
                                                        ->extraImgAttributes([
                                                            'class' => 'rounded-xl object-cover w-full shadow-sm border border-gray-700',
                                                        ])
                                                        ->columnSpan(1),

                                                    Grid::make(1)->schema([
                                                        TextEntry::make('titulo')
                                                            ->label('Ubicación / Portal')
                                                            ->weight('black')
                                                            ->color('warning'),
                                                        TextEntry::make('created_at')
                                                            ->label('Fecha de captura')
                                                            ->dateTime('d/m/Y H:i A')
                                                            ->color('gray'),
                                                    ])->columnSpan(2),
                                                ]),
                                            ])

                                    ]),
                            ]),
                    ])->columnSpanFull()
            ]);
    }
}
