<div class="p-6 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Gestión de Usuarios y Personal</h1>
        <button wire:click="abrirModal" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium transition shadow-sm">
            + Nuevo Usuario
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-4 rounded-r-lg">
            <p>{{ session('message') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <input wire:model.live="search" type="text" placeholder="Buscar por nombre o correo..." class="w-full max-w-sm px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wider font-semibold">
                    <th class="p-4">Nombre</th>
                    <th class="p-4">Correo</th>
                    <th class="p-4">Rol Asignado</th>
                    <th class="p-4">Estado</th>
                    <th class="p-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                @forelse($usuarios as $user)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-4 font-medium text-slate-900">{{ $user->nombre }}</td>
                        <td class="p-4 text-slate-500">{{ $user->email }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">
                                {{ $user->rol->nombre ?? 'Sin Rol' }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $user->estado ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $user->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="p-4 text-center space-x-2">
                            <button wire:click="editar({{ $user->id }})" class="text-blue-600 hover:text-blue-800 font-medium text-xs bg-blue-50 px-3 py-1.5 rounded-md transition">Editar</button>
                            
                            <!-- Botón hacia el módulo de permisos -->
                            <a href="{{ url('/admin/usuarios/' . $user->id . '/permisos') }}" class="inline-block bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-medium text-xs px-3 py-1.5 rounded-md transition">
                                Configurar Permisos
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-slate-400">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">
            {{ $usuarios->links() }}
        </div>
    </div>

    @if($isModalOpen)
        <div class="fixed inset-0 bg-slate-900/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
                <h3 class="text-lg font-bold text-slate-800 mb-4">{{ $user_id ? 'Editar Usuario' : 'Registrar Nuevo Usuario' }}</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Nombre Completo</label>
                        <input wire:model="nombre" type="text" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        @error('nombre') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Correo Electrónico</label>
                        <input wire:model="email" type="email" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        @error('email') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Contraseña</label>
                        <input wire:model="password" type="password" placeholder="{{ $user_id ? 'En blanco para no cambiar' : '' }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        @error('password') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Rol</label>
                        <select wire:model="rol_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white">
                            <option value="">Selecciona rol...</option>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                            @endforeach
                        </select>
                        @error('rol_id') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button wire:click="$set('isModalOpen', false)" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50">Cancelar</button>
                    <button wire:click="guardar" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">Guardar</button>
                </div>
            </div>
        </div>
    @endif
</div>