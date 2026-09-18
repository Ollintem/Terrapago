<div class="p-6 max-w-7xl mx-auto space-y-6">
    {{-- Encabezado y Botón de Nuevo Cliente --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Cartera de Clientes</h1>
            <p class="text-sm text-slate-500">Gestión de compradores y titulares para contratos de lotes</p>
        </div>

        @if(auth()->user()->rol && in_array(strtolower(auth()->user()->rol->nombre), ['administrador', 'super admin', 'superadministrador']) 
            || auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'clientes'))->where('crear', true)->exists())
            <button wire:click="abrirModalCrear" 
                class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center gap-2 cursor-pointer">
                <span>+ Nuevo Cliente</span>
            </button>
        @endif
    </div>

    {{-- Notificaciones Flash --}}
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

    {{-- Contenedor de la Tabla --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <input wire:model.live.debounce.300ms="search" type="text" 
                placeholder="Buscar por nombre, apellidos, teléfono, RFC o CURP..." 
                class="w-full max-w-sm px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
            
            <div class="text-xs font-semibold text-slate-500 self-center">
                Total: {{ $clientes->total() }} clientes
            </div>
        </div>

        @php
            $esAdmin = auth()->user()->rol && in_array(strtolower(auth()->user()->rol->nombre), ['administrador', 'super admin', 'superadministrador']);
        @endphp

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                    <th class="py-4 px-6">Cliente</th>
                    @if($esAdmin)
                        <th class="py-4 px-6">Asesor Asignado</th>
                    @endif
                    <th class="py-4 px-6">Contacto</th>
                    <th class="py-4 px-6">Documentación</th>
                    <th class="py-4 px-6 text-center">Estado</th>
                    <th class="py-4 px-6 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                @forelse ($clientes as $cliente)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-4 px-6">
                            <p class="font-semibold text-slate-900">{{ $cliente->nombre_completo }}</p>
                            @if($cliente->direccion)
                                <p class="text-xs text-slate-400 mt-0.5 truncate max-w-xs" title="{{ $cliente->direccion }}">
                                    {{ $cliente->direccion }}
                                </p>
                            @endif
                        </td>

                        {{-- Columna visible solo para Administrador --}}
                        @if($esAdmin)
                            <td class="py-4 px-6">
                                @if($cliente->asesor)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-md bg-slate-100 text-slate-700">
                                        👤 {{ $cliente->asesor->nombre }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400 italic">Sin Asesor</span>
                                @endif
                            </td>
                        @endif

                        <td class="py-4 px-6">
                            <p class="text-slate-800 font-medium">{{ $cliente->telefono }}</p>
                            <p class="text-xs text-slate-500">{{ $cliente->email ?? 'Sin correo' }}</p>
                        </td>

                        <td class="py-4 px-6">
                            <div class="text-xs space-y-0.5">
                                <p><span class="font-semibold text-slate-500">RFC:</span> {{ $cliente->rfc ?? 'N/A' }}</p>
                                <p><span class="font-semibold text-slate-500">CURP:</span> {{ $cliente->curp ?? 'N/A' }}</p>
                            </div>
                        </td>

                        <td class="py-4 px-6 text-center">
                            @if($esAdmin || auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'clientes'))->where('editar', true)->exists())
                                <button wire:click="cambiarEstado({{ $cliente->id }})" 
                                    class="px-2.5 py-1 text-xs font-semibold rounded-full cursor-pointer transition {{ $cliente->estado ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-rose-100 text-rose-800 hover:bg-rose-200' }}">
                                    {{ $cliente->estado ? 'Activo' : 'Inactivo' }}
                                </button>
                            @else
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $cliente->estado ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $cliente->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            @endif
                        </td>

                        <td class="py-4 px-6 text-right space-x-2">
                            {{-- Botón Editar --}}
                            @if($esAdmin || auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'clientes'))->where('editar', true)->exists())
                                <button wire:click="abrirModalEditar({{ $cliente->id }})" 
                                    class="text-blue-600 hover:text-blue-800 font-medium text-xs bg-blue-50 px-3 py-1.5 rounded-md transition cursor-pointer">
                                    Editar
                                </button>
                            @endif

                            {{-- Botón Eliminar --}}
                            @if($esAdmin || auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'clientes'))->where('eliminar', true)->exists())
                                <button wire:click="eliminar({{ $cliente->id }})" 
                                    wire:confirm="¿Seguro que deseas eliminar al cliente {{ $cliente->nombre_completo }}?"
                                    class="text-rose-600 hover:text-rose-800 font-medium text-xs bg-rose-50 px-3 py-1.5 rounded-md transition cursor-pointer">
                                    Eliminar
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $esAdmin ? 6 : 5 }}" class="p-6 text-center text-slate-400">
                            No se encontraron clientes registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-slate-100">
            {{ $clientes->links() }}
        </div>
    </div>

    {{-- Modal Registro / Edición --}}
    @if($modalAbierto)
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl p-6 space-y-4 max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-bold text-slate-800">
                    {{ $clienteId ? 'Editar Cliente' : 'Registrar Nuevo Cliente' }}
                </h3>

                <form wire:submit.prevent="guardar" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Nombre *</label>
                            <input type="text" wire:model="nombre" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('nombre') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Ap. Paterno *</label>
                            <input type="text" wire:model="apellido_paterno" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('apellido_paterno') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Ap. Materno</label>
                            <input type="text" wire:model="apellido_materno" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('apellido_materno') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Teléfono *</label>
                            <input type="text" wire:model="telefono" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="10 dígitos">
                            @error('telefono') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">F. Nacimiento</label>
                            <input type="date" wire:model="fecha_nacimiento" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('fecha_nacimiento') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Correo</label>
                            <input type="email" wire:model="email" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('email') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">CURP</label>
                            <input type="text" wire:model="curp" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm uppercase focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('curp') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">RFC</label>
                            <input type="text" wire:model="rfc" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm uppercase focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('rfc') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Dirección Completa</label>
                        <textarea wire:model="direccion" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Calle, número, colonia, municipio"></textarea>
                        @error('direccion') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" id="estado" wire:model="estado" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                        <label for="estado" class="text-xs font-semibold text-slate-700 cursor-pointer">Cliente Activo</label>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="cerrarModal" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-lg text-sm font-semibold cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold shadow-md shadow-emerald-600/20 cursor-pointer">
                            Guardar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>