{{-- resources/views/filament/pages/reportes.blade.php --}}
<x-filament-panels::page>

    {{-- ══════════════════════════════════════════════════════════════
         ESTILOS INTERNOS DEL MÓDULO DE REPORTES
    ══════════════════════════════════════════════════════════════ --}}
    <style>
        /* ── Tokens de color ───────────────────────────────────────── */
        :root {
            --rpt-excelente: #22c55e;
            --rpt-bueno: #84cc16;
            --rpt-aceptable: #f59e0b;
            --rpt-regular: #f97316;
            --rpt-neutro: #94a3b8;
            --rpt-card-bg: rgba(255, 255, 255, 0.04);
            --rpt-border: rgba(255, 255, 255, 0.08);
            --rpt-text-muted: #64748b;
        }

        /* ── Estado vacío ──────────────────────────────────────────── */
        .rpt-empty {
            display: none !important;
        }

        /* ── Layout principal ──────────────────────────────────────── */
        .rpt-dashboard {
            display: flex;
            flex-direction: column;
            gap: 1.75rem;
        }

        /* ── Tarjetas KPI superiores ───────────────────────────────── */
        .rpt-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
        }

        .rpt-kpi-card {
            background: var(--rpt-card-bg);
            border: 1px solid var(--rpt-border);
            border-radius: .875rem;
            padding: 1.25rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: .25rem;
        }

        .rpt-kpi-label {
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--rpt-text-muted);
        }

        .rpt-kpi-value {
            font-size: 1.65rem;
            font-weight: 800;
            line-height: 1;
            color: inherit;
        }

        .rpt-kpi-sub {
            font-size: .72rem;
            color: var(--rpt-text-muted);
            margin-top: .15rem;
        }

        /* ── Panel contenedor ─────────────────────────────────────── */
        .rpt-panel {
            background: var(--rpt-card-bg);
            border: 1px solid var(--rpt-border);
            border-radius: 1rem;
            padding: 1.5rem;
        }

        .rpt-panel-title {
            font-size: .8rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: var(--rpt-text-muted);
            margin-bottom: 1.25rem;
        }

        /* ── Gráfica de dona CSS ───────────────────────────────────── */
        .rpt-donut-wrap {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 2rem;
        }

        .rpt-donut {
            --size: 140px;
            width: var(--size);
            height: var(--size);
            border-radius: 50%;
            flex-shrink: 0;
            position: relative;
        }

        .rpt-donut-inner {
            position: absolute;
            inset: 22%;
            border-radius: 50%;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
        }

        .rpt-donut-pct {
            font-size: 1rem;
            font-weight: 800;
            line-height: 1;
        }

        .rpt-donut-lbl {
            font-size: .55rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--rpt-text-muted);
        }

        /* ── Leyenda de la dona ────────────────────────────────────── */
        .rpt-legend {
            display: flex;
            flex-direction: column;
            gap: .6rem;
            flex: 1;
            min-width: 140px;
        }

        .rpt-legend-item {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .8rem;
        }

        .rpt-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .rpt-legend-name {
            flex: 1;
        }

        .rpt-legend-count {
            font-weight: 700;
            font-size: .85rem;
        }

        /* ── Gráfica de barras horizontales CSS ────────────────────── */
        .rpt-bar-list {
            display: flex;
            flex-direction: column;
            gap: .9rem;
        }

        .rpt-bar-item {}

        .rpt-bar-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: .3rem;
            font-size: .78rem;
        }

        .rpt-bar-track {
            height: 8px;
            border-radius: 99px;
            background: rgba(255, 255, 255, 0.07);
            overflow: hidden;
        }

        .rpt-bar-fill {
            height: 100%;
            border-radius: 99px;
            transition: width .6s ease;
        }

        /* ── Índices de salud (medidor circular simple) ────────────── */
        .rpt-health-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 1rem;
            text-align: center;
        }

        .rpt-health-meter {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .4rem;
        }

        .rpt-health-ring {
            --size: 72px;
            width: var(--size);
            height: var(--size);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .95rem;
            font-weight: 800;
            position: relative;
        }

        .rpt-health-name {
            font-size: .68rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--rpt-text-muted);
        }

        /* ── Tabla de desglose ─────────────────────────────────────── */
        .rpt-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .82rem;
        }

        .rpt-table th {
            text-align: left;
            font-size: .68rem;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: var(--rpt-text-muted);
            padding: .5rem .75rem;
            border-bottom: 1px solid var(--rpt-border);
        }

        .rpt-table td {
            padding: .6rem .75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            vertical-align: middle;
        }

        .rpt-table tbody tr:last-child td {
            border-bottom: none;
        }

        .rpt-table .rpt-td-right {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }

        .rpt-table .rpt-td-mono {
            font-family: ui-monospace, monospace;
            font-size: .79rem;
        }

        .rpt-badge {
            display: inline-block;
            padding: .15rem .55rem;
            border-radius: .35rem;
            font-size: .68rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        /* ── Separador de tramo ────────────────────────────────────── */
        .rpt-tramo-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            background: linear-gradient(90deg, rgba(59, 130, 246, .12) 0%, transparent 100%);
            border-left: 3px solid #3b82f6;
            border-radius: .5rem;
            margin-bottom: 1.5rem;
        }

        .rpt-tramo-title {
            font-size: 1rem;
            font-weight: 700;
        }

        .rpt-tramo-meta {
            font-size: .75rem;
            color: var(--rpt-text-muted);
        }

        /* ── Módulo Ejecutivo Alta Dirección ──────────────────────── */
        .rpt-executive-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 1.25rem;
        }

        @media (max-width: 1024px) {
            .rpt-executive-grid {
                grid-template-columns: 1fr;
            }
        }

        .rpt-insight-box {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            justify-content: center;
        }

        .rpt-insight-item {
            background: rgba(255, 255, 255, 0.02);
            border-left: 3px solid #00e5ff;
            /* Azul neón */
            padding: 0.75rem 1rem;
            border-radius: 0 0.5rem 0.5rem 0;
        }

        .rpt-top-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .rpt-top-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--rpt-border);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .rpt-top-item:hover {
            border-color: rgba(0, 229, 255, 0.3);
            background: rgba(0, 229, 255, 0.02);
        }

        .rpt-rank-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            font-size: 0.75rem;
            font-weight: 900;
            background: #f59e0b;
            /* Amarillo neón financiero */
            color: #0f172a;
        }

        /* ── Colores por condición ─────────────────────────────────── */
        .rpt-color-excelente {
            color: var(--rpt-excelente);
        }

        .rpt-color-bueno {
            color: var(--rpt-bueno);
        }

        .rpt-color-aceptable {
            color: var(--rpt-aceptable);
        }

        .rpt-color-regular {
            color: var(--rpt-regular);
        }

        .rpt-bg-excelente {
            background: var(--rpt-excelente);
        }

        .rpt-bg-bueno {
            background: var(--rpt-bueno);
        }

        .rpt-bg-aceptable {
            background: var(--rpt-aceptable);
        }

        .rpt-bg-regular {
            background: var(--rpt-regular);
        }

        .rpt-badge-excelente {
            background: rgba(34, 197, 94, .15);
            color: var(--rpt-excelente);
        }

        .rpt-badge-bueno {
            background: rgba(132, 204, 22, .15);
            color: var(--rpt-bueno);
        }

        .rpt-badge-aceptable {
            background: rgba(245, 158, 11, .15);
            color: var(--rpt-aceptable);
        }

        .rpt-badge-regular {
            background: rgba(249, 115, 22, .15);
            color: var(--rpt-regular);
        }

        .rpt-badge-default {
            background: rgba(148, 163, 184, .1);
            color: var(--rpt-neutro);
        }

        /* ── Grid de 2 columnas de gráficas ────────────────────────── */
        .rpt-charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }

        @media (max-width: 768px) {
            .rpt-charts-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    {{-- ══════════════════════════════════════════════════════════════
         FORMULARIO DE FILTROS (siempre visible)
    ══════════════════════════════════════════════════════════════ --}}
    {{ $this->schema }}

    {{-- ══════════════════════════════════════════════════════════════
         ESTADO VACÍO — Oculto cuando $reportData tiene datos
    ══════════════════════════════════════════════════════════════ --}}
    <div
        class="{{ $this->reportData ? 'rpt-empty' : '' }} fi-section rounded-xl border border-dashed border-gray-300 dark:border-gray-700 p-10 text-center mt-4">
        <x-filament::icon icon="heroicon-o-document-chart-bar" class="mx-auto mb-3 h-12 w-12 text-gray-400" />
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Seleccione un contrato y al menos un tramo para
            visualizar el reporte.</p>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         DASHBOARD DE REPORTE — Solo visible cuando hay datos
    ══════════════════════════════════════════════════════════════ --}}
    @if ($this->reportData)
        @php
            $rd = $this->reportData;
            $condG = $rd['condicion_global'];
            $totalAct = $rd['total_activos'];
            $indices = $rd['indices_salud'];
            $mats = $rd['materiales_global'];

            // Máximo de materiales para escalar las barras
            $maxMat = collect($mats)->max('total') ?: 1;

            // Colores para las barras de materiales (paleta cíclica)
            $barColors = ['#3b82f6', '#8b5cf6', '#06b6d4', '#f59e0b', '#10b981', '#f43f5e', '#a78bfa'];

            // Helper para construir el gradiente cónico de la dona
            $donutGradient = function (array $cond) {
                $total = array_sum($cond) ?: 1;
                $colors = [
                    'excelente' => '#22c55e',
                    'bueno' => '#84cc16',
                    'aceptable' => '#f59e0b',
                    'regular' => '#f97316',
                ];
                $stops = [];
                $deg = 0;
                foreach ($colors as $key => $color) {
                    $pct = (($cond[$key] ?? 0) / $total) * 360;
                    $stops[] = "{$color} {$deg}deg " . ($deg + $pct) . 'deg';
                    $deg += $pct;
                }
                return 'conic-gradient(' . implode(', ', $stops) . ')';
            };

            $condicionLabels = [
                'excelente' => 'Excelente',
                'bueno' => 'Bueno',
                'aceptable' => 'Aceptable',
                'regular' => 'Regular',
            ];
        @endphp

        <div class="rpt-dashboard mt-4">

            <div class="rpt-panel"
                style="border-left: 4px solid #f59e0b; background: linear-gradient(90deg, rgba(245, 158, 11, 0.05) 0%, transparent 100%); padding: 1rem 1.25rem;">
                <div class="flex items-center gap-3">
                    <x-heroicon-m-wrench-screwdriver class="h-5 w-5 text-amber-400" />
                    <div>
                        <div class="text-[0.68rem] uppercase font-bold text-gray-500 tracking-wider">Empresa que realizó
                            la supervisión</div>
                        <div class="text-sm font-bold text-gray-200 mt-0.5">
                            {{ $rd['empresa_supervisora'] ?? 'Sin asignar' }}</div>
                    </div>
                </div>
            </div>

            {{-- ── KPI CARDS ─────────────────────────────────────────── --}}
            <div class="rpt-kpi-grid">
                <div class="rpt-kpi-card">
                    <span class="rpt-kpi-label">Inversión Total</span>
                    <span class="rpt-kpi-value text-blue-400">
                        ${{ number_format($rd['costo_total_proyecto'], 0, '.', ',') }}
                    </span>
                    <span class="rpt-kpi-sub">MXN — todos los tramos</span>
                </div>
                <div class="rpt-kpi-card">
                    <span class="rpt-kpi-label">Total de Activos</span>
                    <span class="rpt-kpi-value text-violet-400">{{ number_format($totalAct) }}</span>
                    <span class="rpt-kpi-sub">estructuras inventariadas</span>
                </div>
                <div class="rpt-kpi-card">
                    <span class="rpt-kpi-label">Tramos analizados</span>
                    <span class="rpt-kpi-value text-cyan-400">{{ count($rd['tramos']) }}</span>
                    <span class="rpt-kpi-sub">tramos seleccionados</span>
                </div>
                <div class="rpt-kpi-card">
                    <span class="rpt-kpi-label">En buen estado</span>
                    <span class="rpt-kpi-value text-emerald-400">
                        {{ $indices['excelente'] + $indices['bueno'] }}%
                    </span>
                    <span class="rpt-kpi-sub">Excelente + Bueno</span>
                </div>
                <div class="rpt-kpi-card">
                    <span class="rpt-kpi-label">Requieren atención</span>
                    <span class="rpt-kpi-value text-orange-400">
                        {{ $indices['regular'] }}%
                    </span>
                    <span class="rpt-kpi-sub">Regular</span>
                </div>
            </div>

            {{-- ── GRÁFICAS GLOBALES: Dona + Barras ────────────────── --}}
            <div class="rpt-charts-grid">

                {{-- Dona de Conservación Global --}}
                <div class="rpt-panel">
                    <div class="rpt-panel-title">Condición de Conservación</div>
                    <div class="rpt-donut-wrap">
                        <div class="rpt-donut" style="background: {{ $donutGradient($condG) }};">
                            <div class="rpt-donut-inner">
                                <span class="rpt-donut-pct text-white">{{ $totalAct }}</span>
                                <span class="rpt-donut-lbl">total</span>
                            </div>
                        </div>
                        <div class="rpt-legend">
                            @foreach ($condicionLabels as $key => $label)
                                <div class="rpt-legend-item">
                                    <span class="rpt-legend-dot rpt-bg-{{ $key }}"></span>
                                    <span class="rpt-legend-name text-gray-300">{{ $label }}</span>
                                    <span class="rpt-legend-count rpt-color-{{ $key }}">
                                        {{ $condG[$key] ?? 0 }}
                                        <span class="text-gray-600 font-normal text-xs">({{ $indices[$key] }}%)</span>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>


                {{-- Barras de Materiales Global --}}
                <div class="rpt-panel">
                    <div class="rpt-panel-title">Distribución por Material</div>
                    @if (empty($mats))
                        <p class="text-sm text-gray-500">Sin datos de material registrados.</p>
                    @else
                        <div class="rpt-bar-list">
                            @foreach ($mats as $i => $mat)
                                @php
                                    $pct = ($mat['total'] / $maxMat) * 100;
                                    $color = $barColors[$i % count($barColors)];
                                @endphp
                                <div class="rpt-bar-item">
                                    <div class="rpt-bar-header">
                                        <span class="text-gray-300">{{ $mat['material'] }}</span>
                                        <span class="text-gray-400">
                                            {{ $mat['total'] }} unid. &mdash;
                                            <span
                                                class="font-semibold text-white">${{ number_format($mat['monto_total'], 0, '.', ',') }}</span>
                                        </span>
                                    </div>
                                    <div class="rpt-bar-track">
                                        <div class="rpt-bar-fill"
                                            style="width:{{ $pct }}%; background:{{ $color }};"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── SECCIÓN EJECUTIVA: ALTA DIRECCIÓN (NUEVO) ─────────── --}}
            @if (!empty($rd['alta_direccion']))
                @php
                    $ad = $rd['alta_direccion'];
                @endphp
                <div class="rpt-executive-grid">

                    {{-- Columna Izquierda: Datos Clave de Obra --}}
                    <div class="rpt-panel">
                        <div class="rpt-panel-title">Datos del Reporte</div>
                        <div class="rpt-insight-box h-full flex flex-col gap-4">
                            @if ($ad['mas_costoso'])
                                <div class="rpt-insight-item" style="border-left-color: #f59e0b;">
                                    <div class="text-[0.68rem] uppercase font-bold text-gray-500 tracking-wider">
                                        Trabajo con Mayor Costo
                                    </div>
                                    <div class="text-sm font-bold text-gray-200 mt-1">
                                        {{ $ad['mas_costoso']['clave'] }}
                                    </div>
                                    <div class="text-xs text-amber-400 font-mono mt-0.5">
                                        ${{ number_format($ad['mas_costoso']['importe'], 2, '.', ',') }} MXN
                                    </div>
                                </div>
                            @endif
                            @if ($ad['mas_repetido'])
                                <div class="rpt-insight-item" style="border-left-color: #00e5ff;">
                                    <div class="text-[0.68rem] uppercase font-bold text-gray-500 tracking-wider">
                                        Material más Común en Campo
                                    </div>
                                    <div class="text-sm font-bold text-gray-200 mt-1">
                                        {{ ucfirst($ad['mas_repetido']['material']) }}
                                    </div>
                                    <div class="text-xs text-cyan-400 mt-0.5">
                                        {{ number_format($ad['mas_repetido']['total']) }} estructuras encontradas
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                    {{-- Columna Derecha: Top 3 Activos Más Críticos Financieramente --}}
                    <div class="rpt-panel">
                        <div class="rpt-panel-title">Top 3 Estructuras de Mayor Impacto Financiero</div>
                        <div class="rpt-top-list">
                            @foreach ($ad['top_criticos'] as $index => $top)
                                <div class="rpt-top-item">
                                    <div class="flex items-center gap-3">
                                        <span class="rpt-rank-badge"
                                            style="background: {{ match ($index) {0 => '#f59e0b',1 => '#cbd5e1',2 => '#b45309',default => '#64748b'} }}; text-shadow: 0 1px 2px rgba(0,0,0,0.4);">
                                            {{ $index + 1 }}
                                        </span>
                                        <div>
                                            <div class="text-sm font-bold text-gray-200">{{ $top['clave'] }}</div>
                                            <div class="text-[0.7rem] text-gray-500 uppercase tracking-wider mt-0.5">
                                                Componente: <span
                                                    class="text-gray-400 font-semibold">{{ $top['material'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-black text-cyan-400 font-mono">
                                            ${{ number_format($top['importe'], 2, '.', ',') }}
                                        </span>
                                        <div class="text-[0.65rem] text-gray-500 uppercase tracking-wide">Monto Asignado
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            @endif

            {{-- ── ÍNDICES DE SALUD (medidores) ────────────────────── --}}
            <div class="rpt-panel">
                <div class="rpt-panel-title">Índices de Salud por Condición</div>
                <div class="rpt-health-grid">
                    @foreach ($condicionLabels as $key => $label)
                        @php
                            // Forzamos buscar la clave en minúsculas por seguridad
                            $keyLower = strtolower($key);
                            $pct = $indices[$keyLower] ?? 0;

                            $color = match ($keyLower) {
                                'excelente' => '#22c55e',
                                'bueno' => '#84cc16',
                                'aceptable' => '#f59e0b',
                                'regular' => '#f97316',
                                'malo' => '#ef4444',
                                default => '#94a3b8',
                            };
                            $deg = round(($pct / 100) * 360);
                        @endphp
                        <div class="rpt-health-meter">
                            <div class="rpt-health-ring"
                                style="background: conic-gradient({{ $color }} {{ $deg }}deg, rgba(255,255,255,0.07) {{ $deg }}deg);">
                                <div
                                    style="
                            position:absolute; inset:12%;
                            border-radius:50%;
                            background:#0f172a;
                            display:flex; align-items:center; justify-content:center;
                            font-size:.8rem; font-weight:800; color:{{ $color }};
                        ">
                                    {{ $pct }}%
                                </div>
                            </div>
                            <span class="rpt-health-name">{{ $label }}</span>
                            <span class="text-xs text-gray-500">{{ $condG[$keyLower] ?? 0 }} activos</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rpt-panel">
                <div class="rpt-panel-title" style="color: #f59e0b;">Análisis de Eficiencia Presupuestal por Tramo</div>
                <div style="overflow-x:auto;">
                    <table class="rpt-table">
                        <thead>
                            <tr>
                                <th>Tramo de Autopista</th>
                                <th class="rpt-td-right">Volumen Activos</th>
                                <th class="rpt-td-right">Inversión Total</th>
                                <th class="rpt-td-right">Costo Promedio por Estructura</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rd['eficiencia_tramos'] as $ef)
                                <tr>
                                    <td class="font-semibold text-gray-200">{{ $ef['titulo'] }}</td>
                                    <td class="rpt-td-right text-gray-400 font-mono">
                                        {{ number_format($ef['total_activos']) }} u.</td>
                                    <td class="rpt-td-right font-bold text-gray-200">
                                        ${{ number_format($ef['costo_total'], 2, '.', ',') }}</td>
                                    <td class="rpt-td-right rpt-td-mono text-cyan-400 font-bold">
                                        ${{ number_format($ef['costo_promedio'], 2, '.', ',') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ── DESGLOSE POR TRAMO ───────────────────────────────── --}}
            @foreach ($rd['tramos'] as $tramo)
                @php
                    $tramoMats = $tramo['resumen_general'];
                    $maxTramoMat = collect($tramoMats)->max('total') ?: 1;
                    $condTramo = $tramo['resumen_condicion'];
                @endphp

                <div class="rpt-panel">

                    {{-- Encabezado del tramo --}}
                    <div class="rpt-tramo-header">
                        <div>
                            <div class="rpt-tramo-title text-blue-300">{{ $tramo['titulo_tramo'] }}</div>
                            <div class="rpt-tramo-meta">
                                {{ $tramo['total_activos'] }} activos &mdash;
                                Inversión: <strong>${{ number_format($tramo['costo_tramo'], 0, '.', ',') }}
                                    MXN</strong>
                            </div>
                        </div>
                    </div>

                    <div class="rpt-charts-grid" style="margin-bottom:1.5rem;">

                        {{-- Mini-dona del tramo --}}
                        <div>
                            <div class="rpt-panel-title">Condición del Tramo</div>
                            <div class="rpt-donut-wrap">
                                <div class="rpt-donut"
                                    style="--size:110px; background: {{ $donutGradient($condTramo) }};">
                                    <div class="rpt-donut-inner">
                                        <span class="rpt-donut-pct text-white"
                                            style="font-size:.85rem;">{{ $tramo['total_activos'] }}</span>
                                        <span class="rpt-donut-lbl">total</span>
                                    </div>
                                </div>
                                <div class="rpt-legend">
                                    @foreach ($condicionLabels as $key => $label)
                                        @if (($condTramo[$key] ?? 0) > 0)
                                            <div class="rpt-legend-item">
                                                <span class="rpt-legend-dot rpt-bg-{{ $key }}"></span>
                                                <span
                                                    class="rpt-legend-name text-gray-300 text-xs">{{ $label }}</span>
                                                <span
                                                    class="rpt-legend-count rpt-color-{{ $key }} text-sm">{{ $condTramo[$key] }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Mini-barras del tramo --}}
                        <div>
                            <div class="rpt-panel-title">Materiales del Tramo</div>
                            @if (empty($tramoMats))
                                <p class="text-sm text-gray-500">Sin datos de material.</p>
                            @else
                                <div class="rpt-bar-list">
                                    @foreach ($tramoMats as $i => $mat)
                                        @php
                                            $pct = ($mat['total'] / $maxTramoMat) * 100;
                                            $color = $barColors[$i % count($barColors)];
                                        @endphp
                                        <div class="rpt-bar-item">
                                            <div class="rpt-bar-header">
                                                <span class="text-gray-300 text-xs">{{ $mat['material'] }}</span>
                                                <span class="text-gray-400 text-xs">
                                                    {{ $mat['total'] }} &mdash;
                                                    <span
                                                        class="text-white font-semibold">${{ number_format($mat['monto_total'], 0, '.', ',') }}</span>
                                                </span>
                                            </div>
                                            <div class="rpt-bar-track">
                                                <div class="rpt-bar-fill"
                                                    style="width:{{ $pct }}%; background:{{ $color }};">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- ── TOP 5 CONCEPTOS MÁS REPETIDOS DEL TRAMO (NUEVO) ── --}}
                    @if (!empty($tramo['top_conceptos']))
                        <div class="rpt-panel-title" style="margin-top: 1.5rem; color: #00e5ff;">
                            Top 5 Conceptos más Frecuentes en este Tramo
                        </div>
                        <div class="rpt-bar-list"
                            style="margin-bottom: 2rem; background: rgba(0,0,0,0.15); padding: 1.25rem; border-radius: 0.75rem; border: 1px solid rgba(255,255,255,0.02);">
                            @php
                                // Tomamos la cantidad máxima para calcular el porcentaje de la barra de progreso
                                $maxCantidadConcepto = collect($tramo['top_conceptos'])->max('cantidad') ?: 1;
                            @endphp

                            @foreach ($tramo['top_conceptos'] as $index => $cpt)
                                @php
                                    $porcentajeBarra = ($cpt['cantidad'] / $maxCantidadConcepto) * 100;
                                    // Variación de color neón del azul al violeta según el ranking
                                    $colorBarra = match ($index) {
                                        0 => '#00e5ff', // Neon Blue para el #1
                                        1 => '#3b82f6',
                                        2 => '#6366f1',
                                        3 => '#8b5cf6',
                                        default => '#a855f7',
                                    };
                                @endphp
                                <div class="rpt-bar-item">
                                    <div class="rpt-bar-header" style="font-size: 0.8rem;">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-xs font-bold px-1.5 py-0.5 rounded bg-gray-800 text-gray-400">#{{ $index + 1 }}</span>
                                            <span class="text-gray-200 font-medium">{{ $cpt['concepto'] }}</span>
                                        </div>
                                        <span class="text-gray-400 text-xs tabular-nums">
                                            <span class="text-white font-bold">{{ $cpt['cantidad'] }}</span> veces
                                            &mdash;
                                            <span
                                                class="font-mono text-amber-400 font-semibold">${{ number_format($cpt['importetotal'], 2, '.', ',') }}</span>
                                        </span>
                                    </div>
                                    <div class="rpt-bar-track"
                                        style="height: 6px; background: rgba(255,255,255,0.03);">
                                        <div class="rpt-bar-fill"
                                            style="width: {{ $porcentajeBarra }}%; background: {{ $colorBarra }}; box-shadow: 0 0 8px {{ $colorBarra }}40;">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="rpt-panel-title" style="margin-top: 1.5rem; color: #ef4444;">
                        Top Activos de Mayor Prioridad Financiera en este Tramo
                    </div>
                    <div style="overflow-x:auto;">
                        <table class="rpt-table">
                            <thead>
                                <tr>
                                    <th>Cadenamiento</th>
                                    <th>Tipo de Activo</th>
                                    <th>Lado</th>
                                    <th>Dimensión / Subtipo</th>
                                    <th class="rpt-td-right">Inversión Requerida</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Tomamos los 5 activos individuales más costosos del tramo --}}
                                @foreach (collect($tramo['activos'])->sortByDesc('importe')->take(5) as $act)
                                    <tr class="hover:bg-red-500/5 transition-colors">
                                        <td class="rpt-td-mono font-bold text-amber-400">
                                            {{ $act['cadenamiento'] }}
                                        </td>
                                        <td class="text-gray-200 font-medium">{{ $act['tipo_activo'] }}</td>
                                        <td class="text-xs uppercase tracking-wider text-gray-400">{{ $act['lado'] }}
                                        </td>
                                        <td class="text-xs text-gray-500">
                                            {{ $act['subtipo'] }} <span class="text-gray-600">&bull;</span>
                                            {{ $act['dimensiones'] }}
                                        </td>
                                        <td class="rpt-td-right rpt-td-mono font-black text-rose-400">
                                            ${{ number_format($act['importe'], 2, '.', ',') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Tabla de Detalle por Condición → Material --}}
                    @if (!empty($tramo['detalle_por_condicion']))
                        <div class="rpt-panel-title" style="margin-top:1rem;">Desglose Condición / Material</div>
                        <div style="overflow-x:auto;">
                            <table class="rpt-table">
                                <thead>
                                    <tr>
                                        <th>Condición</th>
                                        <th>Material</th>
                                        <th class="rpt-td-right">Cantidad</th>
                                        <th class="rpt-td-right">Importe Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tramo['detalle_por_condicion'] as $condicion => $materiales)
                                        @php
                                            // Convertimos "Excelente" a "excelente" para que haga match con los estilos CSS
                                            $keyLower = strtolower($condicion);
                                        @endphp
                                        @foreach ($materiales as $idx => $det)
                                            <tr>
                                                @if ($idx === 0)
                                                    <td rowspan="{{ count($materiales) }}">
                                                        <span
                                                            class="rpt-badge rpt-badge-{{ $keyLower }} rpt-badge-{{ !in_array($keyLower, ['excelente', 'bueno', 'aceptable', 'regular', 'malo']) ? 'default' : '' }}">
                                                            {{ ucfirst($keyLower) }}
                                                        </span>
                                                    </td>
                                                @endif
                                                <td class="text-gray-300">{{ $det['material'] }}</td>
                                                <td class="rpt-td-right font-semibold">{{ $det['cantidad'] }}</td>
                                                <td class="rpt-td-right rpt-td-mono text-emerald-400">
                                                    ${{ number_format($det['importe_total'] ?? ($det['importetotal'] ?? 0), 2, '.', ',') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    {{-- Tabla de activos individuales del tramo --}}


                </div>{{-- /rpt-panel del tramo --}}
            @endforeach

            {{-- ── TOTAL GENERAL ────────────────────────────────────── --}}
            <div class="rpt-panel" style="border-color: rgba(59,130,246,0.3); background: rgba(59,130,246,0.05);">
                <div
                    style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
                    <div>
                        <div class="rpt-panel-title" style="margin-bottom:.25rem;">Inversión Total del Reporte</div>
                        <div class="text-xs text-gray-500">
                            {{ count($rd['tramos']) }} tramo(s) &mdash; {{ $totalAct }} activos inventariados
                        </div>
                    </div>
                    <div class="text-2xl font-black text-blue-300">
                        ${{ number_format($rd['costo_total_proyecto'], 2, '.', ',') }}
                        <span class="text-sm font-normal text-gray-500 ml-1">MXN</span>
                    </div>
                </div>
            </div>

        </div>{{-- /rpt-dashboard --}}
    @endif

</x-filament-panels::page>
