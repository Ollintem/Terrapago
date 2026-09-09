<div class="p-6 max-w-6xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ url('/admin/usuarios') }}" class="text-sm text-emerald-600 hover:underline mb-1 inline-block">&larr; Volver a Usuarios</a>
            <h1 class="text-2xl font-bold text-slate-800">Matriz de Permisos Granulares</h1>
            <p class="text-sm text-slate-500">Configurando: <span class="font-semibold text-slate-700">{{ $usuario->nombre }}</span></p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-4 rounded-r-lg text-sm">
            <p>{{ session('message') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                    <th class="p-4">Módulo</th>
                    <th class="p-4 text-center">Mostrar</th>
                    <th class="p-4 text-center">Crear</th>
                    <th class="p-4 text-center">Editar</th>
                    <th class="p-4 text-center">Eliminar</th>
                    <th class="p-4 text-center">Gestionar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                @foreach($modulos as $modulo)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-4 font-semibold text-slate-800">{{ $modulo->nombre }}</td>
                        @foreach(['mostrar', 'crear', 'editar', 'eliminar', 'gestionar'] as $accion)
                            <td class="p-4 text-center">
                                <input type="checkbox" 
                                    wire:model.live="permisosMatriz.{{ $modulo->id }}.{{ $accion }}"
                                    wire:change="actualizarPermiso({{ $modulo->id }}, '{{ $accion }}')"
                                    class="w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500 cursor-pointer">
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>