<div class="p-6 max-w-7xl mx-auto">

    {{-- =========================================================
         ENCABEZADO PRINCIPAL
    ========================================================== --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        {{-- TÍTULO Y DESCRIPCIÓN --}}
        <div>

            {{-- Indicador de sección --}}
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

                y Personal

            </h1>


            {{-- DESCRIPCIÓN --}}
            <p class="mt-2 text-sm sm:text-base font-medium text-slate-500 max-w-xl">

                Administra los usuarios, roles y permisos de

                <span class="font-semibold text-slate-700">
                    TerraPago
                </span>.

            </p>

        </div>


        {{-- ESTADO DEL SISTEMA + BOTÓN --}}
        <div class="flex items-center gap-3">

            {{-- ESTADO --}}
            <div class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-xl bg-white border border-slate-200 shadow-sm">

                <span class="relative flex h-2.5 w-2.5">

                    <span class="absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75 animate-ping"></span>

                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>

                </span>

                <span class="text-xs font-semibold text-slate-600">
                    Sistema activo
                </span>

            </div>


            {{-- NUEVO USUARIO --}}
            @if(auth()->user()->rol && in_array(strtolower(auth()->user()->rol->nombre), ['administrador', 'super admin', 'superadministrador']) 
                || auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'usuarios'))->where('crear', true)->exists())

                <button
                    wire:click="abrirModal"
                    class="group flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700
                           text-white px-4 py-2.5 rounded-xl
                           font-semibold text-sm
                           shadow-lg shadow-emerald-600/20
                           hover:shadow-emerald-600/30
                           hover:-translate-y-0.5
                           transition-all duration-200
                           cursor-pointer">

                    <span class="text-lg leading-none transition-transform duration-200 group-hover:rotate-90">
                        +
                    </span>

                    Nuevo Usuario

                </button>

            @endif

        </div>

    </div>


    {{-- =========================================================
         NOTIFICACIONES
    ========================================================== --}}
    @if (session()->has('mensaje'))

        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-4 rounded-r-lg">

            <p class="text-sm font-semibold">
                {{ session('mensaje') }}
            </p>

        </div>

    @endif


    @if (session()->has('message'))

        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 mb-4 rounded-r-lg">

            <p class="text-sm font-semibold">
                {{ session('message') }}
            </p>

        </div>

    @endif


    {{-- =========================================================
         TABLA PRINCIPAL
    ========================================================== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">


        {{-- =====================================================
             BUSCADOR
        ====================================================== --}}
        <div class="p-5 border-b border-slate-100 bg-slate-50/40">

            <div class="relative w-full max-w-md">

                {{-- ICONO DE BÚSQUEDA --}}
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">

                    <svg
                        class="w-5 h-5 text-slate-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"/>

                    </svg>

                </div>


                {{-- INPUT --}}
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Buscar por nombre o correo..."

                    class="w-full pl-12 pr-4 py-3
                           bg-white
                           border border-slate-200
                           rounded-xl
                           text-sm font-medium text-slate-700
                           placeholder:text-slate-400
                           shadow-sm
                           focus:outline-none
                           focus:ring-2 focus:ring-emerald-500/30
                           focus:border-emerald-500
                           transition duration-200">

            </div>

        </div>


        {{-- =====================================================
             TABLA
        ====================================================== --}}
        <div class="overflow-x-auto">

            <table class="w-full text-left border-collapse">


                {{-- ENCABEZADOS --}}
                <thead>

                    <tr class="bg-slate-50 text-slate-500 text-[11px] uppercase tracking-[0.12em] font-bold">

                        <th class="p-4">
                            Nombre
                        </th>

                        <th class="p-4">
                            Correo
                        </th>

                        <th class="p-4 text-center">
                            Rol Asignado
                        </th>

                        <th class="p-4 text-center">
                            Estado
                        </th>

                        <th class="p-4 text-center">
                            Acciones
                        </th>

                    </tr>

                </thead>


                {{-- CUERPO DE LA TABLA --}}
                <tbody class="divide-y divide-slate-100 text-sm">


                    @forelse($usuarios as $user)


                        <tr class="hover:bg-slate-50/60 transition duration-150">


                            {{-- =================================================
                                 NOMBRE
                            ================================================== --}}
                            <td class="p-4">

                                <div class="flex items-center gap-3">

                                    {{-- Avatar --}}
                                    <div class="h-9 w-9 rounded-full bg-emerald-50 border border-emerald-100
                                                text-emerald-600 flex items-center justify-center
                                                text-xs font-bold uppercase flex-shrink-0">

                                        {{ substr($user->nombre ?? 'U', 0, 1) }}

                                    </div>


                                    <div>

                                        <p class="font-semibold text-slate-900 tracking-tight">
                                            {{ $user->nombre }}
                                        </p>

                                        <p class="text-[11px] text-slate-400 font-medium">
                                            Usuario del sistema
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                 CORREO
                            ================================================== --}}
                            <td class="p-4">

                                <span class="text-slate-500 font-medium">
                                    {{ $user->email }}
                                </span>

                            </td>


                            {{-- =================================================
                                 ROL
                            ================================================== --}}
                            <td class="p-4 text-center">

                                @if(in_array(strtolower($user->rol->nombre ?? ''), ['administrador', 'super admin', 'superadministrador']))

                                    <span class="inline-flex items-center gap-1.5
                                                 px-3 py-1.5
                                                 text-xs font-semibold tracking-tight
                                                 rounded-full
                                                 bg-emerald-100 text-emerald-800
                                                 border border-emerald-200">

                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>

                                        {{ $user->rol->nombre }}

                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1.5
                                                 px-3 py-1.5
                                                 text-xs font-semibold tracking-tight
                                                 rounded-full
                                                 bg-slate-100 text-slate-700
                                                 border border-slate-200">

                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>

                                        {{ $user->rol->nombre ?? 'Sin Rol' }}

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 ESTADO
                            ================================================== --}}
                            <td class="p-4 text-center">

                                <span class="inline-flex items-center gap-1.5
                                             px-3 py-1.5
                                             text-xs font-semibold tracking-tight
                                             rounded-full
                                             {{ $user->estado
                                                ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                                : 'bg-rose-50 text-rose-700 border border-rose-200' }}">

                                    <span class="w-1.5 h-1.5 rounded-full
                                        {{ $user->estado
                                            ? 'bg-emerald-500'
                                            : 'bg-rose-500' }}">
                                    </span>

                                    {{ $user->estado ? 'Activo' : 'Inactivo' }}

                                </span>

                            </td>


                            {{-- =================================================
                                 ACCIONES
                            ================================================== --}}
                            <td class="p-4">

                                <div class="flex items-center justify-center gap-2">


                                    {{-- EDITAR Y PERMISOS --}}
                                    @if(auth()->user()->rol && in_array(strtolower(auth()->user()->rol->nombre), ['administrador', 'super admin', 'superadministrador'])
                                        || auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'usuarios'))->where('editar', true)->exists())


                                        {{-- EDITAR --}}
                                        <button
                                            wire:click="editar({{ $user->id }})"
                                            class="group flex items-center gap-1.5
                                                   text-blue-600 hover:text-blue-800
                                                   font-semibold text-xs
                                                   bg-blue-50 hover:bg-blue-100
                                                   border border-blue-100
                                                   px-3.5 py-2
                                                   rounded-lg
                                                   transition-all duration-200
                                                   hover:-translate-y-0.5
                                                   cursor-pointer">

                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5h2m-7 14h14M5 19l4-4 10-10a2.121 2.121 0 0 0-3-3L6 12l-1 7z"/>

                                            </svg>

                                            Editar

                                        </button>


                                        {{-- PERMISOS --}}
                                        <a
                                            href="{{ route('admin.usuarios.permisos', $user->id) }}"
                                            class="group flex items-center gap-1.5
                                                   bg-emerald-50 hover:bg-emerald-100
                                                   text-emerald-700
                                                   border border-emerald-100
                                                   font-semibold text-xs
                                                   px-3.5 py-2
                                                   rounded-lg
                                                   transition-all duration-200
                                                   hover:-translate-y-0.5
                                                   cursor-pointer">

                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06-1.5 1.5-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V20h-2.12v-.5a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06-1.5-1.5.06-.06A1.65 1.65 0 0 0 9.4 15a1.65 1.65 0 0 0-1.51-1H7.5v-2.12H8a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06 1.5-1.5.06.06a1.65 1.65 0 0 0 1.82.33 1.65 1.65 0 0 0 1-1.51V6h2.12v.5a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06 1.5 1.5-.06.06a1.65 1.65 0 0 0-.33 1.82 1.65 1.65 0 0 0 1.51 1h.5V14h-.5a1.65 1.65 0 0 0-1.51 1z"/>

                                            </svg>

                                            Permisos

                                        </a>

                                    @endif


                                    {{-- ELIMINAR --}}
                                    @if(auth()->user()->rol && in_array(strtolower(auth()->user()->rol->nombre), ['administrador', 'super admin', 'superadministrador'])
                                        || auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'usuarios'))->where('eliminar', true)->exists())


                                        @if($user->id !== auth()->id() && $user->email !== 'admin@terrapago.com')

                                            <button
                                                wire:click="eliminar({{ $user->id }})"
                                                wire:confirm="¿Estás seguro de que deseas eliminar permanentemente a {{ $user->nombre }}?"

                                                class="group flex items-center justify-center
                                                       text-rose-600 hover:text-rose-800
                                                       font-semibold text-xs
                                                       bg-rose-50 hover:bg-rose-100
                                                       border border-rose-100
                                                       px-3 py-2
                                                       rounded-lg
                                                       transition-all duration-200
                                                       hover:-translate-y-0.5
                                                       cursor-pointer">

                                                <svg
                                                    class="w-4 h-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M6 7h12m-9 0V5h6v2m-7 0v12a2 2 0 002 2h4a2 2 0 002-2V7M10 11v6m4-6v6"/>

                                                </svg>

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

                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mb-3">

                                        <svg
                                            class="w-6 h-6 text-slate-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6H6a4 4 0 01-4-4v-1a4 4 0 014-4h7a4 4 0 014 4v1a4 4 0 01-4 4zm0-10a4 4 0 100-8 4 4 0 000 8z"/>

                                        </svg>

                                    </div>

                                    <p class="text-sm font-semibold text-slate-600">
                                        No hay usuarios registrados.
                                    </p>

                                    <p class="text-xs text-slate-400 mt-1">
                                        Los usuarios aparecerán aquí cuando sean registrados.
                                    </p>

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
        <div class="p-4 border-t border-slate-100 bg-slate-50/30">

            {{ $usuarios->links() }}

        </div>

    </div>


    {{-- =========================================================
         MODAL DE CREACIÓN / EDICIÓN
    ========================================================== --}}
    @if($isModalOpen)

        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm
                    flex items-center justify-center
                    z-50 p-4">

            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">


                {{-- TÍTULO --}}
                <div class="mb-5">

                    <div class="flex items-center gap-3">

                        <div class="w-10 h-10 rounded-xl bg-emerald-50
                                    text-emerald-600 flex items-center justify-center">

                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v16m8-8H4"/>

                            </svg>

                        </div>

                        <div>

                            <h3 class="text-lg font-bold tracking-tight text-slate-900">

                                {{ $user_id ? 'Editar Usuario' : 'Registrar Nuevo Usuario' }}

                            </h3>

                            <p class="text-xs text-slate-400 font-medium">
                                Completa la información del usuario.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- CAMPOS --}}
                <div class="space-y-4">


                    {{-- NOMBRE --}}
                    <div>

                        <label
                            class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">

                            Nombre Completo

                        </label>

                        <input
                            wire:model="nombre"
                            type="text"

                            class="w-full px-3 py-2.5
                                   border border-slate-300
                                   rounded-lg
                                   text-sm font-medium
                                   focus:ring-2 focus:ring-emerald-500/30
                                   focus:border-emerald-500
                                   focus:outline-none
                                   transition">

                        @error('nombre')

                            <span class="text-rose-500 text-xs font-medium">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- CORREO --}}
                    <div>

                        <label
                            class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">

                            Correo Electrónico

                        </label>

                        <input
                            wire:model="email"
                            type="email"

                            class="w-full px-3 py-2.5
                                   border border-slate-300
                                   rounded-lg
                                   text-sm font-medium
                                   focus:ring-2 focus:ring-emerald-500/30
                                   focus:border-emerald-500
                                   focus:outline-none
                                   transition">

                        @error('email')

                            <span class="text-rose-500 text-xs font-medium">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- CONTRASEÑA --}}
                    <div>

                        <label
                            class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">

                            Contraseña

                        </label>

                        <input
                            wire:model="password"
                            type="password"
                            placeholder="{{ $user_id ? 'En blanco para no cambiar' : '' }}"

                            class="w-full px-3 py-2.5
                                   border border-slate-300
                                   rounded-lg
                                   text-sm font-medium
                                   focus:ring-2 focus:ring-emerald-500/30
                                   focus:border-emerald-500
                                   focus:outline-none
                                   transition">

                        @error('password')

                            <span class="text-rose-500 text-xs font-medium">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- ROL --}}
                    <div>

                        <label
                            class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">

                            Rol

                        </label>

                        <select
                            wire:model="rol_id"

                            class="w-full px-3 py-2.5
                                   border border-slate-300
                                   rounded-lg
                                   text-sm font-medium
                                   bg-white
                                   focus:ring-2 focus:ring-emerald-500/30
                                   focus:border-emerald-500
                                   focus:outline-none
                                   transition">

                            <option value="">
                                Selecciona rol...
                            </option>

                            @foreach($roles as $rol)

                                <option value="{{ $rol->id }}">
                                    {{ $rol->nombre }}
                                </option>

                            @endforeach

                        </select>

                        @error('rol_id')

                            <span class="text-rose-500 text-xs font-medium">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>

                </div>


                {{-- BOTONES --}}
                <div class="mt-6 flex justify-end gap-3">

                    <button
                        wire:click="cerrarModal"

                        class="px-4 py-2.5
                               border border-slate-300
                               text-slate-600
                               rounded-lg
                               text-sm font-semibold
                               hover:bg-slate-50
                               transition
                               cursor-pointer">

                        Cancelar

                    </button>


                    <button
                        wire:click="guardar"

                        class="px-4 py-2.5
                               bg-emerald-600
                               text-white
                               rounded-lg
                               text-sm font-semibold
                               hover:bg-emerald-700
                               shadow-sm shadow-emerald-600/20
                               transition
                               cursor-pointer">

                        Guardar

                    </button>

                </div>

            </div>

        </div>

    @endif

</div>