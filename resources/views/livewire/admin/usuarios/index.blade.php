<div class="p-6 max-w-7xl mx-auto">

    {{-- =========================================================
     ENCABEZADO PRINCIPAL
========================================================= --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        <div>

            {{-- SECCIÓN --}}
            <div class="flex items-center gap-2 mb-2">

                <span class="h-2 w-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></span>

                <span class="text-[11px] font-bold uppercase tracking-[0.16em] text-emerald-600">
                    Administración
                </span>

            </div>

            {{-- TÍTULO --}}
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900">

                Gestión de

                <span class="text-emerald-600">
                    Usuarios
                </span>

            </h1>

            {{-- DESCRIPCIÓN --}}
            <p class="mt-2 text-sm sm:text-base font-medium text-slate-500 max-w-xl">

                Administra los usuarios, roles y permisos de

                <span class="font-semibold text-slate-700">
                    TerraPago
                </span>.

            </p>

        </div>

        {{-- BOTÓN NUEVO USUARIO --}}
        <div class="flex items-center gap-3">

            @if (
                (auth()->user()->rol &&
                    in_array(strtolower(auth()->user()->rol->nombre), ['administrador', 'super admin', 'superadministrador'])) ||
                    auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'usuarios'))->where('crear', true)->exists())
                <button wire:click="abrirModal"
                    class="group
                           flex items-center gap-2
                           bg-emerald-600
                           hover:bg-emerald-700
                           text-white
                           px-4 py-2.5
                           rounded-xl
                           font-semibold
                           text-sm
                           shadow-lg
                           shadow-emerald-600/20
                           hover:shadow-emerald-600/30
                           hover:-translate-y-0.5
                           transition-all
                           duration-200
                           cursor-pointer">

                    <span
                        class="text-lg
                               leading-none
                               transition-transform
                               duration-200
                               group-hover:rotate-90">
                        +
                    </span>

                    Nuevo Usuario

                </button>
            @endif

        </div>

    </div>


    {{-- =========================================================
         TARJETAS DE RESUMEN
    ========================================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

        {{-- USUARIOS ACTIVOS --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 hover:shadow-md transition">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                Usuarios activos
            </p>
            <p class="mt-1 text-3xl font-extrabold text-emerald-600">
                {{ $usuariosActivos }}
            </p>
            <p class="mt-1 text-xs text-slate-400">
                con acceso habilitado
            </p>
        </div>

        {{-- USUARIOS INACTIVOS --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 hover:shadow-md transition">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                Usuarios inactivos
            </p>
            <p class="mt-1 text-3xl font-extrabold text-slate-700">
                {{ $usuariosInactivos }}
            </p>
            <p class="mt-1 text-xs text-slate-400">
                sin acceso al sistema
            </p>
        </div>

        {{-- ROLES CONFIGURADOS --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 hover:shadow-md transition">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                Roles configurados
            </p>
            <p class="mt-1 text-3xl font-extrabold text-violet-600">
                {{ $rolesConfigurados }}
            </p>
            <p class="mt-1 text-xs text-slate-400 truncate">
                {{ $roles->map(fn($r) => $r->nombre ?? $r->name)->take(3)->implode(', ') }}
            </p>
        </div>

        {{-- ÚLTIMO ACCESO --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 hover:shadow-md transition">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                Último acceso
            </p>

            @php
                $fechaAcceso = $ultimoUsuario?->ultimo_acceso ?? $ultimoUsuario?->updated_at;
                if ($fechaAcceso) {
                    // Forzar zona horaria de México
                    $fechaAcceso = $fechaAcceso->setTimezone('America/Mexico_City');
                }
            @endphp

            @if ($ultimoUsuario && $fechaAcceso)
                <p class="mt-1 text-2xl font-extrabold text-slate-800">
                    {{ $fechaAcceso->format('H:i') }} {{-- O 'h:i A' si prefieres formato 12 hrs (ej: 02:46 PM) --}}
                </p>
                <p class="mt-1 text-xs text-slate-400 truncate" title="{{ $ultimoUsuario->email }}">
                    {{ $fechaAcceso->isToday() ? 'hoy' : $fechaAcceso->diffForHumans() }} · {{ $ultimoUsuario->email }}
                </p>
            @else
                <p class="mt-1 text-2xl font-extrabold text-slate-400">
                    --:--
                </p>
                <p class="mt-1 text-xs text-slate-400">
                    Sin inicios de sesión
                </p>
            @endif
        </div>

    </div>


    {{-- =========================================================
        NOTIFICACIONES (CON AUTODESAPARICIÓN DE 5 SEGUNDOS)
    ========================================================== --}}
    @if (session()->has('mensaje'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
            x-transition:leave="transition ease-in duration-500"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            class="flex items-center justify-between bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-4 rounded-r-lg">
            <p class="text-sm font-semibold">{{ session('mensaje') }}</p>
            <button type="button" @click="show = false"
                class="text-emerald-500 hover:text-emerald-700 font-bold cursor-pointer">✕</button>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
            x-transition:leave="transition ease-in duration-500"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            class="flex items-center justify-between bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 mb-4 rounded-r-lg">
            <p class="text-sm font-semibold">{{ session('error') }}</p>
            <button type="button" @click="show = false"
                class="text-rose-500 hover:text-rose-700 font-bold cursor-pointer">✕</button>
        </div>
    @endif


    {{-- =========================================================
         TABLA PRINCIPAL
    ========================================================== --}}
    <div
        class="bg-white
               rounded-2xl
               shadow-sm
               border border-slate-200
               overflow-hidden">


        {{-- =====================================================
             BUSCADOR
        ====================================================== --}}
        <div
            class="p-5
                   border-b border-slate-100
                   bg-slate-50/40
                   flex flex-col sm:flex-row
                   sm:items-center
                   sm:justify-between
                   gap-3">

            <div class="relative w-full">

                {{-- ICONO --}}
                <div
                    class="absolute
                           inset-y-0
                           left-0
                           flex items-center
                           pl-4
                           pointer-events-none">

                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />

                    </svg>

                </div>


                {{-- BUSCADOR --}}
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Buscar por nombre o correo electronico..."
                    class="w-full
                           pl-12
                           pr-4
                           py-3
                           bg-white
                           border border-slate-200
                           rounded-xl
                           text-sm
                           font-medium
                           text-slate-700
                           placeholder:text-slate-400
                           shadow-sm
                           focus:outline-none
                           focus:ring-2
                           focus:ring-emerald-500/30
                           focus:border-emerald-500
                           transition">

            </div>


            <span
                class="text-xs
                       font-medium
                       text-slate-400
                       whitespace-nowrap">
                {{ $usuarios->total() }} usuarios
            </span>

        </div>


        {{-- =====================================================
             TABLA
        ====================================================== --}}
        <div class="overflow-x-auto">

            <table class="w-full text-left border-collapse">

                {{-- ENCABEZADOS --}}
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-[11px] uppercase tracking-[0.12em] font-bold">
                        <th class="p-4">Usuario</th>
                        <th class="p-4">Correo</th>
                        <th class="p-4 text-center">Rol</th>
                        <th class="p-4 text-center">Estado</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>

                {{-- CUERPO --}}
                <tbody class="divide-y divide-slate-100 text-sm">

                    @forelse($usuarios as $user)

                        <tr class="hover:bg-slate-50/60 transition duration-150">

                            {{-- USUARIO --}}
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    {{-- AVATAR --}}
                                    <div
                                        class="h-10 w-10 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-bold uppercase flex-shrink-0">
                                        {{ strtoupper(substr($user->name ?? ($user->nombre ?? 'U'), 0, 2)) }}
                                    </div>

                                    <div>
                                        <p class="font-semibold text-slate-900 tracking-tight">
                                            {{ $user->name ?? $user->nombre }}
                                        </p>
                                        <p class="text-[11px] text-slate-400 font-medium">
                                            Usuario del sistema
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- CORREO --}}
                            <td class="p-4">
                                <span class="text-slate-500 font-medium">
                                    {{ $user->email }}
                                </span>
                            </td>

                            {{-- ROL --}}
                            <td class="p-4 text-center">
                                @php
                                    $nombreRol = $user->rol->nombre ?? ($user->rol->name ?? 'Sin Rol');
                                    $esAdmin = in_array(strtolower($nombreRol), [
                                        'administrador',
                                        'super admin',
                                        'superadministrador',
                                    ]);
                                @endphp

                                @if ($esAdmin)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $nombreRol }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        {{ $nombreRol }}
                                    </span>
                                @endif
                            </td>

                            {{-- ESTADO --}}
                            <td class="p-4 text-center">
                                @if ($user->activo)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Activo
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            {{-- ACCIONES --}}
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">

                                    {{-- EDITAR --}}
                                    @if ($user->email !== 'admin@terrapago.com')
                                        <button type="button" wire:click="editar({{ $user->id }})"
                                            class="group flex items-center gap-1.5 text-blue-600 hover:text-blue-800 font-semibold text-xs bg-blue-50 hover:bg-blue-100 border border-blue-100 px-3 py-1.5 rounded-lg transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5h2m-7 14h14M5 19l4-4 10-10a2.121 2.121 0 0 1 3 3L6 12l-1 7z" />
                                            </svg>
                                            Editar
                                        </button>
                                    @endif

                                    {{-- PERMISOS --}}
                                    <a href="{{ route('admin.usuarios.permisos', $user->id) }}"
                                        class="group flex items-center gap-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-100 font-semibold text-xs px-3 py-1.5 rounded-lg transition-all duration-200 hover:-translate-y-0.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06-1.5 1.5-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V20h-2.12v-.5a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06-1.5-1.5.06-.06A1.65 1.65 0 0 0 9.4 15a1.65 1.65 0 0 0-1.51-1H7.5v-2.12H8a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06 1.5-1.5.06.06a1.65 1.65 0 0 0 1.82.33 1.65 1.65 0 0 0 1-1.51V6h2.12v.5a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06 1.5 1.5-.06.06a1.65 1.65 0 0 0-.33 1.82 1.65 1.65 0 0 0 1.51 1h.5V14h-.5a1.65 1.65 0 0 0-1.51 1z" />
                                        </svg>
                                        Permisos
                                    </a>

                                    {{-- ACTIVAR / DESACTIVAR (PRESERVA HISTORIAL) --}}
                                    @if ($user->id !== auth()->id() && $user->email !== 'admin@terrapago.com')
                                        @if ($user->activo)
                                            <button type="button"
                                                wire:click="confirmarToggleEstado({{ $user->id }})"
                                                class="group flex items-center gap-1.5 text-amber-600 hover:text-amber-800 font-semibold text-xs bg-amber-50 hover:bg-amber-100 border border-amber-200 px-3 py-1.5 rounded-lg transition-all duration-200 hover:-translate-y-0.5 cursor-pointer"
                                                title="Desactivar usuario">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>Desactivar</span>
                                            </button>
                                        @else
                                            <button type="button"
                                                wire:click="confirmarToggleEstado({{ $user->id }})"
                                                class="group flex items-center gap-1.5 text-emerald-600 hover:text-emerald-800 font-semibold text-xs bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-3 py-1.5 rounded-lg transition-all duration-200 hover:-translate-y-0.5 cursor-pointer"
                                                title="Activar usuario">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>Activar</span>
                                            </button>
                                        @endif
                                    @endif

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="p-10 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div
                                        class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6H6a4 4 0 01-4-4v-1a4 4 0 014-4h7a4 4 0 014 4v1a4 4 0 01-4 4zm0-10a4 4 0 100-8 4 4 0 000 8z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-600">No hay usuarios registrados.</p>
                                    <p class="text-xs text-slate-400 mt-1">Los usuarios aparecerán aquí cuando sean
                                        registrados.</p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>



        {{-- =====================================================
             PAGINACIÓN
        ====================================================== --}}
        <div
            class="p-4
                   border-t
                   border-slate-100
                   bg-slate-50/30">

            {{ $usuarios->links() }}

        </div>

    </div>

    {{-- =========================================================
        MODAL MODULARIZADO DE CREAR 
    ========================================================== --}}
    @include('livewire.admin.usuarios.create')

    {{-- =========================================================
            MODAL MODULARIZADO DE ACTIVAR / DESACTIVAR  
        ========================================================== --}}
    @include('livewire.admin.usuarios.toggle-status')

    {{-- =========================================================
        MODAL MODULARIZADO DE EDITAR
    ========================================================== --}}
    @include('livewire.admin.usuarios.edit')

</div>
