<div class="p-6 max-w-6xl mx-auto space-y-6">
    {{-- Encabezado con navegación de regreso --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.usuarios.index') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 mb-1 inline-flex items-center gap-1 transition">
                &larr; Volver a Usuarios
            </a>
            <h1 class="text-2xl font-bold text-slate-800">Matriz de Permisos Granulares</h1>
            <p class="text-sm text-slate-500">
                Configurando accesos para: 
                <span class="font-bold text-slate-800">{{ $user->nombre }}</span> 
                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full ml-1">{{ $user->rol->nombre ?? 'Sin Rol' }}</span>
            </p>
        </div>

        {{-- Acciones de selección masiva y guardado --}}
        <div class="flex items-center gap-2">
            <button type="button" wire:click="seleccionarTodo" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                Marcar Todos
            </button>
            <button type="button" wire:click="deseleccionarTodo" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                Desmarcar Todos
            </button>
            <button type="button" wire:click="guardar" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold shadow-md shadow-emerald-600/20 transition cursor-pointer">
                Guardar Cambios
            </button>
        </div>
    </div>

    {{-- Notificaciones Flash --}}
    @if (session()->has('mensaje'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r-lg">
            <p class="text-sm font-medium">{{ session('mensaje') }}</p>
        </div>
    @endif
    @if (session()->has('message'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r-lg">
            <p class="text-sm font-medium">{{ session('message') }}</p>
        </div>
    @endif

    {{-- Matriz de Módulos y Acciones --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                    <th class="py-4 px-6">Módulo del Sistema</th>
                    <th class="py-4 px-4 text-center">Mostrar (Ver)</th>
                    <th class="py-4 px-4 text-center">Crear</th>
                    <th class="py-4 px-4 text-center">Editar</th>
                    <th class="py-4 px-4 text-center">Eliminar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @foreach($modulos as $modulo)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-4 px-6 font-semibold text-slate-800">
                            {{ $modulo->nombre }}
                            <span class="block text-[11px] text-slate-400 font-normal uppercase font-mono">clave: {{ $modulo->clave }}</span>
                        </td>
                        
                        <td class="py-4 px-4 text-center">
                            <input type="checkbox" wire:model="permisosSeleccionados.{{ $modulo->id }}.mostrar" 
                                class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                        </td>

                        <td class="py-4 px-4 text-center">
                            <input type="checkbox" wire:model="permisosSeleccionados.{{ $modulo->id }}.crear" 
                                class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                        </td>

                        <td class="py-4 px-4 text-center">
                            <input type="checkbox" wire:model="permisosSeleccionados.{{ $modulo->id }}.editar" 
                                class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                        </td>

                        <td class="py-4 px-4 text-center">
                            <input type="checkbox" wire:model="permisosSeleccionados.{{ $modulo->id }}.eliminar" 
                                class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4 bg-slate-50/50 border-t border-slate-100 flex justify-end">
            <button type="button" wire:click="guardar" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-emerald-600/20 transition cursor-pointer">
                Guardar Cambios
            </button>
        </div>
    </div>
</div>