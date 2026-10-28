<?php

namespace App\Services;

use App\Models\Contrato;
use App\Models\TipoActivo;
use Carbon\Carbon;


class GisEngineService
{


    public static function getContratoGisData(Contrato $contrato): array
    {

        $contrato->load([
            'carreteras.tramos' => function ($query) use ($contrato) {
                $query->where('contrato_id', $contrato->id)
                    ->orderBy('nombre_tramo');
            },
            'carreteras.tramos.activos' => function ($query) {
                $query->orderBy('cadenamiento');
            },
            'carreteras.tramos.activos.tipoActivo',
            'carreteras.tramos.activos.obraMenor.imagenes',
            'empresa',
        ]);

        // ── 2. METADATOS DEL CONTRATO ──────────────────────────────────────────
        $anioContrato  = $contrato->fecha_inicio
            ? Carbon::parse($contrato->fecha_inicio)->format('Y')
            : 'S/N';
        $nombreEmpresa = $contrato->empresa?->nombre ?? 'N/A';

        $carreterasPayload = [];
        $filtrosGlobales   = self::initFiltros();

        foreach ($contrato->carreteras as $carretera) {
            $tramosPayload = [];

            foreach ($carretera->tramos as $tramo) {
                $activosPayload = self::buildActivosPayload(
                    $tramo->activos,
                    $tramo,
                    $carretera,
                    $filtrosGlobales
                );

                $tramosPayload[] = [
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
                    'activos'      => $activosPayload,
                ];
            }

            $carreterasPayload[] = [
                'carretera_id'     => $carretera->id,
                'nombre_carretera' => $carretera->nombre,
                'polyline'         => $carretera->polyline_google,
                'origin'           => [
                    'lat' => (float) $carretera->latitud_inicio,
                    'lng' => (float) $carretera->longitud_inicio,
                ],
                'destination'      => [
                    'lat' => (float) $carretera->latitud_fin,
                    'lng' => (float) $carretera->longitud_fin,
                ],
                'tramos'           => $tramosPayload,
            ];
        }

        // ── 4. DEDUPLICAR Y ORDENAR FILTROS ───────────────────────────────────
        $filtrosGlobales = self::deduplicateFiltros($filtrosGlobales);

        return [
            'info' => [
                'nombre'  => $contrato->nombre_corto,
                'numero'  => $contrato->numero_contrato,
                'anio'    => $anioContrato,
                'empresa' => $nombreEmpresa,
                'filtros' => $filtrosGlobales,
            ],
            'carreteras' => $carreterasPayload,
        ];
    }


    private static function buildActivosPayload(
        $activos,
        $tramo,
        $carretera,
        array &$filtrosGlobales
    ): array {
        $payload = [];

        foreach ($activos as $activo) {
            $obra      = $activo->obraMenor;
            $tipoNombre = $activo->tipoActivo?->nombre ?? 'Sin tipo';
            $tipoSlug   = $activo->tipoActivo?->slug   ?? 'sin-tipo';

            $condicionRaw = strtolower($activo?->condicion ?? 'sin condición');
            $material     = $obra?->material ?? null;

            // Posición del pin (centro calculado para obras menores)
            $position = null;
            if ($activo->latitud && $activo->longitud) {
                $position = [
                    'lat' => (float) $activo->latitud,
                    'lng' => (float) $activo->longitud,
                ];
            }

            // Polyline propia de la obra menor (inicio → fin del cajón/tubo)
            $path = null;
            if ($obra && $obra->latitud_inicio && $obra->latitud_fin) {
                $path = [
                    ['lat' => (float) $obra->latitud_inicio, 'lng' => (float) $obra->longitud_inicio],
                    ['lat' => (float) $obra->latitud_fin,    'lng' => (float) $obra->longitud_fin],
                ];
            }

            // Saltamos activos sin geometría (dato corrupto)
            if (! $path && ! $position) {
                continue;
            }

            // Imágenes polimórficas de la obra menor
            $imagenesPayload = [];
            if ($obra?->imagenes) {
                foreach ($obra->imagenes as $img) {
                    if (! $img->url) {
                        continue;
                    }
                    $imagenesPayload[] = [
                        'titulo' => $img->titulo ?? 'Evidencia',
                        'url'    => $img->url,
                    ];
                }
            }

            // Acumular filtros dinámicos
            $filtrosGlobales['nombres'][]    = $tipoNombre;
            $filtrosGlobales['materiales'][] = $material;
            $filtrosGlobales['ubicaciones'][] = $activo->cadenamiento;

            // Metadatos del panel de detalles (llave → valor legible)
            $metadatos = array_filter([
                'Cadenamiento'  => $activo->cadenamiento,
                'Lado'          => $activo->lado,
                'Subtipo'       => $obra?->subtipo,
                'Material'      => $material,
                'Dimensiones'   => $obra?->dimensiones,
                'Área Hidráulica' => $obra?->area_hidraulica,
                'Long. Estructura' => $obra?->longitud_estructura,
                'Condición'     => ucfirst($condicionRaw),
            ]);

            // Las imágenes van aparte para que el JS las maneje diferente
            if (! empty($imagenesPayload)) {
                $metadatos['Imagenes'] = $imagenesPayload;
            }

            $payload[] = [
                // Datos de filtro (los usa el JS para show/hide)
                'filter_data' => [
                    'nombre'    => $tipoNombre,
                    'slug'      => $tipoSlug,
                    'material'  => $material ?? 'N/A',
                    'condicion' => $condicionRaw,
                    'ubicacion' => $activo->cadenamiento,
                ],
                // Payload visual completo
                'payload' => [
                    'id'       => $activo->id,
                    'nombre'   => $tipoNombre . ' (' . $activo->cadenamiento . ')',
                    'color'    => self::getColorByCondicion($condicionRaw),
                    'path'     => $path,
                    'position' => $position,
                    'metadatos' => $metadatos,
                    'url_view'  => "/activos/{$activo->id}",
                ],
            ];
        }

        return $payload;
    }

    private static function initFiltros(): array
    {
        return [
            'nombres'     => [],
            'materiales'  => [],
            'condiciones' => ['excelente', 'bueno', 'aceptable', 'regular'],
            'ubicaciones' => [],
        ];
    }

    private static function deduplicateFiltros(array $filtros): array
    {
        return [
            'nombres'     => array_values(array_unique(array_filter($filtros['nombres']))),
            'materiales'  => array_values(array_unique(array_filter($filtros['materiales']))),
            'condiciones' => $filtros['condiciones'],
            'ubicaciones' => array_values(array_unique(array_filter($filtros['ubicaciones']))),
        ];
    }

    private static function getColorByCondicion(?string $condicion): string
    {
        return match (strtolower(trim((string)$condicion))) {
            'excelente' => '#10b981', // Verde Esmeralda (Tailwind emerald-500)
            'bueno'     => '#7dd442', // Verde Estándar Seguro (Tailwind green-500) <--- CAMBIO AQUÍ
            'aceptable' => '#eab308', // Amarillo Seguro (Tailwind yellow-500)     <--- CAMBIO AQUÍ
            'regular'   => '#f97316', // Naranja Seguro (Tailwind orange-500)      <--- CAMBIO AQUÍ
            default     => '#6b7280', // Gris (Tailwind gray-500)
        };
    }
}
