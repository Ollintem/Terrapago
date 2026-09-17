<div class="p-6 max-w-7xl mx-auto space-y-6">

    <!-- Encabezado y Acción -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Catálogo de Puestos y Roles
            </h1>

            <p class="text-sm text-slate-500">
                Administra los niveles de acceso y cargos para el personal de TerraPago
            </p>
        </div>

        @if(
            auth()->user()->rol &&
            in_array(
                strtolower(auth()->user()->rol->nombre),
                ['administrador', 'super admin', 'superadministrador']
            )
            ||
            auth()->user()->permisos()
                ->whereHas('modulo', fn($q) => $q->where('clave', 'roles'))
                ->where('crear', true)
                ->exists()
        )
            <button
                wire:click="abrirModalCrear"
                class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center gap-2 cursor-pointer"
            >
                <span>+ Nuevo Puesto</span>
            </button>
        @endif
    </div>


    <!-- Mensaje de éxito -->
    @if (session()->has('mensaje'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
            {{ session('mensaje') }}
        </div>
    @endif


    <!-- Mensaje de error -->
    @if (session()->has('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif


    <!-- Tabla de Roles -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

        <!-- Buscador -->
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">

            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Buscar puesto o descripción..."
                class="w-full max-w-sm px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm"
            >

        </div>


        <!-- Tabla -->
        <table class="w-full text-left border-collapse">

            <thead>
                <tr class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">

                    <th class="py-4 px-6">
                        Puesto / Rol
                    </th>

                    <th class="py-4 px-6 text-center">
                        Usuarios Asignados
                    </th>

                    <th class="py-4 px-6 text-right">
                        Acciones
                    </th>

                </tr>
            </thead>


            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">

                @forelse ($roles as $rol)

                    <tr class="hover:bg-slate-50/60 transition">

                        <!-- Nombre y descripción -->
                        <td class="py-4 px-6 font-semibold text-slate-900">

                            {{ $rol->nombre }}

                            @if($rol->descripcion)
                                <p class="text-xs text-slate-400 font-normal mt-0.5">
                                    {{ $rol->descripcion }}
                                </p>
                            @endif

                        </td>


                        <!-- Usuarios asignados -->
                        <td class="py-4 px-6 text-center">

                            <span
                                class="inline-flex items-center justify-center px-3 py-1 text-xs font-bold rounded-full
                                {{ $rol->users_count > 0
                                    ? 'bg-emerald-100 text-emerald-700 border border-emerald-200'
                                    : 'bg-slate-100 text-slate-500'
                                }}"
                            >
                                {{ $rol->users_count }}
                            </span>

                        </td>


                        <!-- Acciones -->
                        <td class="py-4 px-6 text-right space-x-2">

                            <!-- Editar -->
                            @if(
                                auth()->user()->rol &&
                                in_array(
                                    strtolower(auth()->user()->rol->nombre),
                                    ['administrador', 'super admin', 'superadministrador']
                                )
                                ||
                                auth()->user()->permisos()
                                    ->whereHas('modulo', fn($q) => $q->where('clave', 'roles'))
                                    ->where('editar', true)
                                    ->exists()
                            )

                                <button
                                    wire:click="abrirModalEditar({{ $rol->id }})"
                                    class="text-blue-600 hover:text-blue-800 font-medium text-xs bg-blue-50 px-3 py-1.5 rounded-md transition cursor-pointer"
                                >
                                    Editar
                                </button>

                            @endif


                            <!-- Eliminar -->
                            @if(
                                auth()->user()->rol &&
                                in_array(
                                    strtolower(auth()->user()->rol->nombre),
                                    ['administrador', 'super admin', 'superadministrador']
                                )
                                ||
                                auth()->user()->permisos()
                                    ->whereHas('modulo', fn($q) => $q->where('clave', 'roles'))
                                    ->where('eliminar', true)
                                    ->exists()
                            )

                                @if(
                                    !in_array(
                                        strtolower($rol->nombre),
                                        ['administrador', 'super admin', 'superadministrador']
                                    )
                                )

                                    <button
                                        wire:click="eliminar({{ $rol->id }})"
                                        wire:confirm="¿Seguro que deseas eliminar el puesto {{ $rol->nombre }}?"
                                        class="text-rose-600 hover:text-rose-800 font-medium text-xs bg-rose-50 px-3 py-1.5 rounded-md transition cursor-pointer"
                                    >
                                        Eliminar
                                    </button>

                                @endif

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="3"
                            class="p-6 text-center text-slate-400"
                        >
                            No se encontraron puestos registrados.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <!-- Modal Crear / Editar -->
    @if($modalAbierto)

        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 z-50">

            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-4">

                <!-- Título -->
                <h3 class="text-lg font-bold text-slate-800">

                    {{ $modoEdicion
                        ? 'Editar Puesto / Rol'
                        : 'Registrar Nuevo Puesto'
                    }}

                </h3>


                <!-- Formulario -->
                <form
                    wire:submit.prevent="guardar"
                    class="space-y-4"
                >

                    <!-- Nombre del Puesto -->
                    <div>

                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">
                            Nombre del Puesto
                        </label>

                        <input
                            type="text"
                            wire:model="nombre"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none"
                            placeholder="Ej. Cobrador en Campo"
                        >

                        @error('nombre')
                            <span class="text-xs text-rose-500">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    <!-- Botones -->
                    <div class="flex justify-end gap-2 pt-2">

                        <button
                            type="button"
                            wire:click="cerrarModal"
                            class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-lg text-sm font-semibold cursor-pointer"
                        >
                            Cancelar
                        </button>


                        <button
                            type="submit"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold shadow-md shadow-emerald-600/20 cursor-pointer"
                        >
                            Guardar
                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endif

</div>