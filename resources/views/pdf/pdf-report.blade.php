<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>
        /* ══════════════════════════════════════════════════════════════
   SCALAMAPS — PDF TEMPLATE  |  Tamaño Carta: 215.9mm × 279.4mm
   Motor: DomPDF 2.x  |  Laravel 12
   ══════════════════════════════════════════════════════════════ */

        /* ── 1. RESET & BOX MODEL ─────────────────────────────────── */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ── 2. FUENTES (DomPDF bundled: DejaVu; fallback seguro) ──── */
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 9pt;
            color: #1a1a2e;
            background: #ffffff;
            line-height: 1.45;
        }

        /* ── 3. PALETA SCALAMAPS ──────────────────────────────────── */
        /*
    --azul-oscuro   : #1a1a2e   (texto base, fondo portada)
    --azul-medio    : #16213e   (headers sección)
    --azul-acento   : #0f3460   (bordes, subtítulos)
    --naranja       : #e94560   (accent, badges críticos)
    --verde         : #2ecc71   (excelente / bueno)
    --amarillo      : #f39c12   (aceptable / regular)
    --rojo          : #e74c3c   (malo)
    --gris-claro    : #f4f6f8   (fondo cards)
    --gris-borde    : #d1d5db   (bordes suaves)
    --blanco        : #ffffff
*/

        /* ── 4. PÁGINA — dimensiones carta en DomPDF ─────────────── */
        @page {
            margin: 0;
            size: 215.9mm 279.4mm;
        }

        /* Página interior con márgenes estándar */
        @page interior {
            margin: 18mm 16mm 22mm 16mm;
            size: 215.9mm 279.4mm;
        }

        /* ── 5. SALTOS DE PÁGINA ──────────────────────────────────── */
        .page-break {
            page-break-after: always;
        }

        /* ── 6. UTILIDADES DE LAYOUT ──────────────────────────────── */
        .w-full {
            width: 100%;
        }

        .w-half {
            width: 50%;
        }

        .w-third {
            width: 33.33%;
        }

        .w-two-thirds {
            width: 66.66%;
        }

        .float-left {
            float: left;
        }

        .float-right {
            float: right;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        /* ── 7. TIPOGRAFÍA ────────────────────────────────────────── */
        .text-xs {
            font-size: 7pt;
        }

        .text-sm {
            font-size: 8pt;
        }

        .text-base {
            font-size: 9pt;
        }

        .text-md {
            font-size: 11pt;
        }

        .text-lg {
            font-size: 13pt;
        }

        .text-xl {
            font-size: 16pt;
        }

        .text-2xl {
            font-size: 20pt;
        }

        .text-3xl {
            font-size: 26pt;
        }

        .font-bold {
            font-weight: bold;
        }

        .font-normal {
            font-weight: normal;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        /* ── 8. COLORES DE TEXTO ──────────────────────────────────── */
        .c-blanco {
            color: #ffffff;
        }

        .c-azul-oscuro {
            color: #1a1a2e;
        }

        .c-azul-acento {
            color: #0f3460;
        }

        .c-naranja {
            color: #e94560;
        }

        .c-verde {
            color: #2ecc71;
        }

        .c-amarillo {
            color: #f39c12;
        }

        .c-rojo {
            color: #e74c3c;
        }

        .c-gris {
            color: #6b7280;
        }

        /* ── 9. BADGES DE CONDICIÓN ───────────────────────────────── */
        .badge {
            display: inline-block;
            padding: 1pt 5pt;
            border-radius: 3pt;
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3pt;
        }

        .badge-excelente {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-bueno {
            background: #bbf7d0;
            color: #166534;
        }

        .badge-aceptable {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-regular {
            background: #fed7aa;
            color: #9a3412;
        }

        .badge-malo {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-sin-datos {
            background: #f3f4f6;
            color: #6b7280;
        }

        /* ── 10. CARDS ────────────────────────────────────────────── */
        .card {
            background: #f4f6f8;
            border: 0.5pt solid #d1d5db;
            border-radius: 4pt;
            padding: 8pt 10pt;
        }

        .card-accent {
            border-left: 3pt solid #0f3460;
        }

        .card-danger {
            border-left: 3pt solid #e94560;
        }

        /* ── 11. TABLAS ───────────────────────────────────────────── */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #1a1a2e;
            color: #ffffff;
            font-size: 7.5pt;
            font-weight: bold;
            padding: 4pt 6pt;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.3pt;
        }

        td {
            font-size: 8pt;
            padding: 3.5pt 6pt;
            border-bottom: 0.3pt solid #e5e7eb;
            vertical-align: middle;
        }

        tr:nth-child(even) td {
            background: #f9fafb;
        }

        /* ── 12. BARRA HORIZONTAL ─────────────────────────────────── */
        .progress-track {
            background: #e5e7eb;
            border-radius: 3pt;
            height: 7pt;
            width: 100%;
            overflow: hidden;
        }

        .progress-fill {
            height: 7pt;
            border-radius: 3pt;
            background: #0f3460;
        }

        .progress-fill-danger {
            background: #e94560;
        }

        .progress-fill-warning {
            background: #f39c12;
        }

        .progress-fill-success {
            background: #2ecc71;
        }

        /* ── 13. SEPARADOR ────────────────────────────────────────── */
        .divider {
            border: none;
            border-top: 0.5pt solid #d1d5db;
            margin: 6pt 0;
        }

        .divider-accent {
            border-top: 1pt solid #0f3460;
        }

        /* ── 14. HEADER DE SECCIÓN (páginas interiores) ───────────── */
        .section-header {
            background: #16213e;
            color: #ffffff;
            padding: 5pt 10pt;
            border-radius: 3pt;
            margin-bottom: 8pt;
        }

        .section-header h2 {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }

        .section-header span {
            font-size: 7.5pt;
            color: #94a3b8;
        }

        /* ── 15. FOOTER (páginas interiores) ──────────────────────── */
        .page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 16mm;
            background: #1a1a2e;
            padding: 4pt 16mm;
        }

        .page-footer table td {
            color: #94a3b8;
            font-size: 7pt;
            border: none;
            background: transparent;
            padding: 2pt 0;
        }

        /* ── 16. NUMERACIÓN (DomPDF script inline) ────────────────── */
        /* Usar en footer: <span class="page-num"></span> */
    </style>
</head>

<body>

    {{-- ══════════════════════════════════════════════════════════
     PÁGINA 1 — PORTADA
     Campos consumidos de $reporte:
       - $reporte['costo_total_proyecto']   → inversión total
       - $reporte['total_activos']           → universo de activos
       - $reporte['indices_salud']           → distribución % por condición
       - $reporte['tramos']                  → listado de tramos analizados
     Los alias 'inversion_formateada' y 'salud_general_global'
     se calculan aquí mismo para no tocar el Service.
     ══════════════════════════════════════════════════════════ --}}

    @php
        /* ── Alias de conveniencia ── */
        $inversionFormateada = '$' . number_format($reporte['costo_total_proyecto'], 2, '.', ',');
        $totalActivos = $reporte['total_activos'];
        $indicesSalud = $reporte['indices_salud']; // ['excelente'=>%, 'bueno'=>%, …]
        $condicionGlobal = $reporte['condicion_global']; // conteos raw
        $tramos = $reporte['tramos'];
        $altaDireccion = $reporte['alta_direccion'];
        $materialesGlobal = $reporte['materiales_global'];

        /* ── Salud general global: condición dominante ── */
        $condicionesOrden = ['excelente' => 5, 'bueno' => 4, 'aceptable' => 3, 'regular' => 2, 'malo' => 1];
        $saludGeneralGlobal = 'sin_datos';
        $maxPct = -1;
        foreach ($indicesSalud as $clave => $pct) {
            if ($pct > $maxPct) {
                $maxPct = $pct;
                $saludGeneralGlobal = $clave;
            }
        }

        /* ── Colores y etiquetas de salud ── */
        $saludConfig = [
            'excelente' => ['label' => 'EXCELENTE', 'color' => '#2ecc71', 'bg' => '#d1fae5', 'fg' => '#065f46'],
            'bueno' => ['label' => 'BUENO', 'color' => '#27ae60', 'bg' => '#bbf7d0', 'fg' => '#166534'],
            'aceptable' => ['label' => 'ACEPTABLE', 'color' => '#f39c12', 'bg' => '#fef9c3', 'fg' => '#854d0e'],
            'regular' => ['label' => 'REGULAR', 'color' => '#e67e22', 'bg' => '#fed7aa', 'fg' => '#9a3412'],
            'malo' => ['label' => 'MALO', 'color' => '#e74c3c', 'bg' => '#fee2e2', 'fg' => '#991b1b'],
            'sin_datos' => ['label' => 'S/D', 'color' => '#9ca3af', 'bg' => '#f3f4f6', 'fg' => '#6b7280'],
        ];
        $saludActual = $saludConfig[$saludGeneralGlobal] ?? $saludConfig['sin_datos'];

        /* ── Fecha del reporte ── */
        $fechaReporte = now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY');
        $anioReporte = now()->year;
    @endphp

    {{-- ╔══════════════════════════════════════════════════════════╗
     ║  PORTADA — Sin márgenes, ocupa la hoja completa         ║
     ╚══════════════════════════════════════════════════════════╝ --}}
    <div
        style="
    width: 215.9mm;
    height: 279.4mm;
    position: relative;
    overflow: hidden;
    background: #1a1a2e;
">

        {{-- ── Banda diagonal decorativa (SVG inline) ──────────────── --}}
        <div style="position: absolute; top: 0; right: 0; width: 100%; height: 100%; overflow: hidden;">
            <svg width="100%" height="100%" viewBox="0 0 816 1056" preserveAspectRatio="none"
                xmlns="http://www.w3.org/2000/svg">
                <!-- Fondo base -->
                <rect width="816" height="1056" fill="#1a1a2e" />
                <!-- Plano diagonal derecho -->
                <polygon points="460,0 816,0 816,700" fill="#16213e" opacity="0.9" />
                <!-- Acento naranja oblicuo -->
                <polygon points="420,0 450,0 816,620 816,580" fill="#e94560" opacity="0.6" />
                <!-- Línea acento azul -->
                <polygon points="380,0 395,0 816,540 816,520" fill="#0f3460" opacity="0.8" />
                <!-- Rejilla sutil (carreteras) -->
                <line x1="0" y1="800" x2="816" y2="500" stroke="#ffffff" stroke-width="0.3"
                    opacity="0.05" />
                <line x1="0" y1="900" x2="816" y2="600" stroke="#ffffff" stroke-width="0.3"
                    opacity="0.05" />
                <line x1="0" y1="700" x2="816" y2="400" stroke="#ffffff" stroke-width="0.3"
                    opacity="0.05" />
                <!-- Punto de origen (intersección) -->
                <circle cx="816" cy="580" r="5" fill="#e94560" opacity="0.8" />
            </svg>
        </div>

        {{-- ── Logo + Nombre sistema (top-left) ─────────────────────── --}}
        <div style="
        position: absolute;
        top: 14mm;
        left: 16mm;
        right: 16mm;
    ">
            <table style="width: auto; border-collapse: collapse;">
                <tr>
                    <td
                        style="
                    background: #e94560;
                    color: #ffffff;
                    font-size: 10pt;
                    font-weight: bold;
                    padding: 4pt 8pt;
                    letter-spacing: 1.5pt;
                    text-transform: uppercase;
                    border: none;
                    vertical-align: middle;
                ">
                        SCALAMAPS</td>
                    <td
                        style="
                    background: transparent;
                    color: #94a3b8;
                    font-size: 7.5pt;
                    padding: 4pt 0 4pt 8pt;
                    letter-spacing: 0.5pt;
                    border: none;
                    vertical-align: middle;
                ">
                        Sistema de Gestión de Activos Viales</td>
                </tr>
            </table>
        </div>

        {{-- ── Línea separadora top ─────────────────────────────────── --}}
        <div
            style="
        position: absolute;
        top: 28mm;
        left: 16mm;
        right: 80mm;
        height: 0.5pt;
        background: #e94560;
        opacity: 0.7;
    ">
        </div>

        {{-- ── Título principal del reporte ────────────────────────── --}}
        <div style="
        position: absolute;
        top: 58mm;
        left: 16mm;
        right: 90mm;
    ">
            <p
                style="
            font-size: 7.5pt;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1.5pt;
            margin-bottom: 4pt;
        ">
                Reporte Técnico de Conservación</p>

            <p
                style="
            font-size: 22pt;
            font-weight: bold;
            color: #ffffff;
            line-height: 1.2;
            margin-bottom: 3pt;
        ">
                ANÁLISIS<br>INTEGRAL DE<br>ACTIVOS VIALES</p>

            <div
                style="
            width: 30mm;
            height: 2pt;
            background: #e94560;
            margin: 6pt 0;
        ">
            </div>

            <p style="
            font-size: 8pt;
            color: #cbd5e1;
            line-height: 1.5;
        ">
                Diagnóstico de condición física,<br>
                distribución de inversión y hallazgos<br>
                por tramo de carretera federal.
            </p>
        </div>

        {{-- ── Tarjeta de Inversión (KPI principal) ─────────────────── --}}
        <div
            style="
        position: absolute;
        top: 118mm;
        left: 16mm;
        width: 82mm;
        background: rgba(15, 52, 96, 0.75);
        border: 0.5pt solid #0f3460;
        border-left: 3pt solid #e94560;
        padding: 8pt 10pt;
    ">
            <p
                style="font-size: 7pt; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.8pt; margin-bottom: 3pt;">
                Inversión Total del Análisis
            </p>
            <p style="font-size: 17pt; font-weight: bold; color: #ffffff; line-height: 1;">
                {{ $inversionFormateada }}
            </p>
            <p style="font-size: 7pt; color: #64748b; margin-top: 2pt;">MXN</p>
        </div>

        {{-- ── Tarjeta de Universo de Activos ───────────────────────── --}}
        <div
            style="
        position: absolute;
        top: 118mm;
        left: 106mm;
        width: 50mm;
        background: rgba(15, 52, 96, 0.75);
        border: 0.5pt solid #0f3460;
        border-left: 3pt solid #0f3460;
        padding: 8pt 10pt;
    ">
            <p
                style="font-size: 7pt; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.8pt; margin-bottom: 3pt;">
                Activos Evaluados
            </p>
            <p style="font-size: 17pt; font-weight: bold; color: #ffffff; line-height: 1;">
                {{ number_format($totalActivos) }}
            </p>
            <p style="font-size: 7pt; color: #64748b; margin-top: 2pt;">Total de activos</p>
        </div>

        {{-- ── Badge Salud General Global ───────────────────────────── --}}
        <div
            style="
        position: absolute;
        top: 148mm;
        left: 16mm;
        width: 82mm;
        background: rgba(15, 52, 96, 0.5);
        border: 0.5pt solid #1e3a5f;
        padding: 7pt 10pt;
        text-align: center;
    ">
            <p
                style="font-size: 7pt; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.8pt; margin-bottom: 4pt;">
                Salud General Global
            </p>
            <span
                style="
            display: inline-block;
            background: {{ $saludActual['bg'] }};
            color: {{ $saludActual['fg'] }};
            font-size: 9pt;
            font-weight: bold;
            padding: 2pt 12pt;
            letter-spacing: 1pt;
            text-transform: uppercase;
        ">{{ $saludActual['label'] }}</span>
            <p style="font-size: 7pt; color: #64748b; margin-top: 3pt;">
                {{ $maxPct }}% del total en esta condición
            </p>
        </div>

        {{-- ── Distribución de condición (mini barras) ──────────────── --}}
        <div
            style="
        position: absolute;
        top: 148mm;
        left: 106mm;
        width: 50mm;
        background: rgba(15, 52, 96, 0.5);
        border: 0.5pt solid #1e3a5f;
        padding: 7pt 8pt;
    ">
            <p
                style="font-size: 7pt; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.8pt; margin-bottom: 5pt;">
                Distribución
            </p>
            @php
                $condColors = [
                    'excelente' => '#2ecc71',
                    'bueno' => '#27ae60',
                    'aceptable' => '#f39c12',
                    'regular' => '#e67e22',
                    'malo' => '#e74c3c',
                ];
            @endphp
            @foreach ($indicesSalud as $cond => $pct)
                @if (isset($condColors[$cond]))
                    <div style="margin-bottom: 3pt;">
                        <table style="margin-bottom: 1pt; border-collapse: collapse;">
                            <tr>
                                <td
                                    style="font-size: 6.5pt; color: #cbd5e1; text-transform: capitalize;
                                   padding: 0; border: none; background: transparent; width: 50mm;">
                                    {{ ucfirst($cond) }}
                                </td>
                                <td
                                    style="font-size: 6.5pt; color: #94a3b8; text-align: right;
                                   padding: 0; border: none; background: transparent;">
                                    {{ $pct }}%
                                </td>
                            </tr>
                        </table>
                        <div style="background: #1e3a5f; height: 4pt; width: 100%; border-radius: 1pt;">
                            <div
                                style="
                        background: {{ $condColors[$cond] }};
                        height: 4pt;
                        width: {{ $pct }}%;
                        border-radius: 1pt;
                        max-width: 100%;
                    ">
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- ── Lista de Tramos Analizados ────────────────────────────── --}}
        <div
            style="
        position: absolute;
        top: 185mm;
        left: 16mm;
        right: 16mm;
        background: rgba(22, 33, 62, 0.85);
        border: 0.5pt solid #1e3a5f;
        padding: 8pt 10pt;
    ">
            <p
                style="font-size: 7pt; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.8pt; margin-bottom: 6pt;">
                Tramos Incluidos en el Análisis
            </p>
            <table style="border-collapse: collapse; width: 100%;">
                <tr>
                    <th
                        style="
                    background: #0f3460;
                    color: #94a3b8;
                    font-size: 6.5pt;
                    padding: 3pt 6pt;
                    text-align: left;
                    font-weight: bold;
                    text-transform: uppercase;
                    letter-spacing: 0.4pt;
                    border: none;
                    width: 55%;
                ">
                        Tramo</th>
                    <th
                        style="
                    background: #0f3460;
                    color: #94a3b8;
                    font-size: 6.5pt;
                    padding: 3pt 6pt;
                    text-align: right;
                    font-weight: bold;
                    text-transform: uppercase;
                    letter-spacing: 0.4pt;
                    border: none;
                    width: 20%;
                ">
                        Activos</th>
                    <th
                        style="
                    background: #0f3460;
                    color: #94a3b8;
                    font-size: 6.5pt;
                    padding: 3pt 6pt;
                    text-align: right;
                    font-weight: bold;
                    text-transform: uppercase;
                    letter-spacing: 0.4pt;
                    border: none;
                    width: 25%;
                ">
                        Inversión MXN</th>
                </tr>
                @foreach ($tramos as $tramo)
                    <tr>
                        <td
                            style="
                    color: #e2e8f0;
                    font-size: 7.5pt;
                    padding: 3.5pt 6pt;
                    border-bottom: 0.3pt solid #1e3a5f;
                    background: transparent;
                ">
                            {{ $tramo['titulo_tramo'] }}</td>
                        <td
                            style="
                    color: #94a3b8;
                    font-size: 7.5pt;
                    padding: 3.5pt 6pt;
                    text-align: right;
                    border-bottom: 0.3pt solid #1e3a5f;
                    background: transparent;
                ">
                            {{ number_format($tramo['total_activos']) }}</td>
                        <td
                            style="
                    color: #94a3b8;
                    font-size: 7.5pt;
                    padding: 3.5pt 6pt;
                    text-align: right;
                    border-bottom: 0.3pt solid #1e3a5f;
                    background: transparent;
                ">
                            ${{ number_format($tramo['costo_tramo'], 0, '.', ',') }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        {{-- ── Footer de portada ─────────────────────────────────────── --}}
        <div
            style="
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 14mm;
        background: #0a0f1e;
        padding: 0 16mm;
    ">
            <table style="height: 14mm; width: 100%; border-collapse: collapse;">
                <tr>
                    <td
                        style="
                    color: #64748b;
                    font-size: 7pt;
                    vertical-align: middle;
                    border: none;
                    background: transparent;
                    padding: 0;
                ">
                        Generado el {{ $fechaReporte }}</td>
                    <td
                        style="
                    color: #64748b;
                    font-size: 7pt;
                    vertical-align: middle;
                    text-align: right;
                    border: none;
                    background: transparent;
                    padding: 0;
                ">
                        SCALAMAPS © {{ $anioReporte }} — Uso Interno Confidencial</td>
                </tr>
            </table>
        </div>

    </div>{{-- /portada --}}

    {{-- ══════════════════════════════════════════════════════════
     FIN PÁGINA 1 — Salto de página para la siguiente sección.
     Descomenta cuando agregues la Página 2 (Resumen Ejecutivo).
     ══════════════════════════════════════════════════════════ --}}
    {{-- <div class="page-break"></div> --}}

    {{-- ════════════════════════════════════════════════════════════════
     PÁGINAS 2 y 3 — Añadir DESPUÉS del bloque de Portada en pdf-template.blade.php
     Pegar a partir de la línea donde está el comentario "FIN PÁGINA 1".
     Descomenta el page-break de cierre de portada antes de pegar esto.
     ════════════════════════════════════════════════════════════════ --}}

    {{-- ╔══════════════════════════════════════════════════════════════╗
     ║  PÁGINA 2 — RESUMEN EJECUTIVO                               ║
     ╚══════════════════════════════════════════════════════════════╝ --}}
    @php
        /* Paleta condición reutilizada en esta página */
        $condMeta = [
            'excelente' => ['label' => 'Excelente', 'bg' => '#d1fae5', 'fg' => '#065f46', 'bar' => '#2ecc71'],
            'bueno' => ['label' => 'Bueno', 'bg' => '#bbf7d0', 'fg' => '#166534', 'bar' => '#27ae60'],
            'aceptable' => ['label' => 'Aceptable', 'bg' => '#fef9c3', 'fg' => '#854d0e', 'bar' => '#f39c12'],
            'regular' => ['label' => 'Regular', 'bg' => '#fed7aa', 'fg' => '#9a3412', 'bar' => '#e67e22'],
            'malo' => ['label' => 'Malo', 'bg' => '#fee2e2', 'fg' => '#991b1b', 'bar' => '#e74c3c'],
        ];

        /*
         * NOTA TÉCNICA: El Service no expone una sub-ruta
         * $t['activos']['alcantarillas']['resumen_condicion'].
         * Los conteos de condición viven en $t['resumen_condicion'] directamente,
         * ya consolidados para todos los activos del tramo.
         * Si en el futuro el Service segmenta por tipo de activo,
         * cambiar la variable $rc abajo y el resto del loop no necesita edición.
         */
        $costoTotal = $reporte['costo_total_proyecto'];
    @endphp

    <div style="page-break-before: always; padding: 14mm 16mm 0 16mm;">

        {{-- ── Header de sección ─────────────────────────────────────── --}}
        <div
            style="
        background: #16213e;
        border-left: 4pt solid #e94560;
        padding: 6pt 10pt;
        margin-bottom: 10pt;
    ">
            <table style="border-collapse: collapse; width: 100%;">
                <tr>
                    <td style="border: none; background: transparent; padding: 0; vertical-align: middle;">
                        <p
                            style="font-size: 11pt; font-weight: bold; color: #ffffff;
                               text-transform: uppercase; letter-spacing: 0.6pt; margin: 0;">
                            Resumen Ejecutivo
                        </p>
                        <p style="font-size: 7pt; color: #94a3b8; margin: 1pt 0 0 0; letter-spacing: 0.3pt;">
                            Comparativa consolidada por tramo · Condición física e inversión
                        </p>
                    </td>
                    <td
                        style="border: none; background: transparent; padding: 0;
                            text-align: right; vertical-align: middle; width: 42mm;">
                        <p style="font-size: 7pt; color: #64748b; margin: 0;">Inversión analizada</p>
                        <p style="font-size: 10pt; font-weight: bold; color: #e94560; margin: 0;">
                            ${{ number_format($costoTotal, 0, '.', ',') }}
                        </p>
                        <p style="font-size: 6.5pt; color: #64748b; margin: 0;">MXN</p>
                    </td>
                </tr>
            </table>
        </div>

        {{-- ── Tabla comparativa de tramos ──────────────────────────── --}}
        <table style="border-collapse: collapse; width: 100%; margin-bottom: 12pt;">
            <thead>
                <tr>
                    <th
                        style="background:#1a1a2e; color:#94a3b8; font-size:6.5pt; padding:4pt 6pt;
                            text-align:left; font-weight:bold; text-transform:uppercase;
                            letter-spacing:0.4pt; border:none; width:28%;">
                        Tramo</th>
                    <th
                        style="background:#1a1a2e; color:#94a3b8; font-size:6.5pt; padding:4pt 6pt;
                            text-align:center; font-weight:bold; text-transform:uppercase;
                            letter-spacing:0.4pt; border:none; width:8%;">
                        Activos</th>
                    <th
                        style="background:#1a1a2e; color:#94a3b8; font-size:6.5pt; padding:4pt 6pt;
                            text-align:left; font-weight:bold; text-transform:uppercase;
                            letter-spacing:0.4pt; border:none; width:36%;">
                        Distribución por Condición</th>
                    <th
                        style="background:#1a1a2e; color:#94a3b8; font-size:6.5pt; padding:4pt 6pt;
                            text-align:right; font-weight:bold; text-transform:uppercase;
                            letter-spacing:0.4pt; border:none; width:14%;">
                        Inversión MXN</th>
                    <th
                        style="background:#1a1a2e; color:#94a3b8; font-size:6.5pt; padding:4pt 6pt;
                            text-align:center; font-weight:bold; text-transform:uppercase;
                            letter-spacing:0.4pt; border:none; width:14%;">
                        Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reporte['tramos'] as $t)
                    @php
                        /*
                         * Ruta canónica del Service:
                         *   $t['resumen_condicion'] => ['excelente'=>N,'bueno'=>N,…]
                         * (Ver nota técnica arriba sobre la ruta alternativa pedida.)
                         */
                        $rc = $t['resumen_condicion']; // conteos por condición
                        $totalTramo = array_sum($rc) ?: 1;

                        /* Condición dominante para el badge de estado */
                        $domCond = 'sin_datos';
                        $domMax = -1;
                        foreach ($rc as $ck => $cv) {
                            if ($cv > $domMax) {
                                $domMax = $cv;
                                $domCond = $ck;
                            }
                        }
                        $domMeta = $condMeta[$domCond] ?? ['label' => 'S/D', 'bg' => '#f3f4f6', 'fg' => '#6b7280'];

                        $pctPresupuesto = $costoTotal > 0 ? round(($t['costo_tramo'] / $costoTotal) * 100, 1) : 0;
                    @endphp
                    <tr>
                        {{-- Nombre del tramo --}}
                        <td
                            style="font-size:8pt; color:#1a1a2e; padding:5pt 6pt;
                            border-bottom:0.4pt solid #e5e7eb; vertical-align:middle;">
                            <span style="font-weight:bold;">{{ $t['titulo_tramo'] }}</span>
                        </td>

                        {{-- Cantidad activos --}}
                        <td
                            style="font-size:8pt; color:#374151; padding:5pt 6pt;
                            text-align:center; border-bottom:0.4pt solid #e5e7eb;
                            vertical-align:middle;">
                            {{ number_format($t['total_activos']) }}
                        </td>

                        {{-- Badges de condición --}}
                        <td style="padding:5pt 6pt; border-bottom:0.4pt solid #e5e7eb; vertical-align:middle;">
                            <table style="border-collapse:collapse; width:100%;">
                                <tr>
                                    @foreach ($condMeta as $ck => $cm)
                                        @php $cnt = $rc[$ck] ?? 0; @endphp
                                        <td
                                            style="padding:0 1pt; border:none; background:transparent;
                                        text-align:center; width:20%;">
                                            <span
                                                style="
                                    display:inline-block;
                                    background: {{ $cm['bg'] }};
                                    color: {{ $cm['fg'] }};
                                    font-size:6pt;
                                    font-weight:bold;
                                    padding:1pt 3pt;
                                    min-width:14pt;
                                    text-align:center;
                                ">{{ $cnt }}</span>
                                            <br>
                                            <span style="font-size:5.5pt; color:#9ca3af;">
                                                {{ substr($cm['label'], 0, 3) }}
                                            </span>
                                        </td>
                                    @endforeach
                                </tr>
                            </table>
                        </td>

                        {{-- Inversión del tramo --}}
                        <td
                            style="font-size:8pt; color:#1a1a2e; font-weight:bold;
                            padding:5pt 6pt; text-align:right;
                            border-bottom:0.4pt solid #e5e7eb; vertical-align:middle;">
                            ${{ number_format($t['costo_tramo'], 0, '.', ',') }}
                        </td>

                        {{-- Badge estado dominante --}}
                        <td
                            style="padding:5pt 6pt; text-align:center;
                            border-bottom:0.4pt solid #e5e7eb; vertical-align:middle;">
                            <span
                                style="
                        display:inline-block;
                        background: {{ $domMeta['bg'] }};
                        color: {{ $domMeta['fg'] }};
                        font-size:6.5pt;
                        font-weight:bold;
                        padding:2pt 5pt;
                        text-transform:uppercase;
                        letter-spacing:0.3pt;
                    ">{{ $domMeta['label'] }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- ── Participación Presupuestal por Tramo (barras horizontales) ── --}}
        <div
            style="
        background: #f4f6f8;
        border: 0.5pt solid #d1d5db;
        border-left: 3pt solid #0f3460;
        padding: 8pt 10pt;
        margin-bottom: 10pt;
    ">
            <p
                style="font-size: 8pt; font-weight: bold; color: #16213e;
                   text-transform: uppercase; letter-spacing: 0.5pt; margin-bottom: 8pt;">
                Participación Presupuestal por Tramo
            </p>

            @foreach ($reporte['tramos'] as $t)
                @php
                    $pctPresupuesto = $costoTotal > 0 ? round(($t['costo_tramo'] / $costoTotal) * 100, 1) : 0;

                    /* Color de barra según peso presupuestal */
                    if ($pctPresupuesto >= 40) {
                        $barColor = '#e94560';
                    } elseif ($pctPresupuesto >= 20) {
                        $barColor = '#0f3460';
                    } else {
                        $barColor = '#3b82f6';
                    }
                @endphp
                <div style="margin-bottom: 6pt;">
                    <table style="border-collapse: collapse; width: 100%; margin-bottom: 2pt;">
                        <tr>
                            <td
                                style="font-size: 7.5pt; color: #374151; font-weight: bold;
                                padding: 0; border: none; background: transparent;">
                                {{ $t['titulo_tramo'] }}
                            </td>
                            <td
                                style="font-size: 7.5pt; color: #6b7280; text-align: right;
                                padding: 0; border: none; background: transparent; width: 60pt;">
                                {{ $pctPresupuesto }}% &nbsp;·&nbsp;
                                ${{ number_format($t['costo_tramo'], 0, '.', ',') }}
                            </td>
                        </tr>
                    </table>
                    {{-- Track de la barra --}}
                    <div style="background: #e5e7eb; height: 8pt; width: 100%; border-radius: 2pt; overflow: hidden;">
                        <div
                            style="
                    background: {{ $barColor }};
                    height: 8pt;
                    width: {{ min($pctPresupuesto, 100) }}%;
                    border-radius: 2pt;
                    max-width: 100%;
                ">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ── Resumen de condición global (mini tabla de totales) ─── --}}
        <div
            style="
        background: #f4f6f8;
        border: 0.5pt solid #d1d5db;
        padding: 7pt 10pt;
    ">
            <p
                style="font-size: 7.5pt; font-weight: bold; color: #16213e;
                   text-transform: uppercase; letter-spacing: 0.5pt; margin-bottom: 6pt;">
                Consolidado Global de Condición
            </p>
            <table style="border-collapse: collapse; width: 100%;">
                <tr>
                    @foreach ($condMeta as $ck => $cm)
                        @php $gCnt = $reporte['condicion_global'][$ck] ?? 0; @endphp
                        <td style="text-align: center; padding: 0 3pt; border: none; background: transparent;">
                            <div
                                style="
                        background: {{ $cm['bg'] }};
                        border: 0.5pt solid {{ $cm['bar'] }};
                        padding: 5pt 3pt;
                        border-radius: 3pt;
                    ">
                                <p
                                    style="font-size: 12pt; font-weight: bold;
                                   color: {{ $cm['fg'] }}; margin: 0; line-height: 1;">
                                    {{ $gCnt }}
                                </p>
                                <p
                                    style="font-size: 6pt; color: {{ $cm['fg'] }};
                                   text-transform: uppercase; margin: 2pt 0 0 0; letter-spacing: 0.3pt;">
                                    {{ $cm['label'] }}
                                </p>
                            </div>
                        </td>
                    @endforeach
                </tr>
            </table>
        </div>

        {{-- ── Footer de página interior ─────────────────────────────── --}}
        <div
            style="
        position: fixed;
        bottom: 0; left: 0; right: 0;
        height: 12mm;
        background: #1a1a2e;
        padding: 0 16mm;
    ">
            <table style="height: 12mm; width: 100%; border-collapse: collapse;">
                <tr>
                    <td
                        style="font-size: 6.5pt; color: #64748b; vertical-align: middle;
                            border: none; background: transparent; padding: 0;">
                        SCALAMAPS · Reporte de Análisis Integral de Activos Viales
                    </td>
                    <td
                        style="font-size: 6.5pt; color: #64748b; text-align: right;
                            vertical-align: middle; border: none; background: transparent; padding: 0;">
                        Página 2 · Resumen Ejecutivo
                    </td>
                </tr>
            </table>
        </div>

    </div>{{-- /página 2 --}}


    {{-- ╔══════════════════════════════════════════════════════════════╗
     ║  PÁGINA 3 — ANÁLISIS DE ALTA DIRECCIÓN                     ║
     ╚══════════════════════════════════════════════════════════════╝ --}}
    @php
        $ad = $reporte['alta_direccion'];
        $masCostoso = $ad['mas_costoso'] ?? null; // array: clave, material, cantidad, importe
        $masRepetido = $ad['mas_repetido'] ?? null; // array: material, total
        $topCriticos = $ad['top_criticos'] ?? []; // array de arrays: clave, material, cantidad, importe

        /*
         * El Service construye $coleccionConceptos a partir de $t['activos'],
         * mapeando: clave => tipo_activo, material, cantidad (1 por defecto), importe.
         * No hay un campo "descripción larga" explícito; se usa 'material' como
         * descripción operativa. Si el Service expone 'descripcion' en el futuro,
         * reemplazar las referencias a 'material' abajo.
         */

    @endphp

    <div style="page-break-before: always; padding: 14mm 16mm 16mm 16mm;">

        {{-- ── Header de sección ─────────────────────────────────────── --}}
        <div
            style="
        background: #16213e;
        border-left: 4pt solid #e94560;
        padding: 6pt 10pt;
        margin-bottom: 12pt;
    ">
            <p
                style="font-size: 11pt; font-weight: bold; color: #ffffff;
                   text-transform: uppercase; letter-spacing: 0.6pt; margin: 0 0 1pt 0;">
                Análisis de Alta Dirección
            </p>
            <p style="font-size: 7pt; color: #94a3b8; margin: 0; letter-spacing: 0.3pt;">
                Indicadores estratégicos para la toma de decisiones · Dirección General CAPUFE
            </p>
        </div>

        {{-- ── Fila KPI: Mayor Impacto + Mayor Recurrencia ──────────── --}}
        <table style="border-collapse: collapse; width: 100%; margin-bottom: 12pt;">
            <tr>

                {{-- KPI 1: Concepto de Mayor Impacto Financiero --}}
                <td
                    style="width: 49%; vertical-align: top; padding: 0 5pt 0 0; border: none; background: transparent;">
                    <div
                        style="
                    background: #fff7f7;
                    border: 0.5pt solid #fecaca;
                    border-top: 3pt solid #e94560;
                    padding: 9pt 10pt;
                    height: 100%;
                ">
                        <p
                            style="font-size: 6.5pt; color: #9ca3af; text-transform: uppercase;
                               letter-spacing: 0.8pt; margin: 0 0 2pt 0;">
                            Concepto de Mayor Impacto Financiero
                        </p>
                        <p style="font-size: 7pt; color: #6b7280; margin: 0 0 6pt 0;">
                            Trabajo unitario con mayor erogación acumulada
                        </p>

                        @if ($masCostoso)
                            {{-- Código / Clave --}}
                            <div
                                style="
                        background: #1a1a2e;
                        display: inline-block;
                        padding: 2pt 7pt;
                        margin-bottom: 5pt;
                    ">
                                <span
                                    style="font-size: 7pt; color: #e94560; font-weight: bold;
                                     letter-spacing: 0.5pt;">
                                    {{ strtoupper($masCostoso['clave'] ?? '—') }}
                                </span>
                            </div>

                            {{-- Descripción operativa --}}
                            <p
                                style="font-size: 8.5pt; font-weight: bold; color: #1a1a2e;
                               margin: 0 0 4pt 0; line-height: 1.3;">
                                {{ $masCostoso['material'] ?? '—' }}
                            </p>

                            <hr style="border: none; border-top: 0.4pt solid #fecaca; margin: 5pt 0;">

                            <table style="border-collapse: collapse; width: 100%;">
                                <tr>
                                    <td style="border: none; background: transparent; padding: 0;">
                                        <p
                                            style="font-size: 6.5pt; color: #9ca3af; margin: 0; text-transform: uppercase;">
                                            Monto Acumulado
                                        </p>
                                        <p
                                            style="font-size: 14pt; font-weight: bold; color: #e94560;
                                           margin: 1pt 0 0 0; line-height: 1;">
                                            ${{ number_format($masCostoso['importe'] ?? 0, 0, '.', ',') }}
                                        </p>
                                        <p style="font-size: 6.5pt; color: #9ca3af; margin: 1pt 0 0 0;">MXN</p>
                                    </td>
                                    <td
                                        style="border: none; background: transparent; padding: 0;
                                        text-align: right; vertical-align: bottom; width: 40%;">
                                        <p
                                            style="font-size: 6.5pt; color: #9ca3af; margin: 0; text-transform: uppercase;">
                                            Cantidad aplicada
                                        </p>
                                        <p
                                            style="font-size: 11pt; font-weight: bold; color: #374151;
                                           margin: 1pt 0 0 0;">
                                            {{ number_format($masCostoso['cantidad'] ?? 1) }}
                                        </p>
                                        <p style="font-size: 6.5pt; color: #9ca3af; margin: 1pt 0 0 0;">unidades</p>
                                    </td>
                                </tr>
                            </table>
                        @else
                            <p style="font-size: 8pt; color: #9ca3af; font-style: italic;">Sin datos disponibles.</p>
                        @endif
                    </div>
                </td>

                {{-- KPI 2: Concepto de Mayor Recurrencia Operativa --}}
                <td
                    style="width: 49%; vertical-align: top; padding: 0 0 0 5pt; border: none; background: transparent;">
                    <div
                        style="
                    background: #f0f7ff;
                    border: 0.5pt solid #bfdbfe;
                    border-top: 3pt solid #0f3460;
                    padding: 9pt 10pt;
                    height: 100%;
                ">
                        <p
                            style="font-size: 6.5pt; color: #9ca3af; text-transform: uppercase;
                               letter-spacing: 0.8pt; margin: 0 0 2pt 0;">
                            Concepto de Mayor Recurrencia Operativa
                        </p>
                        <p style="font-size: 7pt; color: #6b7280; margin: 0 0 6pt 0;">
                            Material / trabajo que más se repite en el inventario
                        </p>

                        @if ($masRepetido)
                            {{-- Clave descriptiva --}}
                            <div
                                style="
                        background: #0f3460;
                        display: inline-block;
                        padding: 2pt 7pt;
                        margin-bottom: 5pt;
                    ">
                                <span
                                    style="font-size: 7pt; color: #93c5fd; font-weight: bold;
                                     letter-spacing: 0.5pt;">
                                    Material dominante
                                </span>
                            </div>

                            {{-- Descripción del trabajo --}}
                            <p
                                style="font-size: 8.5pt; font-weight: bold; color: #1a1a2e;
                               margin: 0 0 4pt 0; line-height: 1.3;">
                                {{ $masRepetido['material'] ?? '—' }}
                            </p>

                            <hr style="border: none; border-top: 0.4pt solid #bfdbfe; margin: 5pt 0;">

                            <table style="border-collapse: collapse; width: 100%;">
                                <tr>
                                    <td style="border: none; background: transparent; padding: 0;">
                                        <p
                                            style="font-size: 6.5pt; color: #9ca3af; margin: 0; text-transform: uppercase;">
                                            Frecuencia de aparición
                                        </p>
                                        <p
                                            style="font-size: 14pt; font-weight: bold; color: #0f3460;
                                           margin: 1pt 0 0 0; line-height: 1;">
                                            {{ number_format($masRepetido['total'] ?? 0) }}
                                        </p>
                                        <p style="font-size: 6.5pt; color: #9ca3af; margin: 1pt 0 0 0;">
                                            registros en todos los tramos
                                        </p>
                                    </td>
                                    <td
                                        style="border: none; background: transparent; padding: 0;
                                        text-align: right; vertical-align: bottom; width: 40%;">
                                        <div
                                            style="
                                    background: #dbeafe;
                                    border: 0.5pt solid #93c5fd;
                                    padding: 4pt 6pt;
                                    text-align: center;
                                ">
                                            <p
                                                style="font-size: 6pt; color: #1d4ed8; text-transform: uppercase;
                                               font-weight: bold; margin: 0;">
                                                Alta recurrencia
                                            </p>
                                            <p style="font-size: 6pt; color: #3b82f6; margin: 1pt 0 0 0;">
                                                Prioridad de contratación
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        @else
                            <p style="font-size: 8pt; color: #9ca3af; font-style: italic;">Sin datos disponibles.</p>
                        @endif
                    </div>
                </td>

            </tr>
        </table>

        {{-- ── Top 3 Conceptos Críticos ──────────────────────────────── --}}
        <div
            style="
        background: #f4f6f8;
        border: 0.5pt solid #d1d5db;
        border-left: 3pt solid #e94560;
        padding: 9pt 10pt;
    ">
            <table style="border-collapse: collapse; width: 100%; margin-bottom: 7pt;">
                <tr>
                    <td style="border: none; background: transparent; padding: 0; vertical-align: middle;">
                        <p
                            style="font-size: 9pt; font-weight: bold; color: #16213e;
                               text-transform: uppercase; letter-spacing: 0.5pt; margin: 0;">
                            Top 3 · Conceptos Críticos por Inversión
                        </p>
                        <p style="font-size: 7pt; color: #6b7280; margin: 1pt 0 0 0;">
                            Ordenados de mayor a menor costo consolidado · Base: todos los tramos analizados
                        </p>
                    </td>
                    <td
                        style="border: none; background: transparent; padding: 0;
                            text-align: right; vertical-align: middle; width: 30mm;">
                        <span
                            style="
                        background: #1a1a2e;
                        color: #e94560;
                        font-size: 7pt;
                        font-weight: bold;
                        padding: 2pt 6pt;
                        text-transform: uppercase;
                        letter-spacing: 0.5pt;
                    ">Estratégico</span>
                    </td>
                </tr>
            </table>

            <table style="border-collapse: collapse; width: 100%;">
                <thead>
                    <tr>
                        <th
                            style="background:#1a1a2e; color:#94a3b8; font-size:6.5pt; padding:4pt 7pt;
                                text-align:center; font-weight:bold; text-transform:uppercase;
                                letter-spacing:0.4pt; border:none; width:6%;">
                            #</th>
                        <th
                            style="background:#1a1a2e; color:#94a3b8; font-size:6.5pt; padding:4pt 7pt;
                                text-align:left; font-weight:bold; text-transform:uppercase;
                                letter-spacing:0.4pt; border:none; width:20%;">
                            Clave / Tipo</th>
                        <th
                            style="background:#1a1a2e; color:#94a3b8; font-size:6.5pt; padding:4pt 7pt;
                                text-align:left; font-weight:bold; text-transform:uppercase;
                                letter-spacing:0.4pt; border:none; width:40%;">
                            Descripción del Trabajo</th>
                        <th
                            style="background:#1a1a2e; color:#94a3b8; font-size:6.5pt; padding:4pt 7pt;
                                text-align:center; font-weight:bold; text-transform:uppercase;
                                letter-spacing:0.4pt; border:none; width:14%;">
                            Volumen Total</th>
                        <th
                            style="background:#1a1a2e; color:#94a3b8; font-size:6.5pt; padding:4pt 7pt;
                                text-align:right; font-weight:bold; text-transform:uppercase;
                                letter-spacing:0.4pt; border:none; width:20%;">
                            Importe Consolidado</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        /* Podiums: colores de posición */
                        $podiumColors = ['#f59e0b', '#9ca3af', '#cd7c3b'];
                        $podiumLabels = ['1°', '2°', '3°'];
                    @endphp
                    @forelse ($topCriticos as $idx => $concepto)
                        @php
                            $podColor = $podiumColors[$idx] ?? '#6b7280';
                            $rowBg = $idx === 0 ? '#fff8ee' : '#ffffff';
                        @endphp
                        <tr style="background: {{ $rowBg }};">
                            {{-- Posición --}}
                            <td
                                style="font-size: 9pt; font-weight: bold; color: {{ $podColor }};
                                text-align: center; padding: 6pt 7pt;
                                border-bottom: 0.4pt solid #e5e7eb; vertical-align: middle;">
                                {{ $podiumLabels[$idx] ?? $idx + 1 . '°' }}
                            </td>

                            {{-- Clave / tipo de activo --}}
                            <td style="padding: 6pt 7pt; border-bottom: 0.4pt solid #e5e7eb; vertical-align: middle;">
                                <span
                                    style="
                            display: inline-block;
                            background: #1a1a2e;
                            color: {{ $podColor }};
                            font-size: 6.5pt;
                            font-weight: bold;
                            padding: 1.5pt 5pt;
                            text-transform: uppercase;
                            letter-spacing: 0.3pt;
                        ">{{ $concepto['clave'] ?? '—' }}</span>
                            </td>

                            {{-- Descripción larga (material como proxy operativo) --}}
                            <td
                                style="font-size: 8pt; color: #374151; padding: 6pt 7pt;
                                border-bottom: 0.4pt solid #e5e7eb; vertical-align: middle;
                                line-height: 1.35;">
                                {{ $concepto['material'] ?? '—' }}
                                {{--
                            NOTA: Cuando el Service exponga un campo 'descripcion'
                            explícito en $coleccionConceptos, reemplazar
                            $concepto['material'] por $concepto['descripcion'].
                        --}}
                            </td>

                            {{-- Volumen total (cantidad de unidades) --}}
                            <td
                                style="font-size: 8pt; color: #374151; font-weight: bold;
                                text-align: center; padding: 6pt 7pt;
                                border-bottom: 0.4pt solid #e5e7eb; vertical-align: middle;">
                                {{ number_format($concepto['cantidad'] ?? 1) }}
                                <br>
                                <span style="font-size: 6pt; color: #9ca3af; font-weight: normal;">uds.</span>
                            </td>

                            {{-- Importe consolidado --}}
                            <td
                                style="font-size: 8.5pt; font-weight: bold; color: #e94560;
                                text-align: right; padding: 6pt 7pt;
                                border-bottom: 0.4pt solid #e5e7eb; vertical-align: middle;">
                                ${{ number_format($concepto['importe'] ?? 0, 0, '.', ',') }}
                                <br>
                                <span style="font-size: 6pt; color: #9ca3af; font-weight: normal;">MXN</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                style="text-align: center; font-size: 8pt; color: #9ca3af;
                                            padding: 10pt; border: none; font-style: italic;">
                                No se encontraron conceptos críticos para los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Suma parcial de los top 3 --}}
            @if (count($topCriticos) > 0)
                @php
                    $sumaTop3 = array_sum(array_column($topCriticos, 'importe'));
                    $pctTop3 = $costoTotal > 0 ? round(($sumaTop3 / $costoTotal) * 100, 1) : 0;
                @endphp
                <div style="margin-top: 7pt; text-align: right;">
                    <span style="font-size: 7pt; color: #6b7280;">
                        Subtotal Top 3:
                        <strong style="color: #1a1a2e;">
                            ${{ number_format($sumaTop3, 0, '.', ',') }} MXN
                        </strong>
                        &nbsp;·&nbsp;
                        <strong style="color: #e94560;">{{ $pctTop3 }}%</strong>
                        del presupuesto total
                    </span>
                </div>
            @endif
        </div>

        {{-- ── Footer de página interior ─────────────────────────────── --}}
        <div
            style="
        position: fixed;
        bottom: 0; left: 0; right: 0;
        height: 12mm;
        background: #1a1a2e;
        padding: 0 16mm;
    ">
            <table style="height: 12mm; width: 100%; border-collapse: collapse;">
                <tr>
                    <td
                        style="font-size: 6.5pt; color: #64748b; vertical-align: middle;
                            border: none; background: transparent; padding: 0;">
                        SCALAMAPS · Reporte de Análisis Integral de Activos Viales
                    </td>
                    <td
                        style="font-size: 6.5pt; color: #64748b; text-align: right;
                            vertical-align: middle; border: none; background: transparent; padding: 0;">
                        Página 3 · Análisis de Alta Dirección
                    </td>
                </tr>
            </table>
        </div>

    </div>{{-- /página 3 --}}

    {{-- ══════════════════════════════════════════════════════════════
     FIN PÁGINAS 2-3
     Siguiente bloque: Páginas Iterativas (Desglose por tramo).
     Añadir <div class="page-break"></div> al final de esta sección
     antes de pegar el siguiente bloque.
     ══════════════════════════════════════════════════════════════ --}}

</body>

</html>
