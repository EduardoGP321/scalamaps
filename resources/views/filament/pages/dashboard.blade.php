<x-filament-panels::page>

    <div class="rounded-xl border border-gray-700 bg-[#040e25] p-4 shadow-md">
        <div class="flex flex-wrap items-end gap-4">

            {{-- Selector de Contrato --}}
            <div class="flex-1 min-w-[200px]">
                <label class="mb-1 block text-sm font-medium text-gray-300">
                    Contrato
                </label>
                <select wire:model.live="contrato_id"
                    class="w-full rounded-lg border border-gray-600 bg-gray-700 px-3 py-2 text-sm text-white focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500">
                    <option value="">Todos los contratos</option>
                    @foreach ($this->getContratos() as $id => $nombre)
                        <option value="{{ $id }}">{{ $nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Selector de Tramo --}}
            <div class="flex-1 min-w-[200px]">
                <label class="mb-1 block text-sm font-medium text-gray-300">
                    Tramo
                </label>
                <select wire:model="tramo_id" @disabled(!$contrato_id)
                    class="w-full rounded-lg border px-3 py-2 text-sm text-white focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500 transition-colors
        {{ !$contrato_id ? 'border-gray-700 bg-[#040e25] text-gray-500 cursor-not-allowed' : 'border-gray-600 bg-gray-700 text-white' }}">
                    @if (!$contrato_id)
                        <option value="">Selecciona un contrato primero...</option>
                    @else
                        <option value="">Todos los tramos</option>
                        @foreach ($this->getTramos() as $id => $nombre)
                            <option value="{{ $id }}">{{ $nombre }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            {{-- Botones --}}
            <div class="flex gap-2">
                <button wire:click="aplicarFiltros"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-400 active:scale-95 transition-all">
                    <x-heroicon-m-funnel class="h-4 w-4" />
                    Aplicar
                </button>

                @if ($this->contrato_id || $this->tramo_id)
                    <button wire:click="limpiarFiltros"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-600 bg-gray-700 px-4 py-2 text-sm font-medium text-gray-300 hover:bg-gray-600 focus:outline-none transition-all">
                        <x-heroicon-m-x-mark class="h-4 w-4" />
                        Limpiar
                    </button>
                @endif
            </div>

        </div>

        @if ($this->contrato_id || $this->tramo_id)
            <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-gray-400">
                <x-heroicon-m-funnel class="h-3.5 w-3.5 text-primary-400" />
                <span>Filtro activo:</span>
                @if ($this->contrato_id)
                    @php $contratos = $this->getContratos(); @endphp
                    <span class="rounded-full bg-primary-900 px-2 py-0.5 text-primary-300 font-medium">
                        {{ $contratos[$this->contrato_id] ?? 'Contrato #' . $this->contrato_id }}
                    </span>
                @endif
                @if ($this->tramo_id)
                    @php $tramos = $this->getTramos(); @endphp
                    <span class="rounded-full bg-indigo-900 px-2 py-0.5 text-indigo-300 font-medium">
                        {{ $tramos[$this->tramo_id] ?? 'Tramo #' . $this->tramo_id }}
                    </span>
                @endif
                <span class="text-yellow-500 italic">— Presiona "Aplicar" para actualizar las gráficas</span>
            </div>
        @endif
    </div>


    <div class="grid grid-cols-1 gap-6 mt-2">
        <x-filament-widgets::widgets :widgets="$this->getWidgets()" :columns="1" />
    </div>

</x-filament-panels::page>
