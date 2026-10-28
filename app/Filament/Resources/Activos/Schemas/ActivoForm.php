<?php

namespace App\Filament\Resources\Activos\Schemas;

use App\Models\CatalogoConcepto;
use Daljo25\FilamentTablerIcons\Enums\TablerIcon;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;

class ActivoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Registro de Activo')
                    ->tabs([
                        Tab::make('Ubicación y Tipo')
                            ->icon(Heroicon::MapPin)
                            ->schema([
                                Grid::make(2)->schema([

                                    Select::make('tramo_id')
                                        ->relationship('tramo', 'nombre_tramo')
                                        ->prefixIcon(TablerIcon::Road)
                                        ->required()
                                        ->searchable()
                                        ->preload()
                                        ->columnSpanFull(),

                                    Select::make('tipo_activo_id')
                                        ->label('Tipo de Activo')
                                        ->relationship('tipoActivo', 'nombre')
                                        ->prefixIcon(TablerIcon::Category)
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function ($state, $set) {
                                            if ($state == 1) {
                                                $set('latitud', null);
                                                $set('longitud', null);
                                            } else {
                                                $set('obra_menor', []);
                                            }
                                        }),

                                    TextInput::make('cadenamiento')
                                        ->label('Cadenamiento')
                                        ->prefixIcon(TablerIcon::SignRight)
                                        ->placeholder('Ej. 9+571')
                                        ->required(),

                                    Select::make('condicion')
                                        ->label('Condición')
                                        ->prefixIcon(TablerIcon::HeartRateMonitor)
                                        ->options([
                                            'excelente' => 'Excelente',
                                            'bueno' => 'Bueno',
                                            'aceptable' => 'Aceptable',
                                            'regular' => 'Regular',
                                        ])
                                        ->required(),

                                    Select::make('lado')
                                        ->label('Cuerpo')
                                        ->prefixIcon(TablerIcon::LayoutSidebar)
                                        ->options([
                                            'a' => 'A',
                                            'b' => 'B',
                                            'sobre_eje' => 'Sobre Eje',
                                        ])->required(),

                                ]),

                                Section::make('Coordenadas del Marcador')
                                    ->description(fn(Get $get) => $get('tipo_activo_id') == 1
                                        ? 'El PIN se calculará automáticamente en el centro geométrico de la Obra Menor.'
                                        : 'Ubicación puntual para el PIN del mapa.')
                                    ->columns(3)
                                    ->schema([

                                        TextInput::make('latitud')
                                            ->label('Latitud del Marcador')
                                            ->prefixIcon(TablerIcon::MapPin)
                                            ->placeholder('Ej. 18.835412')
                                            ->numeric()
                                            ->disabled(fn(Get $get) => $get('tipo_activo_id') == 1)
                                            ->hidden(fn(Get $get) => $get('tipo_activo_id') == 1),

                                        TextInput::make('longitud')
                                            ->label('Longitud del Marcador')
                                            ->prefixIcon(TablerIcon::MapPin)
                                            ->placeholder('Ej. -96.421584')
                                            ->numeric()
                                            ->disabled(fn(Get $get) => $get('tipo_activo_id') == 1)
                                            ->hidden(fn(Get $get) => $get('tipo_activo_id') == 1),

                                        Placeholder::make('calculo_automatico')
                                            ->label('Coordenadas GIS')
                                            ->content('Auto-calculadas mediante el eje de entrada/salida')
                                            ->visible(fn(Get $get) => $get('tipo_activo_id') == 1)
                                            ->columnSpan(2),



                                    ]),
                            ]),

                        Tab::make('Detalles Técnicos')
                            ->icon(TablerIcon::Settings)
                            ->schema([
                                Section::make('Atributos de Obra Menor')
                                    ->description('Eje geométrico, dimensiones y estado de conservación de la estructura.')
                                    ->hidden(fn(Get $get) => $get('tipo_activo_id') != 1)
                                    ->relationship('obraMenor')
                                    ->schema([
                                        Grid::make(2)->schema([

                                            TextInput::make('latitud_inicio')
                                                ->label('Latitud de Entrada')
                                                ->prefixIcon(TablerIcon::MapPin)
                                                ->placeholder('Ej. 19.043284')
                                                ->numeric()
                                                ->required(fn(Get $get) => $get('tipo_activo_id') == 1),

                                            TextInput::make('longitud_inicio')
                                                ->label('Longitud de Entrada')
                                                ->prefixIcon(TablerIcon::MapPin)
                                                ->placeholder('Ej. -98.201934')
                                                ->numeric()
                                                ->required(fn(Get $get) => $get('tipo_activo_id') == 1),

                                            TextInput::make('latitud_fin')
                                                ->label('Latitud de Salida')
                                                ->prefixIcon(TablerIcon::MapPinShare)
                                                ->placeholder('Ej. 19.043512')
                                                ->numeric()
                                                ->required(fn(Get $get) => $get('tipo_activo_id') == 1),

                                            TextInput::make('longitud_fin')
                                                ->label('Longitud de Salida')
                                                ->prefixIcon(TablerIcon::MapPinShare)
                                                ->placeholder('Ej. -98.201567')
                                                ->numeric()
                                                ->required(fn(Get $get) => $get('tipo_activo_id') == 1),

                                            Select::make('subtipo')
                                                ->label('Tipo de Obra Menor')
                                                ->prefixIcon(TablerIcon::BuildingBridge2)
                                                ->options([
                                                    'tubo' => 'Tubo',
                                                    'bateria_2_tubos' => 'Batería 2 tubos',
                                                    'bateria_3_tubos' => 'Batería 3 tubos',
                                                    'losa' => 'Losa',
                                                    'cajon' => 'Cajón',
                                                    'boveda' => 'Bóveda',
                                                    'sifon' => 'Sifón',
                                                    'mixto' => 'Mixto',
                                                ])->required(fn(Get $get) => $get('tipo_activo_id') == 1)
                                                ->columnSpanFull(),

                                            Select::make('material')
                                                ->label('Material')
                                                ->prefixIcon(TablerIcon::Hammer)
                                                ->options([
                                                    'concreto' => 'Concreto',
                                                    'acero' => 'Acero',
                                                    'maposteria' => 'Mampostería',
                                                    'lamina' => 'Lámina',
                                                    'ads' => 'ADS',
                                                ])->required(fn(Get $get) => $get('tipo_activo_id') == 1),

                                            TextInput::make('area_hidraulica')
                                                ->label('Área Hidráulica (m²)')
                                                ->prefixIcon(TablerIcon::Droplet)
                                                ->placeholder('Ej. 1.13')
                                                ->numeric(),

                                            TextInput::make('altura_terraplen_entrada')
                                                ->label('Altura del Terraplén en Entrada (m)')
                                                ->prefixIcon(TablerIcon::Cone)
                                                ->placeholder('Ej. 2.50')
                                                ->numeric(),

                                            TextInput::make('altura_terraplen_salida')
                                                ->label('Altura del Terraplén en Salida (m)')
                                                ->prefixIcon(TablerIcon::Cone)
                                                ->placeholder('Ej. 2.10')
                                                ->numeric(),

                                            TextInput::make('longitud_estructura')
                                                ->label('Longitud de la Estructura (m)')
                                                ->prefixIcon(TablerIcon::Ruler2)
                                                ->placeholder('Ej. 14.50')
                                                ->numeric(),

                                            TextInput::make('esviaje')
                                                ->label('Esviaje (°)')
                                                ->prefixIcon(TablerIcon::Angle)
                                                ->placeholder('Ej. 15')
                                                ->numeric(),

                                            TextInput::make('pendiente')
                                                ->label('Pendiente (%)')
                                                ->prefixIcon(TablerIcon::TrendingUp)
                                                ->placeholder('Ej. 1.5')
                                                ->numeric(),

                                            TextInput::make('dimensiones')
                                                ->label('Dimensiones')
                                                ->prefixIcon(TablerIcon::Dimensions)
                                                ->placeholder('Ej. 1.20m x 1.20m o Ø 1.05m'),

                                            Textarea::make('Observaciones')
                                                ->label('Observaciones Técnicas de Campo')
                                                ->placeholder('Ej. Presenta azolve moderado en la entrada y desprendimiento menor de concreto en los aleros de salida...')
                                                ->rows(3)
                                                ->columnSpanFull()
                                        ])
                                    ]),
                                Section::make('Atributos de Señalamiento')
                                    ->hidden(fn(Get $get) => $get('tipo_activo_id') != 2)
                                    ->schema([
                                        TextInput::make('codigo_senal')->placeholder('SR-9'),
                                        Select::make('estado_reflejante')
                                            ->options(['Alto', 'Medio', 'Bajo']),
                                    ]),

                                Section::make('Aviso')
                                    ->hidden(fn(Get $get) => $get('tipo_activo_id') != null)
                                    ->schema([
                                        Placeholder::make('info')
                                            ->content('Por favor, selecciona un tipo de activo en la pestaña anterior.')
                                    ])
                            ]),

                        Tab::make('Fotografías en campo')
                            ->icon(Heroicon::Camera)
                            ->schema([
                                Section::make('Evidencias Fotográficas de Inspección')
                                    ->description('Suba obligatoriamente la fotografía de Entrada y la de Salida de la estructura correspondiente.')
                                    ->schema([

                                        Repeater::make('imagenes_dinamicas') // Nombre genérico para desvincularlo de magias ciegas
                                            ->schema([

                                                Select::make('titulo')
                                                    ->label('Tipo de Fotografía (Ubicación)')
                                                    ->options([
                                                        'entrada' => 'Fotografía de Entrada',
                                                        'salida' => 'Fotografía de Salida',
                                                    ])
                                                    ->placeholder('— Seleccione la perspectiva —')
                                                    ->required()
                                                    ->columnSpan(2),

                                                FileUpload::make('ruta')
                                                    ->label('Subir Evidencia')
                                                    ->disk('public')
                                                    ->directory('activos/obra_menor')
                                                    ->visibility('public')
                                                    ->image()
                                                    ->required()
                                                    ->imageEditor()
                                                    ->optimize('webp', 80)
                                                    ->columnSpan(2),

                                            ])
                                            ->columns(4)
                                            ->grid(2)
                                            ->label('Registro Fotográfico Oficial')
                                            ->createItemButtonLabel('Agregar Fotografía')
                                            ->minItems(0)
                                            ->maxItems(2)
                                            ->reorderable(false)
                                            ->collapsible()
                                            ->afterStateHydrated(function ($component, $record) {
                                                if (! $record) {
                                                    $component->state([]);
                                                    return;
                                                }
                                                $modeloHijo = $record->obraMenor ?? ($record->puente ?? null);
                                                if (! $modeloHijo) {
                                                    $component->state([]);
                                                    return;
                                                }
                                                $imagenes = DB::table('imagenes')
                                                    ->where('imageable_id', $modeloHijo->id)
                                                    ->where('imageable_type', get_class($modeloHijo))
                                                    ->get(['ruta', 'titulo'])
                                                    ->map(fn($img) => [
                                                        'ruta' => $img->ruta,
                                                        'titulo' => $img->titulo,
                                                    ])
                                                    ->toArray();

                                                $component->state($imagenes);
                                            })

                                            ->saveRelationshipsUsing(function ($component, $record) {
                                                if (! $record) return;

                                                // Detectamos dinámicamente qué modelo hijo tiene datos en este Activo
                                                $modeloHijo = $record->obraMenor ?? ($record->puente ?? null);

                                                if (! $modeloHijo) {
                                                    return; // Si no hay estructura guardada, no hay a quién asociarle fotos
                                                }

                                                $state = $component->getState() ?? [];
                                                $targetClass = get_class($modeloHijo);
                                                $targetId = $modeloHijo->id;

                                                $syncData = [];
                                                foreach ($state as $item) {
                                                    if (! empty($item['ruta'])) {

                                                        // ── CORRECCIÓN PRINCIPAL PARA EL ARRAY TO STRING CONVERSION ──
                                                        // Si Filament devuelve la ruta empaquetada en un array, extraemos el string puro.
                                                        $rutaPura = is_array($item['ruta'])
                                                            ? (reset($item['ruta']) ?: null)
                                                            : $item['ruta'];

                                                        // Si por alguna razón viene vacío tras la limpieza, saltamos este registro
                                                        if (! $rutaPura) {
                                                            continue;
                                                        }

                                                        $syncData[] = [
                                                            'ruta'           => $rutaPura, // <-- Ahora es un String garantizado
                                                            'titulo'         => $item['titulo'] ?? null,
                                                            'imageable_id'   => $targetId,
                                                            'imageable_type' => $targetClass,
                                                            'created_at'     => now(),
                                                            'updated_at'     => now(),
                                                        ];
                                                    }
                                                }

                                                // Limpia fotos anteriores del modelo hijo específico y guarda las nuevas de forma segura
                                                DB::table('imagenes')
                                                    ->where('imageable_id', $targetId)
                                                    ->where('imageable_type', $targetClass)
                                                    ->delete();

                                                if (! empty($syncData)) {
                                                    DB::table('imagenes')->insert($syncData);
                                                }
                                            }),

                                    ]),
                            ]),

                        Tab::make('Conceptos')
                            ->icon('heroicon-m-currency-dollar')
                            ->schema([
                                Section::make('Catálogo de Conceptos')
                                    ->description('Asigna los conceptos de obra')
                                    ->schema([

                                        Repeater::make('conceptos')
                                            ->relationship('conceptos')
                                            ->live()
                                            ->schema([

                                                // 1. Selector de Concepto
                                                Select::make('catalogo_concepto_id')
                                                    ->label('Concepto del Catálogo')
                                                    ->relationship('catalogoConcepto', 'clave')
                                                    ->searchable()
                                                    ->preload()
                                                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->clave} - {$record->concepto}")
                                                    ->required()
                                                    ->live() // Detecta el cambio de concepto para traer el precio unitario
                                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                        // Buscamos el concepto seleccionado en el catálogo maestro
                                                        $concepto = CatalogoConcepto::find($state);
                                                        $precio = $concepto ? (float) $concepto->precio_unitario : 0.00;

                                                        // Guardamos temporalmente el precio unitario en un campo de estado para usarlo en la multiplicación
                                                        $set('precio_unitario_oculto', $precio);

                                                        // Multiplicamos el precio por la cantidad actual y lo asignamos a 'importe' (Tu columna real)
                                                        $cantidad = (float) $get('cantidad') ?? 0;
                                                        $set('importe', $cantidad * $precio);
                                                    })
                                                    ->columnSpan(2),

                                                // 2. Cantidad (Tu columna real)
                                                TextInput::make('cantidad')
                                                    ->label('Cantidad')
                                                    ->numeric()
                                                    ->default(1)
                                                    ->required()
                                                    ->live(onBlur: true) // Recalcula el importe cuando el usuario cambia el número o sale del input
                                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                        $precio = (float) $get('precio_unitario_oculto') ?? 0;
                                                        $cantidad = (float) $state ?? 0;

                                                        // Guarda el resultado en tu columna real 'importe'
                                                        $set('importe', $cantidad * $precio);
                                                    })
                                                    ->columnSpan(1),

                                                // 3. Celda Visual del Subtotal de la Partida
                                                Placeholder::make('importe_vista')
                                                    ->label('Subtotal Partida')
                                                    ->content(function (Get $get) {
                                                        // Lee directamente tu columna 'importe' para darle formato de dinero en la pantalla
                                                        $importe = (float) $get('importe') ?? 0.00;
                                                        return '$ ' . number_format($importe, 2);
                                                    })
                                                    ->columnSpan(1),

                                                // Campo de estado en memoria (No se guarda en la BD) para retener el precio unitario de la fila
                                                TextInput::make('precio_unitario_oculto')
                                                    ->hidden()
                                                    ->dehydrated(false),

                                                // Tu columna real 'importe'. Se queda oculta porque el usuario no la edita, la calcula el sistema
                                                Hidden::make('importe')
                                                    ->required()
                                            ])
                                            ->columns(4)
                                            ->columnSpanFull()
                                            ->defaultItems(0)
                                            ->collapsible(),

                                        // ─── EL MARCADOR DEL GRAN TOTAL ACUMULADO DEL ACTIVO ───
                                        Section::make()
                                            ->schema([
                                                Placeholder::make('gran_total_activo')
                                                    ->label('Total para este Activo:')
                                                    ->content(function (Get $get) {
                                                        // Recorremos las filas del repetidor buscando tu columna 'importe' para acumular el Gran Total
                                                        $filas = $get('conceptos') ?? [];
                                                        $total = 0.00;

                                                        foreach ($filas as $fila) {
                                                            $total += (float) ($fila['importe'] ?? 0.00);
                                                        }

                                                        return '$ ' . number_format($total, 2) . ' MXN';
                                                    })
                                                    ->extraAttributes([
                                                        'class' => 'text-xl font-black text-success-600 dark:text-success-400 bg-success-500/5 p-4 rounded-xl border border-success-500/10'
                                                    ])
                                            ])
                                    ]),
                            ]),
                    ])->columnSpanFull()
            ]);
    }
}
