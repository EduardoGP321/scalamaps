<?php

namespace App\Services;

use App\Models\Contrato;
use App\Models\TipoActivo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class GisEngineService
{
    public static function getContratoGisData(Contrato $contrato): array
    {
        // 1. Carga optimizada: Traemos tramos y sus activos con las relaciones necesarias
        $tramos = $contrato->tramos()
            ->with(['activos.tipoActivo', 'activos.obraMenor'])
            ->get();

        // 2. Metadatos del Contrato para el Header del Mapa
        $anioContrato = $contrato->fecha_inicio ? Carbon::parse($contrato->fecha_inicio)->format('Y') : 'S/N';
        $nombreEmpresa = $contrato->empresa->nombre ?? 'N/A';

        // 3. Generación de Filtros Dinámicos
        $nombresActivos = TipoActivo::pluck('nombre')->toArray();

        $materiales = $tramos->flatMap(fn($t) => $t->activos)
            ->map(fn($a) => $a->obraMenor?->material)
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // Estandarizamos condiciones para evitar errores de mayúsculas en JS
        $condiciones = ['excelente', 'bueno', 'regular', 'malo', 'crítico'];

        $ubicaciones = $tramos->flatMap(fn($t) => $t->activos)
            ->pluck('kilometraje')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // 4. Rutas de los Tramos (Polylines de la carretera)
        $routesMain = $tramos->map(fn($tramo) => [
            'tramo_id'     => $tramo->id,
            'nombre_tramo' => $tramo->nombre_tramo,
            'polyline'     => $tramo->polyline_google,
            'origin'       => [
                'lat' => (float) $tramo->latitud_inicio,
                'lng' => (float) $tramo->longitud_inicio,
            ],
            'destination'  => [
                'lat' => (float) $tramo->latitud_fin,
                'lng' => (float) $tramo->longitud_fin,
            ],
        ])->values()->toArray();

        // 5. Procesamiento de Activos (Puntos y Líneas)
        $assets = $tramos->flatMap(fn($tramo) => $tramo->activos->map(function ($activo) use ($tramo) {
            $obra = $activo->obraMenor;
            $tipoNombre = $activo->tipoActivo->nombre ?? 'Sin tipo';

            // Usamos strtolower para que coincida con nuestro array de colores
            $condicionRaw = strtolower($obra?->condicion ?? 'bueno');
            $color = self::getColorByCondicion($condicionRaw);

            $path = null;
            $position = null;

            // Lógica de Geometría:
            // Si tiene datos de inicio/fin en obraMenor -> Polilínea
            if ($obra && $obra->latitud_inicio && $obra->latitud_fin) {
                $path = [
                    ['lat' => (float) $obra->latitud_inicio, 'lng' => (float) $obra->longitud_inicio],
                    ['lat' => (float) $obra->latitud_fin,    'lng' => (float) $obra->longitud_fin],
                ];
            }

            // Siempre enviamos la posición base del Activo para el Marcador (Pin)
            if ($activo->latitud && $activo->longitud) {
                $position = [
                    'lat' => (float) $activo->latitud,
                    'lng' => (float) $activo->longitud,
                ];
            }

            if (!$path && !$position) return null;

            return [
                'filter_data' => [
                    'nombre'    => $tipoNombre,
                    'material'  => $obra?->material ?? 'N/A',
                    'condicion' => $condicionRaw,
                    'ubicacion' => $activo->kilometraje,
                ],
                'payload' => [
                    'id'        => $activo->id,
                    'nombre'    => $tipoNombre . ' (' . $activo->kilometraje . ')',
                    'color'     => $color,
                    'path'      => $path,     // Si es null, el JS dibuja marcador
                    'position'  => $position, // Posición base del marcador
                    'metadatos' => [
                        'Tramo'         => $tramo->nombre_tramo,
                        'Cadenamiento'  => $activo->kilometraje,
                        'Lado'          => $activo->lado,
                        'Subtipo'       => $obra?->subtipo,
                        'Material'      => $obra?->material,
                        'Dimensiones'   => $obra?->dimensiones,
                        'Condición'     => ucfirst($condicionRaw),
                        'Observaciones' => $obra?->observaciones,
                        'Imagenes'      => [
                            [
                                'titulo' => 'Entrada',
                                'url' => $obra?->foto_entrada ? Storage::url($obra->foto_entrada) : null
                            ],
                            [
                                'titulo' => 'Salida',
                                'url' => $obra?->foto_salida ? Storage::url($obra->foto_salida) : null
                            ],
                        ],
                    ],
                    'url_view' => "/admin/activos/{$activo->id}",
                ],
            ];
        }))->filter()->values()->toArray();

        return [
            'info' => [
                'nombre'  => $contrato->nombre_corto,
                'numero'  => $contrato->numero_contrato,
                'anio'    => $anioContrato,
                'empresa' => $nombreEmpresa,
                'filtros' => [
                    'nombres'     => $nombresActivos,
                    'materiales'  => $materiales,
                    'condiciones' => $condiciones,
                    'ubicaciones' => $ubicaciones,
                ],
            ],
            'routes_main' => $routesMain,
            'assets'      => $assets,
        ];
    }

    private static function getColorByCondicion(?string $condicion): string
    {
        return match (strtolower($condicion)) {
            'excelente', 'bueno' => '#10b981', // Verde
            'regular', 'aceptable' => '#f59e0b', // Naranja
            'malo', 'crítico'    => '#ef4444', // Rojo
            default              => '#6b7280', // Gris
        };
    }
}
