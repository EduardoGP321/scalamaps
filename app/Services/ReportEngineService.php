<?php

namespace App\Services;

use App\Models\Activo;
use App\Models\Contrato;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ReportEngineService
{
    /**
     *
     * @param  array  $filtros  Array con 'tramo_ids' (array de IDs) y 'tipo_activo_id' (int|null)
     * @return array|null       Array con toda la data masticada, o null si no hay filtros válidos.
     */
    public static function getContratoReportData(array $filtros): ?array
    {
        $tramoIds     = $filtros['tramo_ids']    ?? [];
        $tipoActivoId = $filtros['tipo_activo_id'] ?? null;

        if (empty($tramoIds)) {
            return null;
        }

        $activos = Activo::query()
            ->whereIn('tramo_id', $tramoIds)
            ->when($tipoActivoId, fn($q) => $q->where('tipo_activo_id', $tipoActivoId))
            ->with([
                'tramo',
                'tipoActivo',
                'obraMenor',
                'conceptos.catalogoConcepto',
            ])
            ->get();

        if ($activos->isEmpty()) {
            return null;
        }

        $costoTotalProyecto = $activos->reduce(function (float $carry, $activo) {
            return $carry + $activo->conceptos->sum('importe');
        }, 0.0);

        $activosPorTramo = $activos->groupBy('tramo_id');
        $tramosDesglose = [];

        // ── ENTRADA AL BUCLE DE TRAMOS INDIVIDUALES ────────────────────────
        foreach ($activosPorTramo as $tramoId => $activosDelTramo) {

            /** @var \App\Models\Tramo $tramo */
            $tramo      = $activosDelTramo->first()->tramo;
            $costoTramo = $activosDelTramo->reduce(function (float $carry, $activo) {
                return $carry + $activo->conceptos->sum('importe');
            }, 0.0);

            // ── CORRECCIÓN: El cálculo del Top 5 debe ejecutarse AQUÍ adentro por tramo ──
            $conceptosTramo = [];
            foreach ($activosDelTramo as $activo) {
                foreach ($activo->conceptos as $concepto) {
                    if (! $concepto->catalogoConcepto) {
                        continue;
                    }

                    $nombreConcepto  = $concepto->catalogoConcepto->concepto ?? 'Concepto Vial General';
                    $importeConcepto = (float) ($concepto->importe ?? 0.0);

                    if (! isset($conceptosTramo[$nombreConcepto])) {
                        $conceptosTramo[$nombreConcepto] = [
                            'concepto'     => $nombreConcepto,
                            'cantidad'     => 0,
                            'importetotal' => 0.0,
                        ];
                    }

                    $conceptosTramo[$nombreConcepto]['cantidad']++;
                    $conceptosTramo[$nombreConcepto]['importetotal'] += $importeConcepto;
                }
            }

            // Ordenamos y extraemos el Top 5 individual de este tramo específico
            $topConceptosTramo = collect($conceptosTramo)
                ->sortByDesc('cantidad')
                ->take(5)
                ->values()
                ->toArray();
            // ───────────────────────────────────────────────────────────────────────────

            $resumenCondicion = [];
            $condicionesValidas = ['excelente', 'bueno', 'aceptable', 'regular', 'malo', 'sin_datos'];

            foreach ($activosDelTramo as $activo) {
                // ◄ CORRECCIÓN: Ahora se lee directo desde el modelo Activo, no de obraMenor
                $condicion = trim(strtolower($activo->condicion ?? 'sin_datos'));

                if ($condicion === '') {
                    $condicion = 'sin_datos';
                }

                $resumenCondicion[$condicion] = ($resumenCondicion[$condicion] ?? 0) + 1;
            }

            // Garantizamos que todas las claves esperadas existan con valor 0 si no hay datos.
            foreach ($condicionesValidas as $clave) {
                if (! isset($resumenCondicion[$clave])) {
                    $resumenCondicion[$clave] = 0;
                }
            }

            $resumenGeneral = [];
            foreach ($activosDelTramo as $activo) {
                if ($activo->obraMenor) {
                    $material     = $activo->obraMenor->material ?? 'Sin Material';
                    $importeActivo = $activo->conceptos->sum('importe');

                    if (! isset($resumenGeneral[$material])) {
                        $resumenGeneral[$material] = [
                            'material'    => $material,
                            'total'       => 0,
                            'monto_total' => 0.0,
                        ];
                    }

                    $resumenGeneral[$material]['total']++;
                    $resumenGeneral[$material]['monto_total'] += $importeActivo;
                }
            }
            $resumenGeneral = array_values($resumenGeneral);

            $detallePorCondicion = [];

            foreach ($activosDelTramo as $activo) {
                // Obtenemos la condición directa del activo
                $condicion = strtolower($activo->condicion ?? 'sin_datos');
                if ($condicion === '') {
                    $condicion = 'sin_datos';
                }

                // CORRECCIÓN: Si no hay obraMenor, le asignamos un texto genérico en lugar de romper el ciclo
                $material = $activo->obraMenor->material ?? 'sin_material';
                if ($material === '') {
                    $material = 'sin_material';
                }

                $importeActivo = $activo->conceptos->sum('importe');

                if (! isset($detallePorCondicion[$condicion])) {
                    $detallePorCondicion[$condicion] = [];
                }

                if (! isset($detallePorCondicion[$condicion][$material])) {
                    $detallePorCondicion[$condicion][$material] = [
                        'material'      => ucfirst($material), // Lo capitaliza visualmente en la tabla
                        'cantidad'      => 0,
                        'importe_total' => 0.0,
                    ];
                }

                $detallePorCondicion[$condicion][$material]['cantidad']++;
                $detallePorCondicion[$condicion][$material]['importe_total'] += $importeActivo;
            }

            // Normalizamos los valores internos a arrays indexados.
            foreach ($detallePorCondicion as $condicionKey => $materiales) {
                $detallePorCondicion[$condicionKey] = array_values($materiales);
            }
            $activosPlanos = $activosDelTramo->map(function ($activo) {
                return [
                    'id'           => $activo->id,
                    'cadenamiento' => $activo->cadenamiento,
                    'tipo_activo'  => $activo->tipoActivo->nombre ?? '—',
                    'condicion'    => $activo->obraMenor->condicion   ?? '—',
                    'material'     => $activo->obraMenor->material     ?? '—',
                    'subtipo'      => $activo->obraMenor->subtipo      ?? '—',
                    'dimensiones'  => $activo->obraMenor->dimensiones  ?? '—',
                    'lado'         => $activo->lado                    ?? '—',
                    'importe'      => $activo->conceptos->sum('importe'),
                ];
            })->values()->toArray();

            $tramosDesglose[] = [
                'tramo_id'              => $tramoId,
                'titulo_tramo'          => $tramo->nombre_tramo ?? "Tramo #{$tramoId}",
                'costo_tramo'           => $costoTramo,
                'total_activos'         => $activosDelTramo->count(),
                'resumen_condicion'     => $resumenCondicion,
                'resumen_general'       => $resumenGeneral,
                'detalle_por_condicion' => $detallePorCondicion,
                'top_conceptos'         => $topConceptosTramo, // Mapeado correctamente de manera individual
                'activos'               => $activosPlanos,
            ];
        }

        // ── PROCESAMIENTO GLOBAL POST-LOOP ─────────────────────────────────────
        $condicionGlobal = [
            'excelente' => 0,
            'bueno' => 0,
            'aceptable' => 0,
            'regular' => 0,
            'malo' => 0,
            'sin_datos' => 0 // ◄ Agregado
        ];
        foreach ($tramosDesglose as $tramo) {
            foreach ($condicionGlobal as $clave => $_) {
                $condicionGlobal[$clave] += $tramo['resumen_condicion'][$clave] ?? 0;
            }
        }

        $totalActivos = array_sum($condicionGlobal);
        $indicesSalud = [];
        foreach ($condicionGlobal as $clave => $cantidad) {
            $indicesSalud[$clave] = $totalActivos > 0 ? round(($cantidad / $totalActivos) * 100, 1) : 0;
        }

        $materialesGlobal = [];
        foreach ($tramosDesglose as $tramo) {
            foreach ($tramo['resumen_general'] as $mat) {
                $key = $mat['material'];
                if (! isset($materialesGlobal[$key])) {
                    $materialesGlobal[$key] = ['material' => $key, 'total' => 0, 'monto_total' => 0.0];
                }
                $materialesGlobal[$key]['total']       += $mat['total'];
                $materialesGlobal[$key]['monto_total'] += $mat['monto_total'];
            }
        }
        $materialesGlobal = array_values($materialesGlobal);

        $coleccionConceptos = collect($tramosDesglose)->flatMap(function ($t) {
            return collect($t['activos'])->map(function ($act) {
                return [
                    'clave' => $act['tipo_activo'] ?? 'Concepto Vial',
                    'material' => $act['material'] ?? 'General',
                    'cantidad' => $act['cantidad'] ?? 1,
                    'importe' => $act['importe'] ?? 0.00
                ];
            });
        });

        $masCostoso = $coleccionConceptos->sortByDesc('importe')->first();
        $masRepetido = $coleccionConceptos->groupBy('material')->sortByDesc(fn($g) => $g->count())->first();
        $topCriticos = $coleccionConceptos->sortByDesc('importe')->take(3)->values()->toArray();
        $eficienciaTramos = collect($tramosDesglose)->map(function ($t) {
            return [
                'titulo'        => $t['titulo_tramo'],
                'total_activos' => $t['total_activos'],
                'costo_total'   => $t['costo_tramo'],
                'costo_promedio' => $t['total_activos'] > 0 ? ($t['costo_tramo'] / $t['total_activos']) : 0.0
            ];
        })->sortByDesc('costo_total')->values()->toArray();

        $nombreEmpresa = 'No asignada';
        $primerTramoId = collect($tramoIds)->first();
        if ($primerTramoId) {
            $contrato = Contrato::whereHas('tramos', fn($q) => $q->where('id', $primerTramoId))
                ->with('empresa')
                ->first();
            if ($contrato && $contrato->empresa) {
                $nombreEmpresa = $contrato->empresa->name;
            }
        }

        return [
            'empresa_supervisora'  => $nombreEmpresa,
            'costo_total_proyecto' => $costoTotalProyecto,
            'eficiencia_tramos' => $eficienciaTramos,
            'total_activos'        => $totalActivos,
            'condicion_global'     => $condicionGlobal,
            'indices_salud'        => $indicesSalud,
            'materiales_global'    => $materialesGlobal,
            'tramos'               => $tramosDesglose,
            'alta_direccion' => [
                'mas_costoso'  => $masCostoso,
                'mas_repetido' => $masRepetido ? [
                    'material' => $masRepetido->first()['material'] ?? 'Sin Datos',
                    'total'    => $masRepetido->count()
                ] : null,
                'top_criticos' => $topCriticos
            ]
        ];
    }
}
