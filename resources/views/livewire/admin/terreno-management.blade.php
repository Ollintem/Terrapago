<div class="p-6 max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Inventario de Lotes / Terrenos</h1>
            <p class="text-sm text-slate-500">Control de manzanas, superficies, precios y disponibilidad física</p>
        </div>

        @if(auth()->user()->rol && in_array(strtolower(auth()->user()->rol->nombre), ['administrador', 'super admin', 'superadministrador']) 
            || auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'terrenos'))->where('crear', true)->exists())
            <button wire:click="abrirModalCrear" 
                class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center gap-2 cursor-pointer">
                <span>+ Registrar Lote</span>
            </button>
        @endif
    </div>

    @if (session()->has('mensaje'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
            {{ session('mensaje') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-3">
                <input wire:model.live.debounce.300ms="search" type="text" 
                    placeholder="Buscar por Mz, Lote, Ubicación..." 
                    class="w-full sm:w-64 px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">

                <select wire:model.live="filtroEstado" class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">Todos los estados</option>
                    <option value="DISPONIBLE">DISPONIBLE</option>
                    <option value="APARTADO">APARTADO</option>
                    <option value="VENDIDO">VENDIDO</option>
                </select>
            </div>

            <div class="text-xs font-semibold text-slate-500 self-center">
                Total: {{ $terrenos->total() }} lotes
            </div>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                    <th class="py-4 px-6">Lote / Ubicación</th>
                    <th class="py-4 px-6">Medidas y Superficie</th>
                    <th class="py-4 px-6">Precio de Venta</th>
                    <th class="py-4 px-6 text-center">Estado</th>
                    <th class="py-4 px-6 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                @forelse ($terrenos as $terreno)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-4 px-6">
                            <p class="font-bold text-slate-900">Manzana {{ $terreno->manzana }} — Lote {{ $terreno->lote }}</p>
                            @if($terreno->ubicacion)
                                <p class="text-xs text-slate-400 mt-0.5 truncate max-w-xs">{{ $terreno->ubicacion }}</p>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-slate-800 font-medium">{{ $terreno->medidas }}</p>
                            <p class="text-xs text-slate-500">{{ number_format($terreno->superficie, 2) }} m²</p>
                        </td>
                        <td class="py-4 px-6">
                            <p class="font-bold text-emerald-700">${{ number_format($terreno->precio, 2) }}</p>
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($terreno->estado === 'DISPONIBLE')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                    DISPONIBLE
                                </span>
                            @elseif($terreno->estado === 'APARTADO')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                                    APARTADO
                                </span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-200 text-slate-700">
                                    VENDIDO
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right space-x-2">
                            @if(auth()->user()->rol && in_array(strtolower(auth()->user()->rol->nombre), ['administrador', 'super admin', 'superadministrador']) 
                                || auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'terrenos'))->where('editar', true)->exists())
                                <button wire:click="abrirModalEditar({{ $terreno->id }})" 
                                    class="text-blue-600 hover:text-blue-800 font-medium text-xs bg-blue-50 px-3 py-1.5 rounded-md transition cursor-pointer">
                                    Editar
                                </button>
                            @endif

                            @if(auth()->user()->rol && in_array(strtolower(auth()->user()->rol->nombre), ['administrador', 'super admin', 'superadministrador']) 
                                || auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'terrenos'))->where('eliminar', true)->exists())
                                <button wire:click="eliminar({{ $terreno->id }})" 
                                    wire:confirm="¿Seguro que deseas eliminar el Lote {{ $terreno->lote }} de la Manzana {{ $terreno->manzana }}?"
                                    class="text-rose-600 hover:text-rose-800 font-medium text-xs bg-rose-50 px-3 py-1.5 rounded-md transition cursor-pointer">
                                    Eliminar
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-slate-400">No hay lotes registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-slate-100">
            {{ $terrenos->links() }}
        </div>
    </div>

    @if($modalAbierto)
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 space-y-4">
                <h3 class="text-lg font-bold text-slate-800">
                    {{ $terrenoId ? 'Editar Lote / Terreno' : 'Registrar Nuevo Lote' }}
                </h3>

                <form wire:submit.prevent="guardar" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Manzana *</label>
                            <input type="text" wire:model="manzana" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('manzana') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Lote *</label>
                            <input type="text" wire:model="lote" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('lote') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Medidas *</label>
                            <input type="text" wire:model="medidas" placeholder="Ej. 10x20 m" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('medidas') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Superficie (m²) *</label>
                            <input type="number" step="0.01" wire:model="superficie" placeholder="200.00" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('superficie') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Precio ($) *</label>
                            <input type="number" step="0.01" wire:model="precio" placeholder="400000.00" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('precio') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Estado *</label>
                            <select wire:model="estado" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="DISPONIBLE">DISPONIBLE</option>
                                <option value="APARTADO">APARTADO</option>
                                <option value="VENDIDO">VENDIDO</option>
                            </select>
                            @error('estado') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Ubicación / Referencia</label>
                        <input type="text" wire:model="ubicacion" placeholder="Esquina norte, frente al parque" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                        @error('ubicacion') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="cerrarModal" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-lg text-sm font-semibold cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold shadow-md shadow-emerald-600/20 cursor-pointer">
                            Guardar Lote
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>