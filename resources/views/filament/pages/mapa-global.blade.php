<x-filament-panels::page>
    <div class="space-y-4">
        {{-- Header de Filtros --}}
        <div
            class="grid grid-cols-1 gap-4 p-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.live="contratoId" id="contrato-selector">
                        <option value="">Seleccione un Contrato</option>
                        @foreach ($contratos as $c)
                            <option value="{{ $c->id }}">{{ $c->nombre_corto }} ({{ $c->numero_contrato }})
                            </option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>

                <x-filament::input.wrapper prefix-icon="heroicon-m-magnifying-glass">
                    <select id="select-ubicacion"
                        class="fi-select-input block w-full border-none bg-transparent py-1.5 pe-8 ps-3 text-sm leading-6 text-gray-950 outline-none transition duration-75 focus:ring-0 disabled:pointer-events-none disabled:opacity-50 dark:text-white">
                        <option value="">Todos los Cadenamientos</option>
                    </select>
                </x-filament::input.wrapper>

                <div wire:loading class="flex items-center justify-end text-primary-600">
                    <x-filament::loading-indicator class="h-5 w-5" />
                </div>
            </div>

            <div id="filter-panel"
                class="opacity-0 transition-all duration-500 space-y-3 border-t border-gray-100 dark:border-gray-800 pt-3">
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
                </div>
            </div>
        </div>

        <div wire:ignore
            class="relative w-full rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 shadow-2xl"
            style="height: 750px;">
            <div id="map" style="width: 100%; height: 100%; background-color: #f3f4f6;"></div>

            {{-- Overlay --}}
            <div id="map-overlay"
                class="absolute top-6 right-6 bg-white/90 dark:bg-gray-950/95 p-6 rounded-2xl shadow-2xl backdrop-blur-md border border-white/20 max-w-xs min-w-[260px] pointer-events-none transition-all"
                style="display: none;">
                <div class="mb-4">
                    <h2 id="overlay-nombre" class="text-base font-bold text-gray-900 dark:text-white leading-tight">
                    </h2>
                    <p id="overlay-numero" class="text-[10px] font-mono text-primary-600 mt-1 uppercase tracking-wider">
                    </p>
                </div>

                <div class="space-y-4">
                    <div id="asset-details" class="hidden space-y-3 animate-fade-in pointer-events-auto">
                        <hr class="border-gray-100 dark:border-gray-800">
                        <div
                            class="p-3 bg-primary-500/5 dark:bg-primary-500/10 rounded-2xl border border-primary-500/10">
                            <span class="block text-[8px] uppercase font-bold text-primary-600 mb-1">Activo
                                Seleccionado</span>
                            <span id="detail-nombre"
                                class="text-sm font-black text-gray-900 dark:text-white leading-none"></span>
                        </div>
                        <div id="metadatos-container" class="grid grid-cols-1 gap-2"></div>
                    </div>

                    <div class="flex items-center justify-between px-1">
                        <span id="overlay-activos" class="text-[10px] font-bold text-gray-400"></span>
                    </div>

                    <div id="overlay-zoom-hint"
                        class="flex items-center gap-2 text-[10px] text-amber-600 bg-amber-50 dark:bg-amber-900/20 p-2.5 rounded-xl border border-amber-100 dark:border-amber-800/50"
                        style="display:none;">
                        <x-filament::icon icon="heroicon-m-magnifying-glass-plus" class="h-4 w-4" />
                        <span>Acércate para ver detalles técnicos</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @script
        <script>
            let gisMap = null;
            let gisData = null;
            let lastZoomLevel = 5;
            let currentlySelectedAssetId = null;
            let currentSelections = {
                nombres: new Set(),
                materiales: new Set(),
                condiciones: new Set(),
                ubicacion: ''
            };
            let gisLayers = {
                routes: [], // array porque ahora son varios tramos
                assets: []
            };

            // ─── Listener de Livewire ────────────────────────────────────────────
            window.addEventListener('update-map', event => {
                const data = event.detail.payload;
                if (!data) return;

                gisData = data;

                // --- Agrega esto para actualizar la información visual ---
                if (document.getElementById('txt-nombre-contrato')) {
                    document.getElementById('txt-nombre-contrato').textContent = data.info.nombre;
                }

                // Aquí puedes poner: "Año: 2023 | Empresa: CAPSA"
                if (document.getElementById('txt-detalle-contrato')) {
                    document.getElementById('txt-detalle-contrato').textContent =
                        `Año: ${data.info.anio} | Empresa: ${data.info.empresa}`;
                }

                // Tu lógica de inicialización de Sets que ya tienes
                currentSelections.nombres = new Set(data.info.filtros.nombres);
                currentSelections.materiales = new Set(data.info.filtros.materiales);
                currentSelections.condiciones = new Set(data.info.filtros.condiciones);
                currentSelections.ubicacion = null;

                refreshAssetsOnMap();
            });

            // ─── Inicialización del mapa ─────────────────────────────────────────
            async function initGisMap() {
                const {
                    Map
                } = await google.maps.importLibrary("maps");

                gisMap = new Map(document.getElementById("map"), {
                    center: {
                        lat: 18.86,
                        lng: -98.20
                    }, // Centro en Puebla
                    zoom: 8,
                    mapId: 'e7f7ed4aef1512f61b25b129',
                    disableDefaultUI: false,
                    streetViewControl: false
                });

                gisMap.addListener('idle', () => {
                    const newZoom = gisMap.getZoom();
                    const hint = document.getElementById('overlay-zoom-hint');
                    if (hint) hint.style.display = newZoom < 14 ? 'flex' : 'none';

                    // Refresca marcadores de extremos cuando cruzamos el umbral zoom 14
                    if ((lastZoomLevel < 14 && newZoom >= 14) || (lastZoomLevel >= 14 && newZoom < 14)) {
                        refreshAssetsOnMap();
                    }
                    lastZoomLevel = newZoom;
                });

                // Listener select cadenamiento
                const selectUbi = document.getElementById('select-ubicacion');
                if (selectUbi) {
                    selectUbi.addEventListener('change', (e) => {
                        currentSelections.ubicacion = e.target.value;
                        refreshAssetsOnMap();
                    });
                }

                @if ($gisData)
                    loadData(@js($gisData));
                @endif
            }

            // ─── Carga de datos completa ─────────────────────────────────────────
            async function loadData(payload) {
                if (!payload) {
                    resetUI();
                    return;
                }
                gisData = payload;
                if (!gisMap) return;

                setupDynamicFilters(payload.info.filtros);
                clearAllLayers();
                updateOverlayBaseInfo();
                await drawAllRoutes();
                await refreshAssetsOnMap();
                autofitRoutes();
            }

            // ─── Dibuja las rutas de TODOS los tramos ────────────────────────────
            async function drawAllRoutes() {
                if (!gisData?.routes_main?.length) return;

                const {
                    Polyline
                } = await google.maps.importLibrary("maps");

                for (const route of gisData.routes_main) {

                    // Caso A: el tramo tiene polyline_google guardado en BD
                    if (route.polyline) {
                        const path = google.maps.geometry.encoding.decodePath(route.polyline);
                        const poly = new Polyline({
                            path,
                            strokeColor: '#0f172a',
                            strokeWeight: 10,
                            strokeOpacity: 0.85,
                            map: gisMap
                        });
                        gisLayers.routes.push(poly);
                        continue;
                    }

                    // Caso B: no hay polyline guardado, llamamos la Routes API en vivo
                    if (!route.origin?.lat || !route.destination?.lat) continue;

                    try {
                        const res = await fetch('https://routes.googleapis.com/directions/v2:computeRoutes', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Goog-Api-Key': '{{ config('services.google_maps.key') }}',
                                'X-Goog-Fieldmask': 'routes.polyline'
                            },
                            body: JSON.stringify({
                                origin: {
                                    location: {
                                        latLng: {
                                            latitude: route.origin.lat,
                                            longitude: route.origin.lng
                                        }
                                    }
                                },
                                destination: {
                                    location: {
                                        latLng: {
                                            latitude: route.destination.lat,
                                            longitude: route.destination.lng
                                        }
                                    }
                                },
                                travelMode: 'DRIVE'
                            })
                        });
                        const json = await res.json();
                        if (json.routes?.length) {
                            const path = google.maps.geometry.encoding.decodePath(json.routes[0].polyline.encodedPolyline);
                            const poly = new Polyline({
                                path,
                                strokeColor: '#0f172a',
                                strokeWeight: 10,
                                strokeOpacity: 0.85,
                                map: gisMap
                            });
                            gisLayers.routes.push(poly);
                        }
                    } catch (err) {
                        console.warn('No se pudo dibujar ruta para tramo:', route.nombre_tramo, err);
                    }
                }
            }

            // ─── Dibuja activos (polilíneas y marcadores) ────────────────────────
            async function refreshAssetsOnMap() {
                const {
                    AdvancedMarkerElement,
                    PinElement
                } = await google.maps.importLibrary("marker");
                const {
                    Polyline
                } = await google.maps.importLibrary("maps");

                clearAssets();

                if (!gisData?.assets?.length) {
                    document.getElementById('overlay-activos').textContent = 'Activos: 0';
                    return;
                }

                const currentZoom = gisMap.getZoom();
                const showPolyMarkers = currentZoom >= 14;

                gisData.assets.forEach(asset => {
                    const f = asset.filter_data;
                    const data = asset.payload;

                    // Filtros
                    if (!currentSelections.nombres.has(f.nombre)) return;
                    if (!currentSelections.materiales.has(f.material)) return;
                    if (!currentSelections.condiciones.has(f.condicion)) return;
                    if (currentSelections.ubicacion && f.ubicacion !== currentSelections.ubicacion) return;

                    // --- Polilínea (alcantarilla con latitud/longitud de entrada y salida) ---
                    if (data.path) {
                        const poly = new Polyline({
                            path: data.path,
                            strokeColor: data.color,
                            strokeWeight: 7,
                            strokeOpacity: 0.9,
                            map: gisMap,
                            clickable: true
                        });
                        poly.addListener('click', () => {
                            currentlySelectedAssetId = data.id;
                            showAssetDetails(data);
                        });
                        gisLayers.assets.push(poly);

                        // Pines en extremos solo con zoom alto
                        if (showPolyMarkers) {
                            data.path.forEach(pos => {
                                const dot = new AdvancedMarkerElement({
                                    position: pos,
                                    map: gisMap,
                                    content: new PinElement({
                                        scale: 0.5,
                                        background: data.color,
                                        borderColor: '#ffffff'
                                    }).element
                                });
                                dot.addListener('click', () => {
                                    currentlySelectedAssetId = data.id;
                                    showAssetDetails(data);
                                });
                                gisLayers.assets.push(dot);
                            });
                        }
                    }

                    // --- Marcador puntual (activo con latitud/longitud directa) ---
                    else if (data.position) {
                        const marker = new AdvancedMarkerElement({
                            position: data.position,
                            map: gisMap,
                            title: data.nombre,
                            content: new PinElement({
                                background: data.color || '#10b981',
                                borderColor: '#ffffff',
                                scale: 0.8
                            }).element
                        });
                        marker.addListener('click', () => {
                            currentlySelectedAssetId = data.id;
                            showAssetDetails(data);
                        });
                        gisLayers.assets.push(marker);
                    }
                });

                document.getElementById('overlay-activos').textContent =
                    `Activos visibles: ${gisLayers.assets.length}`;
            }

            // ─── Filtros dinámicos ───────────────────────────────────────────────
            function setupDynamicFilters(filtros) {
                const panel = document.getElementById('filter-panel');
                if (panel) panel.style.opacity = '1';

                // Selector de cadenamiento
                const selectUbi = document.getElementById('select-ubicacion');
                if (selectUbi) {
                    selectUbi.innerHTML = '<option value="">Todos los Claros / Cadenamientos</option>';
                    (filtros.ubicaciones || []).forEach(u => {
                        const opt = document.createElement('option');
                        opt.value = u;
                        opt.textContent = u;
                        selectUbi.appendChild(opt);
                    });
                }

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

                categories.forEach(cat => {
                    const container = document.getElementById(cat.id);
                    if (!container) return;
                    container.innerHTML = '';
                    currentSelections[cat.key] = new Set(cat.items);

                    cat.items.forEach(item => {
                        const btn = document.createElement('button');
                        const update = () => {
                            const active = currentSelections[cat.key].has(item);
                            btn.className = `px-2 py-1 rounded-md text-[10px] font-bold border transition-all ${
                                active
                                    ? 'bg-primary-600 border-primary-600 text-white'
                                    : 'bg-white dark:bg-gray-800 text-gray-400 border-gray-200'
                            }`;
                        };
                        btn.textContent = item;
                        update();
                        btn.onclick = () => {
                            if (currentSelections[cat.key].has(item)) currentSelections[cat.key].delete(
                                item);
                            else currentSelections[cat.key].add(item);
                            update();
                            refreshAssetsOnMap();
                        };
                        container.appendChild(btn);
                    });
                });
            }

            // ─── Panel de detalles del activo ────────────────────────────────────
            function showAssetDetails(data) {
                const detailPanel = document.getElementById('asset-details');
                const container = document.getElementById('metadatos-container');
                if (!detailPanel || !container) return;

                detailPanel.classList.remove('hidden');
                document.getElementById('detail-nombre').textContent = data.nombre;
                container.innerHTML = '';

                Object.entries(data.metadatos).forEach(([key, value]) => {
                    if (!value) return;

                    if (key === 'Imagenes' && Array.isArray(value)) {
                        // Solo mostramos imágenes si tienen URL válida
                        const validImgs = value.filter(img => img.url && !img.url.endsWith('/'));
                        if (!validImgs.length) return;

                        let html = `<div class="grid grid-cols-2 gap-2 mt-2">`;
                        validImgs.forEach(img => {
                            html += `<div class="space-y-1">
                                <img src="${img.url}"
                                     class="w-full h-20 object-cover rounded-lg border border-gray-200 cursor-pointer"
                                     onclick="window.open('${img.url}', '_blank')"
                                     onerror="this.parentElement.style.display='none'">
                                <span class="block text-[8px] text-gray-500 truncate">${img.titulo}</span>
                            </div>`;
                        });
                        container.innerHTML += html + `</div>`;
                    } else if (key !== 'Comentarios') {
                        container.innerHTML += `
                            <div class="flex flex-col p-2 bg-white dark:bg-gray-900 rounded-xl border border-gray-100 shadow-sm">
                                <span class="text-[8px] uppercase font-bold text-gray-400">${key}</span>
                                <span class="text-[10px] font-bold text-gray-700 dark:text-gray-200">${value}</span>
                            </div>`;
                    }
                });

                if (data.url_view) {
                    container.innerHTML += `
                        <a href="${data.url_view}"
                           class="mt-4 block w-full py-2 text-center text-xs font-bold text-white bg-primary-600 rounded-xl pointer-events-auto">
                            Ver Información Completa
                        </a>`;
                }
            }

            // ─── Autofit: ajusta el mapa para ver todos los tramos ───────────────
            function autofitRoutes() {
                if (!gisMap || !gisData?.routes_main?.length) return;
                const bounds = new google.maps.LatLngBounds();

                gisData.routes_main.forEach(route => {
                    if (route.origin?.lat) bounds.extend({
                        lat: route.origin.lat,
                        lng: route.origin.lng
                    });
                    if (route.destination?.lat) bounds.extend({
                        lat: route.destination.lat,
                        lng: route.destination.lng
                    });
                });

                // También incluye las posiciones de activos para mejor encuadre
                (gisData.assets || []).forEach(asset => {
                    const d = asset.payload;
                    if (d.path) d.path.forEach(p => bounds.extend(p));
                    if (d.position) bounds.extend(d.position);
                });

                gisMap.fitBounds(bounds, {
                    padding: 80
                });
            }

            // ─── Info básica del overlay ─────────────────────────────────────────
            function updateOverlayBaseInfo() {
                const overlay = document.getElementById('map-overlay');
                if (overlay) overlay.style.display = 'block';
                document.getElementById('overlay-nombre').textContent = gisData?.info?.nombre ?? '';
                document.getElementById('overlay-numero').textContent = gisData?.info?.numero ?? '';
                const detailPanel = document.getElementById('asset-details');
                if (detailPanel) detailPanel.classList.add('hidden');
            }

            // ─── Limpieza ────────────────────────────────────────────────────────
            function clearAllLayers() {
                gisLayers.routes.forEach(r => r.setMap(null));
                gisLayers.routes = [];
                clearAssets();
            }

            function clearAssets() {
                gisLayers.assets.forEach(asset => {
                    if (typeof asset.setMap === 'function') asset.setMap(null);
                    else asset.map = null;
                });
                gisLayers.assets = [];
            }

            function resetUI() {
                clearAllLayers();
                const selectUbi = document.getElementById('select-ubicacion');
                if (selectUbi) {
                    selectUbi.innerHTML = '<option value="">Todos los Claros / Cadenamientos</option>';
                }
                const overlay = document.getElementById('map-overlay');
                if (overlay) overlay.style.display = 'none';
                const panel = document.getElementById('filter-panel');
                if (panel) panel.style.opacity = '0';
                currentlySelectedAssetId = null;

                if (gisMap) {
                    gisMap.setZoom(8);
                    gisMap.panTo({
                        lat: 18.86,
                        lng: -98.20
                    });
                }
            }

            initGisMap();
        </script>
    @endscript
</x-filament-panels::page>
