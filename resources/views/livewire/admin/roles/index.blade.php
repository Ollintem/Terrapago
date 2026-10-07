<div class="p-6 max-w-7xl mx-auto space-y-6">

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

                Catálogo de Puestos y

                <span class="text-emerald-600">
                    Roles
                </span>

            </h1>


            {{-- DESCRIPCIÓN --}}
            <p class="mt-2 text-sm sm:text-base font-medium text-slate-500 max-w-xl">

                Administra los niveles de acceso y cargos para el personal de

                <span class="font-semibold text-slate-700">
                    TerraPago
                </span>.

            </p>

        </div>


        {{-- NUEVO PUESTO --}}
        <div class="flex items-center gap-3">

            @if (
                (auth()->user()->rol &&
                    in_array(strtolower(auth()->user()->rol->nombre), ['administrador', 'super admin', 'superadministrador'])) ||
                    auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'roles'))->where('crear', true)->exists())
                <button wire:click="abrirModalCrear"
                    class="group flex items-center gap-2
                           bg-emerald-600 hover:bg-emerald-700
                           text-white px-4 py-2.5
                           rounded-xl
                           font-semibold text-sm
                           shadow-lg shadow-emerald-600/20
                           hover:shadow-emerald-600/30
                           hover:-translate-y-0.5
                           transition-all duration-200
                           cursor-pointer">

                    <span class="text-lg leading-none transition-transform duration-200 group-hover:rotate-90">
                        +
                    </span>

                    Nuevo Puesto

                </button>
            @endif

        </div>

    </div>


    {{-- =========================================================
        MENSAJES CON AUTODESAPARICIÓN (5 SEGUNDOS)
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
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">


        {{-- =====================================================
             BUSCADOR
        ====================================================== --}}
        <div class="p-5 border-b border-slate-100 bg-slate-50/40">

            <div class="relative w-full max-w-md">

                {{-- ICONO DE BÚSQUEDA --}}
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">

                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />

                    </svg>

                </div>


                {{-- INPUT --}}
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar puesto o rol..."
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

                    <tr
                        class="bg-slate-50 text-slate-500 text-[11px]
                               uppercase tracking-[0.12em] font-bold">

                        <th class="p-4">
                            Puesto / Rol
                        </th>

                        <th class="p-4 text-center">
                            Usuarios Asignados
                        </th>

                        <th class="p-4 text-center">
                            Acciones
                        </th>

                    </tr>

                </thead>


                {{-- CUERPO --}}
                <tbody class="divide-y divide-slate-100 text-sm">


                    @forelse ($roles as $rol)

                        <tr class="hover:bg-slate-50/60 transition duration-150">


                            {{-- =================================================
                                 NOMBRE DEL ROL
                            ================================================== --}}
                            <td class="p-4">

                                <div class="flex items-center gap-3">

                                    {{-- Indicador --}}
                                    <div
                                        class="h-9 w-9 rounded-xl
                                                bg-emerald-50
                                                border border-emerald-100
                                                text-emerald-600
                                                flex items-center justify-center
                                                text-xs font-bold
                                                flex-shrink-0">

                                        {{ strtoupper(substr($rol->nombre ?? 'R', 0, 1)) }}

                                    </div>


                                    <div>

                                        <p class="font-semibold text-slate-900 tracking-tight">

                                            {{ $rol->nombre }}

                                        </p>

                                        @if ($rol->descripcion)
                                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">

                                                {{ $rol->descripcion }}

                                            </p>
                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                 USUARIOS ASIGNADOS
                            ================================================== --}}
                            <td class="p-4 text-center">

                                <span
                                    class="inline-flex items-center gap-1.5
                                           px-3 py-1.5
                                           text-xs font-semibold
                                           rounded-full

                                           {{ $rol->users_count > 0
                                               ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                               : 'bg-slate-100 text-slate-500 border border-slate-200' }}">

                                    <span
                                        class="w-1.5 h-1.5 rounded-full
                                        {{ $rol->users_count > 0 ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>

                                    {{ $rol->users_count }}

                                </span>

                            </td>


                            {{-- =================================================
                                 ACCIONES
                            ================================================== --}}
                            <td class="p-4">

                                <div class="flex items-center justify-center gap-2">


                                    {{-- EDITAR --}}
                                    @if (
                                        (auth()->user()->rol &&
                                            in_array(strtolower(auth()->user()->rol->nombre), ['administrador', 'super admin', 'superadministrador'])) ||
                                            auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'roles'))->where('editar', true)->exists())
                                        <button wire:click="abrirModalEditar({{ $rol->id }})"
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

                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5h2m-7 14h14M5 19l4-4 10-10a2.121 2.121 0 0 1 3 3L6 12l-1 7z" />

                                            </svg>

                                            Editar

                                        </button>
                                    @endif


                                    {{-- ELIMINAR --}}
                                    @if (
                                        (auth()->user()->rol &&
                                            in_array(strtolower(auth()->user()->rol->nombre), ['administrador', 'super admin', 'superadministrador'])) ||
                                            auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'roles'))->where('eliminar', true)->exists())
                                        @if (!in_array(strtolower($rol->nombre), ['administrador', 'super admin', 'superadministrador']))
                                            <button type="button" wire:click="confirmarEliminar({{ $rol->id }})"
                                                class="group flex items-center gap-1.5
                                                      text-rose-600 hover:text-rose-800
                                                      font-semibold text-xs
                                                      bg-rose-50 hover:bg-rose-100
                                                      border border-rose-100
                                                      px-3.5 py-2
                                                      rounded-lg
                                                      transition-all duration-200
                                                      hover:-translate-y-0.5
                                                      cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M6 7h12m-9 0V5h6v2m-7 0v12a2 2 0 002 2h4a2 2 0 002-2V7M10 11v6m4-6v6" />
                                                </svg>
                                                Eliminar
                                            </button>
                                        @endif
                                    @endif

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="3" class="p-10 text-center">

                                <div class="flex flex-col items-center justify-center">

                                    <div
                                        class="w-12 h-12 rounded-full
                                               bg-slate-100
                                               flex items-center justify-center
                                               mb-3">

                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />

                                        </svg>

                                    </div>

                                    <p class="text-sm font-semibold text-slate-600">

                                        No se encontraron puestos registrados.

                                    </p>

                                    <p class="text-xs text-slate-400 mt-1">

                                        Los puestos aparecerán aquí cuando sean registrados.

                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- =========================================================
        INCLUSIÓN DEL MODAL DE ROL Y PERMISOS
    ========================================================== --}}
    @include('livewire.admin.roles.create')

    {{-- =========================================================
        INCLUSIÓN DEL MODAL DE ELIMINAR ROL
    ========================================================== --}}
    @include('livewire.admin.roles.delete')

    {{-- =========================================================
        INCLUSIÓN DEL MODAL DE EDITAR ROL
    ========================================================== --}}
    @include('livewire.admin.roles.edit')


    
    
    

</div>




</div>
