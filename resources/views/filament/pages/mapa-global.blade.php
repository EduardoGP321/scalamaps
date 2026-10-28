<x-filament-panels::page>
    <div class="space-y-4">

        {{-- ═══════════════════════════════════════════════════════════════════
             PANEL DE FILTROS JERÁRQUICOS (Header)
        ═══════════════════════════════════════════════════════════════════ --}}
        <div
            class="grid grid-cols-1 gap-4 p-4 bg-white dark:bg-[#040e25] border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm">

            {{-- Fila 1: Selectores en cascada --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">

                {{-- Selector 1: Contrato (Livewire) --}}
                <x-filament::input.wrapper label="Contrato">
                    <x-filament::input.select wire:model.live="contratoId" id="contrato-selector">
                        <option value="">— Seleccione un Contrato —</option>
                        @foreach ($contratos as $c)
                            <option value="{{ $c->id }}">
                                {{ $c->nombre_corto }} ({{ $c->numero_contrato }})
                            </option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>

                {{-- Selector 2: Carretera (JS dinámico) --}}
                <x-filament::input.wrapper prefix-icon="heroicon-m-map" prefix-icon-color="warning">
                    <select id="select-carretera"
                        class="fi-select-input block w-full border-none bg-transparent py-1.5 pe-8 ps-3 text-sm leading-6 text-gray-950 outline-none focus:ring-0 dark:text-white">
                        <option value="">— Todas las Carreteras —</option>
                    </select>
                </x-filament::input.wrapper>

                {{-- Selector 3: Tramo (JS dinámico) --}}
                <x-filament::input.wrapper prefix-icon="heroicon-m-chevron-double-right" prefix-icon-color="warning">
                    <select id="select-tramo"
                        class="fi-select-input block w-full border-none bg-transparent py-1.5 pe-8 ps-3 text-sm leading-6 text-gray-950 outline-none focus:ring-0 dark:text-white">
                        <option value="">— Todos los Tramos —</option>
                    </select>
                </x-filament::input.wrapper>

                {{-- Selector 4: Cadenamiento (JS dinámico, activo solo con tramo) --}}
                <x-filament::input.wrapper prefix-icon="heroicon-m-magnifying-glass" prefix-icon-color="warning">
                    <select id="select-ubicacion"
                        class="fi-select-input block w-full border-none bg-transparent py-1.5 pe-8 ps-3 text-sm leading-6 text-gray-950 outline-none focus:ring-0 dark:text-white">
                        <option value="">— Cadenamiento —</option>
                    </select>
                </x-filament::input.wrapper>
            </div>

            {{-- Fila 2: Panel de Filtros de Activos
                 OCULTO por defecto. Solo aparece cuando se selecciona un Tramo.
                 Controlado por pointer-events y opacity para animación suave. --}}
            <div id="filter-panel"
                class="transition-all duration-500 ease-in-out space-y-3 border-t border-gray-100 dark:border-gray-800 pt-3"
                style="opacity: 0; pointer-events: none; max-height: 0; overflow: hidden;">
                <div class="flex flex-wrap gap-6 items-start">
                    <div class="space-y-1">
                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Tipo:</span>
                        <div id="filter-nombres" class="flex flex-wrap gap-2"></div>
                    </div>
                    <div class="space-y-1 border-l border-gray-100 dark:border-gray-800 pl-4">
                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Material:</span>
                        <div id="filter-materiales" class="flex flex-wrap gap-2"></div>
                    </div>
                    <div class="space-y-1 border-l border-gray-100 dark:border-gray-800 pl-4">
                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Condición:</span>
                        <div id="filter-condiciones" class="flex flex-wrap gap-2"></div>
                    </div>
                    <div class="ml-auto flex items-center" wire:loading>
                        <x-filament::loading-indicator class="h-5 w-5 text-primary-600" />
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════
             MAPA GIS
        ═══════════════════════════════════════════════════════════════════ --}}
        <div wire:ignore
            class="relative w-full rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 shadow-2xl"
            style="height: 750px;">

            <div id="map" style="width: 100%; height: 100%; background-color: #f3f4f6;"></div>

            {{-- Overlay Flotante de Información --}}
            <div id="map-overlay"
                class="absolute top-6 right-6 bg-white/90 dark:bg-gray-950/95 p-6 rounded-2xl shadow-2xl backdrop-blur-md border border-white/20 max-w-xs min-w-[260px] pointer-events-none transition-all"
                style="display: none;">
                <div class="mb-4">
                    <h2 id="overlay-nombre" class="text-base font-bold text-gray-900 dark:text-white leading-tight">
                    </h2>
                    <p id="overlay-numero" class="text-[10px] font-mono text-primary-600 mt-1 uppercase tracking-wider">
                    </p>
                    <p id="overlay-contrato-detalles" class="text-[9px] text-gray-400 mt-0.5"></p>
                </div>

                <div class="space-y-4">
                    {{-- Panel de Detalles del Activo Clickeado --}}
                    <div id="asset-details" class="hidden space-y-3 animate-fade-in pointer-events-auto">
                        <hr class="border-gray-100 dark:border-gray-800">
                        <div
                            class="p-3 bg-primary-500/5 dark:bg-primary-500/10 rounded-2xl border border-primary-500/10">
                            <span class="block text-[8px] uppercase font-bold text-primary-600 mb-1">Activo
                                Seleccionado</span>
                            <span id="detail-nombre"
                                class="text-sm font-black text-gray-900 dark:text-white leading-none"></span>
                        </div>
                        <div id="metadatos-container" class="grid grid-cols-1 gap-2 max-h-60 overflow-y-auto pr-1">
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-1">
                        <span id="overlay-activos" class="text-[10px] font-bold text-gray-400"></span>
                    </div>

                    <div id="overlay-zoom-hint"
                        class="items-center gap-2 text-[10px] text-amber-600 bg-amber-50 dark:bg-amber-900/20 p-2.5 rounded-xl border border-amber-100 dark:border-amber-800/50"
                        style="display: none;">
                        <x-filament::icon icon="heroicon-m-magnifying-glass-plus" class="h-4 w-4" />
                        <span>Acércate para ver los extremos de las alcantarillas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════
         MOTOR JS — SCALAMAPS GIS ENGINE
         Sintaxis @script de Livewire v3 para evitar re-ejecución en navegación
    ═══════════════════════════════════════════════════════════════════════ --}}
    @script
        <script>
            // ─────────────────────────────────────────────────────────────────────────
            // ESTADO GLOBAL DEL MOTOR GIS
            // ─────────────────────────────────────────────────────────────────────────
            let gisMap = null;
            let gisData = null; // El payload completo del contrato activo
            let lastZoomLevel = 8;

            /**
             * Selecciones actuales del usuario.
             * carreteraId y tramoId son strings para coincidir con .value del <select>.
             */
            let currentSelections = {
                nombres: new Set(),
                materiales: new Set(),
                condiciones: new Set(),
                carreteraId: '',
                tramoId: '',
                ubicacion: '', // cadenamiento seleccionado
            };

            /**
             * Capas del mapa organizadas para limpieza independiente.
             *
             * carreterasPolys: { carretera_id: Polyline }  — trazo base de cada carretera
             * tramosPolys:     { tramo_id: Polyline }       — trazo detallado de cada tramo
             * assetsLayouts:   []                           — pines y líneas de activos activos
             */
            let gisLayers = {
                carreterasPolys: {},
                tramosPolys: {},
                assetsLayouts: [],
            };

            // ─────────────────────────────────────────────────────────────────────────
            // INICIALIZACIÓN DEL MAPA
            // ─────────────────────────────────────────────────────────────────────────

            async function initGisMap() {
                const {
                    Map
                } = await google.maps.importLibrary('maps');

                gisMap = new Map(document.getElementById('map'), {
                    center: {
                        lat: 18.86,
                        lng: -98.20
                    },
                    zoom: 8,
                    mapId: 'e7f7ed4aef1512f61b25b129',
                    disableDefaultUI: false,
                    streetViewControl: false,
                });

                gisMap.addListener('idle', () => {
                    const newZoom = gisMap.getZoom();
                    const hint = document.getElementById('overlay-zoom-hint');

                    // Mostrar hint de zoom solo cuando hay activos en pantalla
                    if (hint && gisLayers.assetsLayouts.length > 0) {
                        hint.style.display = newZoom < 14 ? 'flex' : 'none';
                    }

                    // Redibujar activos al cruzar el umbral de zoom 14
                    // (para mostrar/ocultar los puntos extremos de obras menores)
                    if ((lastZoomLevel < 14 && newZoom >= 14) || (lastZoomLevel >= 14 && newZoom < 14)) {
                        if (currentSelections.tramoId) {
                            refreshAssetsOnMap();
                        }
                    }
                    lastZoomLevel = newZoom;
                });

                // ── EVENTOS EN CASCADA ───────────────────────────────────────────────

                // Evento: Carretera seleccionada
                document.getElementById('select-carretera').addEventListener('change', (e) => {
                    currentSelections.carreteraId = e.target.value;
                    currentSelections.tramoId = ''; // Resetear hijo
                    currentSelections.ubicacion = ''; // Resetear cadenamiento

                    // Repoblar el dropdown de tramos filtrado por carretera
                    populateTramosDropdown();
                    resetCadenamientosDropdown();

                    // REGLA 2: Al seleccionar carretera, ocultar panel de filtros
                    hideFilterPanel();

                    // Limpiar activos del mapa (no corresponde mostrarlos todavía)
                    clearAssetsLayer();

                    // Mostrar solo la polilínea de la carretera seleccionada
                    renderVialLayers();

                    // Encuadrar al trazo de la carretera
                    focusGeometry('carretera');
                });

                // Evento: Tramo seleccionado
                document.getElementById('select-tramo').addEventListener('change', (e) => {
                    currentSelections.tramoId = e.target.value;
                    currentSelections.ubicacion = ''; // Resetear cadenamiento al cambiar tramo

                    if (currentSelections.tramoId) {
                        // REGLA 3: Al seleccionar un tramo, mostrar panel de filtros mágicamente
                        showFilterPanel();
                        populateCadenamientosDropdown();
                    } else {
                        // Si se deseleccionó el tramo, volver al estado de carretera
                        hideFilterPanel();
                        clearAssetsLayer();
                        resetCadenamientosDropdown();
                    }

                    // Apagar carretera, encender tramo (o viceversa si se deseleccionó)
                    renderVialLayers();

                    // Renderizar activos del tramo seleccionado
                    if (currentSelections.tramoId) {
                        refreshAssetsOnMap();
                        focusGeometry('tramo');
                    } else {
                        focusGeometry('carretera');
                    }
                });

                // Evento: Cadenamiento seleccionado
                document.getElementById('select-ubicacion').addEventListener('change', (e) => {
                    currentSelections.ubicacion = e.target.value;
                    refreshAssetsOnMap();
                });

                // Cargar datos iniciales si ya hay un contrato seleccionado (carga de URL)
                @if ($gisData)
                    loadData(@js($gisData));
                @endif
            }

            // ─────────────────────────────────────────────────────────────────────────
            // LISTENER DE LIVEWIRE (wire:dispatch desde PHP)
            // ─────────────────────────────────────────────────────────────────────────

            window.addEventListener('update-map', event => {
                const data = event.detail.payload;
                loadData(data);
            });

            // ─────────────────────────────────────────────────────────────────────────
            // CARGA PRINCIPAL DE DATOS
            // ─────────────────────────────────────────────────────────────────────────

            /**
             * loadData: Punto de entrada cuando llega un nuevo payload de contrato.
             *
             * REGLA 1: Estado inicial = solo polilíneas base de carreteras.
             *          Panel de filtros oculto. No se muestran activos.
             */
            async function loadData(payload) {
                if (!payload) {
                    resetUI();
                    return;
                }

                gisData = payload;
                if (!gisMap) return;

                // Limpiar capas previas
                clearAllLayers();

                // Resetear selecciones
                currentSelections.carreteraId = '';
                currentSelections.tramoId = '';
                currentSelections.ubicacion = '';

                // Poblar dropdowns
                populateCarreterasDropdown();
                populateTramosDropdown();
                resetCadenamientosDropdown();

                // Actualizar overlay de información del contrato
                updateOverlayBaseInfo();

                // Pre-construir geometrías en memoria (sin agregarlas al mapa)
                await buildGeometryLayers();

                // REGLA 1: Mostrar SOLO las polilíneas de carreteras
                renderVialLayers();

                // REGLA 1: Panel de filtros oculto
                hideFilterPanel();

                // REGLA 1: Sin activos en el mapa
                clearAssetsLayer();

                // Inicializar los filtros con los valores del payload
                // (los botones se crean pero el panel está oculto hasta que se elija tramo)
                buildFilterButtons(payload.info.filtros);

                // Encuadrar al contrato completo
                focusGeometry('contrato');
            }

            // ─────────────────────────────────────────────────────────────────────────
            // CONSTRUCCIÓN DE GEOMETRÍAS (pre-compilado, sin mostrar en mapa)
            // ─────────────────────────────────────────────────────────────────────────

            async function buildGeometryLayers() {
                if (!gisData?.carreteras?.length) return;

                const {
                    Polyline
                } = await google.maps.importLibrary('maps');

                gisLayers.carreterasPolys = {};
                gisLayers.tramosPolys = {};

                for (const carr of gisData.carreteras) {
                    // CAPA A: Polilínea base de la carretera (estilo sobrio)
                    if (carr.polyline) {
                        const path = google.maps.geometry.encoding.decodePath(carr.polyline);
                        gisLayers.carreterasPolys[carr.carretera_id] = new Polyline({
                            path: path,
                            strokeColor: '#0f172a', // Negro institucional
                            strokeWeight: 6,
                            strokeOpacity: 0.75,
                            // NO se asigna map todavía — renderVialLayers() lo hará
                        });
                    }

                    // CAPA B: Polilíneas detalladas de los tramos hijos
                    for (const tramo of carr.tramos) {
                        if (!tramo.polyline) continue;

                        const path = google.maps.geometry.encoding.decodePath(tramo.polyline);
                        gisLayers.tramosPolys[tramo.tramo_id] = new Polyline({
                            path: path,
                            strokeColor: '#2563eb', // Azul vivo para el tramo activo
                            strokeWeight: 9,
                            strokeOpacity: 0.95,
                            // NO se asigna map todavía
                        });
                    }
                }
            }

            // ─────────────────────────────────────────────────────────────────────────
            // INTERRUPTOR DE CAPAS VIALES (el corazón de la cascada)
            // ─────────────────────────────────────────────────────────────────────────

            /**
             * renderVialLayers: Decide qué polilíneas mostrar según el estado actual.
             *
             * CASO 1 — Hay tramo seleccionado:
             *   → Apagar TODAS las carreteras y TODOS los tramos excepto el seleccionado.
             *   → Encender solo la polilínea del tramo activo.
             *
             * CASO 2 — Hay carretera seleccionada (sin tramo):
             *   → Apagar todas las carreteras excepto la seleccionada.
             *   → Apagar todos los tramos.
             *   → Encender solo la polilínea de la carretera seleccionada.
             *
             * CASO 3 — Solo contrato (sin carretera ni tramo):
             *   → Encender TODAS las polilíneas base de carreteras.
             *   → Apagar todos los tramos.
             */
            function renderVialLayers() {
                // Paso 1: Apagar absolutamente todo
                Object.values(gisLayers.carreterasPolys).forEach(p => p.setMap(null));
                Object.values(gisLayers.tramosPolys).forEach(p => p.setMap(null));

                if (currentSelections.tramoId) {
                    // CASO 1: Tramo seleccionado
                    const tramoPoly = gisLayers.tramosPolys[currentSelections.tramoId];
                    if (tramoPoly) tramoPoly.setMap(gisMap);

                } else if (currentSelections.carreteraId) {
                    // CASO 2: Carretera seleccionada, sin tramo
                    const carrPoly = gisLayers.carreterasPolys[currentSelections.carreteraId];
                    if (carrPoly) carrPoly.setMap(gisMap);

                } else {
                    // CASO 3: Vista global del contrato
                    Object.values(gisLayers.carreterasPolys).forEach(p => p.setMap(gisMap));
                }
            }

            // ─────────────────────────────────────────────────────────────────────────
            // RENDERIZADO DE ACTIVOS (Solo para el tramo activo)
            // ─────────────────────────────────────────────────────────────────────────

            /**
             * refreshAssetsOnMap: Dibuja los activos del tramo seleccionado,
             * aplicando los filtros activos de tipo/material/condición.
             *
             * IMPORTANTE: Solo se llama cuando hay un tramoId seleccionado.
             * Si no hay tramo, el mapa no muestra activos.
             */
            async function refreshAssetsOnMap() {
                clearAssetsLayer();

                if (!currentSelections.tramoId || !gisData?.carreteras) {
                    updateActivosCounter(0);
                    return;
                }

                const {
                    AdvancedMarkerElement,
                    PinElement
                } = await google.maps.importLibrary('marker');
                const {
                    Polyline
                } = await google.maps.importLibrary('maps');

                const currentZoom = gisMap.getZoom();
                const showPolyMarkers = currentZoom >= 14; // Extremos de alcantarillas en zoom alto

                // Buscar los activos del tramo seleccionado en el payload jerárquico
                const tramoId = String(currentSelections.tramoId);
                let activosDelTramo = [];

                for (const carr of gisData.carreteras) {
                    for (const tramo of carr.tramos) {
                        if (String(tramo.tramo_id) === tramoId) {
                            activosDelTramo = tramo.activos || [];
                            break;
                        }
                    }
                    if (activosDelTramo.length) break;
                }

                // Aplicar filtros de atributos y dibujar
                // LÓGICA CORRECTA: si el item NO está en el Set, se oculta.
                // Set vacío = todos desactivados = no mostrar nada.
                for (const asset of activosDelTramo) {
                    const f = asset.filter_data;
                    const data = asset.payload;

                    // Filtro: Tipo de activo
                    if (!currentSelections.nombres.has(f.nombre)) continue;
                    // Filtro: Material (N/A se trata como valor válido)
                    if (!currentSelections.materiales.has(f.material)) continue;
                    // Filtro: Condición
                    if (!currentSelections.condiciones.has(f.condicion)) continue;
                    // Filtro: Cadenamiento (dropdown — vacío = todos)
                    if (currentSelections.ubicacion && f.ubicacion !== currentSelections.ubicacion) continue;

                    // ── Polilínea propia de la Obra Menor (alcantarilla) ──
                    // Si tiene path → es obra menor → solo polilínea, sin pin encima.
                    if (data.path) {
                        const poly = new Polyline({
                            path: data.path,
                            strokeColor: data.color,
                            strokeWeight: 6,
                            strokeOpacity: 0.9,
                            map: gisMap,
                            clickable: true,
                        });
                        poly.addListener('click', () => showAssetDetails(data));
                        gisLayers.assetsLayouts.push(poly);

                        // En zoom alto: pequeños dots en los extremos (entrada y salida)
                        if (showPolyMarkers) {
                            for (const pos of data.path) {
                                const dot = new AdvancedMarkerElement({
                                    position: pos,
                                    map: gisMap,
                                    content: new PinElement({
                                        scale: 0.45,
                                        background: data.color,
                                        borderColor: '#ffffff',
                                    }).element,
                                });
                                dot.addListener('click', () => showAssetDetails(data));
                                gisLayers.assetsLayouts.push(dot);
                            }
                        }
                    }

                    // ── Pin puntual — SOLO para activos sin polilínea (señalamientos, etc.) ──
                    // Las obras menores ya quedaron representadas por su polilínea arriba.
                    if (data.position && !data.path) {
                        const marker = new AdvancedMarkerElement({
                            position: data.position,
                            map: gisMap,
                            title: data.nombre,
                            content: new PinElement({
                                background: data.color || '#10b981',
                                borderColor: '#ffffff',
                                scale: 0.75,
                            }).element,
                        });
                        marker.addListener('click', () => showAssetDetails(data));
                        gisLayers.assetsLayouts.push(marker);
                    }
                }

                updateActivosCounter(gisLayers.assetsLayouts.length);
            }

            // ─────────────────────────────────────────────────────────────────────────
            // PANEL DE DETALLES DEL ACTIVO
            // ─────────────────────────────────────────────────────────────────────────

            function showAssetDetails(data) {
                const detailPanel = document.getElementById('asset-details');
                const container = document.getElementById('metadatos-container');
                if (!detailPanel || !container) return;

                detailPanel.classList.remove('hidden');
                document.getElementById('detail-nombre').textContent = data.nombre;
                container.innerHTML = '';

                for (const [key, value] of Object.entries(data.metadatos)) {
                    if (!value) continue;

                    if (key === 'Imagenes' && Array.isArray(value)) {
                        // ── Galería de imágenes polimórficas ──
                        const validImgs = value.filter(img => img.url);
                        if (!validImgs.length) continue;

                        let html = '<div class="grid grid-cols-2 gap-2 mt-2 col-span-full">';
                        for (const img of validImgs) {
                            html += `
                        <div class="space-y-1">
                            <img src="${img.url}"
                                 class="w-full h-20 object-cover rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer shadow-sm hover:opacity-90 transition"
                                 onclick="window.open('${img.url}', '_blank')"
                                 loading="lazy">
                            <span class="block text-[8px] text-gray-500 font-medium truncate text-center">${img.titulo}</span>
                        </div>`;
                        }
                        container.innerHTML += html + '</div>';

                    } else if (key === 'Observaciones') {
                        // ── Observaciones en bloque completo ──
                        container.innerHTML += `
                    <div class="p-2 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-100 dark:border-amber-800/50 col-span-full">
                        <span class="text-[8px] uppercase font-bold text-amber-600 tracking-wider">Observaciones</span>
                        <p class="text-[10px] text-gray-700 dark:text-gray-300 mt-1">${value}</p>
                    </div>`;

                    } else {
                        // ── Metadato clave → valor ──
                        container.innerHTML += `
                    <div class="flex flex-col p-2 bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
                        <span class="text-[8px] uppercase font-bold text-gray-400 tracking-wider">${key}</span>
                        <span class="text-[10px] font-bold text-gray-700 dark:text-gray-200">${value}</span>
                    </div>`;
                    }
                }

                if (data.url_view) {
                    container.innerHTML += `
                <a href="${data.url_view}"
                   class="mt-2 block w-full py-2 text-center text-[11px] font-bold text-white bg-primary-600 hover:bg-primary-500 rounded-xl pointer-events-auto transition shadow-md">
                    Ver Información Completa
                </a>`;
                }
            }

            // ─────────────────────────────────────────────────────────────────────────
            // ENCUADRE DINÁMICO (fitBounds)
            // ─────────────────────────────────────────────────────────────────────────

            function focusGeometry(type) {
                if (!gisMap) return;

                const bounds = new google.maps.LatLngBounds();
                let hasPoints = false;

                if (type === 'tramo' && currentSelections.tramoId) {
                    const poly = gisLayers.tramosPolys[currentSelections.tramoId];
                    if (poly) {
                        poly.getPath().forEach(p => bounds.extend(p));
                        hasPoints = true;
                    }
                } else if (type === 'carretera' && currentSelections.carreteraId) {
                    const poly = gisLayers.carreterasPolys[currentSelections.carreteraId];
                    if (poly) {
                        poly.getPath().forEach(p => bounds.extend(p));
                        hasPoints = true;
                    }
                } else if (type === 'contrato') {
                    Object.values(gisLayers.carreterasPolys).forEach(poly => {
                        poly.getPath().forEach(p => bounds.extend(p));
                        hasPoints = true;
                    });
                }

                if (hasPoints) {
                    gisMap.fitBounds(bounds, {
                        padding: type === 'tramo' ? 120 : 60
                    });
                }
            }

            // ─────────────────────────────────────────────────────────────────────────
            // DROPDOWNS DINÁMICOS
            // ─────────────────────────────────────────────────────────────────────────

            function populateCarreterasDropdown() {
                const select = document.getElementById('select-carretera');
                select.innerHTML = '<option value="">— Todas las Carreteras —</option>';

                if (!gisData?.carreteras) return;

                for (const c of gisData.carreteras) {
                    const opt = document.createElement('option');
                    opt.value = c.carretera_id;
                    opt.textContent = c.nombre_carretera;
                    select.appendChild(opt);
                }
            }

            function populateTramosDropdown() {
                const select = document.getElementById('select-tramo');
                select.innerHTML = '<option value="">— Todos los Tramos —</option>';
                currentSelections.tramoId = '';

                if (!gisData?.carreteras) return;

                for (const c of gisData.carreteras) {
                    // Si hay una carretera seleccionada, solo mostrar sus tramos
                    if (currentSelections.carreteraId && String(c.carretera_id) !== String(currentSelections.carreteraId)) {
                        continue;
                    }
                    for (const t of c.tramos) {
                        const opt = document.createElement('option');
                        opt.value = t.tramo_id;
                        opt.textContent = t.nombre_tramo;
                        select.appendChild(opt);
                    }
                }
            }

            /**
             * Pobla el dropdown de cadenamientos con los ubicaciones únicas
             * de los activos del tramo actualmente seleccionado.
             * Se llama SOLO cuando se elige un tramo.
             */
            function populateCadenamientosDropdown() {
                const select = document.getElementById('select-ubicacion');
                select.innerHTML = '<option value="">— Todos los Cadenamientos —</option>';
                currentSelections.ubicacion = '';

                if (!currentSelections.tramoId || !gisData?.carreteras) return;

                const tramoId = String(currentSelections.tramoId);
                let activosDelTramo = [];

                for (const carr of gisData.carreteras) {
                    for (const tramo of carr.tramos) {
                        if (String(tramo.tramo_id) === tramoId) {
                            activosDelTramo = tramo.activos || [];
                            break;
                        }
                    }
                    if (activosDelTramo.length) break;
                }

                // Extraer cadenamientos únicos y ordenarlos
                const ubicaciones = [...new Set(
                    activosDelTramo.map(a => a.filter_data.ubicacion).filter(Boolean)
                )].sort();

                for (const ubi of ubicaciones) {
                    const opt = document.createElement('option');
                    opt.value = ubi;
                    opt.textContent = ubi;
                    select.appendChild(opt);
                }
            }

            function resetCadenamientosDropdown() {
                const select = document.getElementById('select-ubicacion');
                select.innerHTML = '<option value="">— Cadenamiento —</option>';
                currentSelections.ubicacion = '';
            }

            // ─────────────────────────────────────────────────────────────────────────
            // PANEL DE FILTROS DE ACTIVOS
            // ─────────────────────────────────────────────────────────────────────────

            /**
             * buildFilterButtons: Crea los botones toggle de filtros.
             * Solo se llama UNA VEZ al cargar el contrato.
             * El panel en sí permanece oculto hasta que se elija un tramo.
             */
            function buildFilterButtons(filtros) {
                const categories = [{
                        id: 'filter-nombres',
                        items: filtros.nombres,
                        key: 'nombres'
                    },
                    {
                        id: 'filter-materiales',
                        items: filtros.materiales,
                        key: 'materiales'
                    },
                    {
                        id: 'filter-condiciones',
                        items: filtros.condiciones,
                        key: 'condiciones'
                    },
                ];

                for (const cat of categories) {
                    const container = document.getElementById(cat.id);
                    if (!container) continue;
                    container.innerHTML = '';

                    // Inicializar Sets con todos los valores (todo activo por defecto)
                    currentSelections[cat.key] = new Set(cat.items);

                    for (const item of cat.items) {
                        const btn = document.createElement('button');
                        btn.type = 'button';

                        const updateStyle = () => {
                            const active = currentSelections[cat.key].has(item);
                            btn.className = `px-2 py-1 rounded-md text-[10px] font-bold border transition-all ${
                        active
                            ? 'bg-primary-600 border-primary-600 text-white'
                            : 'bg-white dark:bg-gray-800 text-gray-400 border-gray-200 dark:border-gray-700'
                    }`;
                        };

                        btn.textContent = item;
                        updateStyle();

                        btn.onclick = () => {
                            if (currentSelections[cat.key].has(item)) {
                                currentSelections[cat.key].delete(item);
                            } else {
                                currentSelections[cat.key].add(item);
                            }
                            updateStyle();
                            refreshAssetsOnMap();
                        };

                        container.appendChild(btn);
                    }
                }
            }

            /**
             * REGLA 3 CUMPLIDA: El panel aparece mágicamente al seleccionar un tramo.
             */
            function showFilterPanel() {
                const panel = document.getElementById('filter-panel');
                if (!panel) return;
                panel.style.opacity = '1';
                panel.style.pointerEvents = 'auto';
                panel.style.maxHeight = '200px';
                panel.style.overflow = 'visible';
            }

            /**
             * REGLAS 1 y 2 CUMPLIDAS: El panel se oculta al nivel contrato o carretera.
             */
            function hideFilterPanel() {
                const panel = document.getElementById('filter-panel');
                if (!panel) return;
                panel.style.opacity = '0';
                panel.style.pointerEvents = 'none';
                panel.style.maxHeight = '0';
                panel.style.overflow = 'hidden';
            }

            // ─────────────────────────────────────────────────────────────────────────
            // UTILIDADES DE LIMPIEZA
            // ─────────────────────────────────────────────────────────────────────────

            function clearAllLayers() {
                Object.values(gisLayers.carreterasPolys).forEach(p => p.setMap(null));
                Object.values(gisLayers.tramosPolys).forEach(p => p.setMap(null));
                gisLayers.carreterasPolys = {};
                gisLayers.tramosPolys = {};
                clearAssetsLayer();
            }

            function clearAssetsLayer() {
                for (const asset of gisLayers.assetsLayouts) {
                    if (typeof asset.setMap === 'function') {
                        asset.setMap(null);
                    } else {
                        asset.map = null;
                    }
                }
                gisLayers.assetsLayouts = [];

                // Cerrar el panel de detalles si quedó abierto de un activo anterior
                const detailPanel = document.getElementById('asset-details');
                if (detailPanel) detailPanel.classList.add('hidden');
            }

            function updateActivosCounter(count) {
                const el = document.getElementById('overlay-activos');
                if (el) el.textContent = count > 0 ? `Activos visibles: ${count}` : '';
            }

            function updateOverlayBaseInfo() {
                const overlay = document.getElementById('map-overlay');
                if (overlay) overlay.style.display = 'block';

                document.getElementById('overlay-nombre').textContent = gisData?.info?.nombre ?? '';
                document.getElementById('overlay-numero').textContent = gisData?.info?.numero ?? '';
                document.getElementById('overlay-contrato-detalles').textContent =
                    `Año: ${gisData?.info?.anio ?? 'S/N'} | ${gisData?.info?.empresa ?? 'N/A'}`;

                const detailPanel = document.getElementById('asset-details');
                if (detailPanel) detailPanel.classList.add('hidden');
            }

            function resetUI() {
                clearAllLayers();
                hideFilterPanel();

                document.getElementById('select-carretera').innerHTML = '<option value="">— Todas las Carreteras —</option>';
                document.getElementById('select-tramo').innerHTML = '<option value="">— Todos los Tramos —</option>';
                resetCadenamientosDropdown();

                currentSelections.carreteraId = '';
                currentSelections.tramoId = '';
                currentSelections.ubicacion = '';

                const overlay = document.getElementById('map-overlay');
                if (overlay) overlay.style.display = 'none';

                if (gisMap) {
                    gisMap.setZoom(8);
                    gisMap.panTo({
                        lat: 18.86,
                        lng: -98.20
                    });
                }
            }

            // ─────────────────────────────────────────────────────────────────────────
            // ARRANQUE
            // ─────────────────────────────────────────────────────────────────────────
            initGisMap();
        </script>
    @endscript
</x-filament-panels::page>
