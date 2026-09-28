<div class="p-6 max-w-7xl mx-auto space-y-6">

    {{-- =========================================================
        ENCABEZADO
    ========================================================== --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        <div>

            {{-- Categoría --}}
            <div class="flex items-center gap-2 mb-2">

                <span
                    class="h-2 w-2 rounded-full
                           bg-emerald-500
                           shadow-sm shadow-emerald-500/50">
                </span>

                <span
                    class="text-[11px] font-bold uppercase
                           tracking-[0.16em]
                           text-emerald-600">
                    Inventario
                </span>

            </div>


            {{-- Título --}}
            <h1
                class="text-3xl sm:text-4xl
                       font-extrabold
                       tracking-tight
                       text-slate-900">

                Inventario de
                <span class="text-emerald-600">
                    Terrenos
                </span>

            </h1>


            {{-- Descripción --}}
            <p
                class="mt-2
                       text-sm sm:text-base
                       font-medium
                       text-slate-500
                       max-w-xl">

                Consulta y administra la disponibilidad e información de los
                <span class="font-semibold text-slate-700">
                    terrenos
                </span>
                de
                <span class="font-semibold text-slate-700">
                    TerraPago
                </span>.

            </p>

        </div>


        {{-- Botón Agregar Lote --}}
        @if(
            (auth()->user()->rol &&
            in_array(
                strtolower(auth()->user()->rol->nombre),
                ['administrador', 'super admin', 'superadministrador']
            ))
            ||
            auth()->user()->permisos()
                ->whereHas('modulo', fn($q) => $q->where('clave', 'terrenos'))
                ->where('crear', true)
                ->exists()
        )

            <button
                wire:click="abrirModalCrear"
                class="inline-flex items-center gap-2
                       px-5 py-2.5
                       bg-emerald-600
                       hover:bg-emerald-700
                       text-white
                       text-sm font-semibold
                       rounded-xl
                       shadow-md
                       shadow-emerald-600/20
                       transition-all duration-200
                       hover:-translate-y-0.5
                       cursor-pointer">

                <span class="text-base leading-none">
                    +
                </span>

                <span>
                    Agregar Lote
                </span>

            </button>

        @endif

    </div>


    {{-- =========================================================
        MENSAJES CON AUTODESAPARICIÓN (5 SEGUNDOS)
    ========================================================== --}}

    @if (session()->has('mensaje'))
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 5000)" 
            x-show="show" 
            x-transition:leave="transition ease-in duration-500" 
            x-transition:leave-start="opacity-100 transform scale-100" 
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            class="flex items-center gap-3 p-4 rounded-xl
                   bg-emerald-50
                   border border-emerald-200
                   text-emerald-700
                   text-sm font-medium">

            <div class="flex items-center justify-center
                        w-8 h-8 rounded-full
                        bg-emerald-100
                        text-emerald-600
                        font-bold">
                ✓
            </div>

            <span class="flex-1">
                {{ session('mensaje') }}
            </span>

            {{-- Botón para cerrar manualmente si no quieren esperar --}}
            <button type="button" @click="show = false" class="text-emerald-500 hover:text-emerald-700 text-sm font-bold cursor-pointer">
                ✕
            </button>
        </div>
    @endif

    @if (session()->has('error'))
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 5000)" 
            x-show="show" 
            x-transition:leave="transition ease-in duration-500" 
            x-transition:leave-start="opacity-100 transform scale-100" 
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            class="flex items-center gap-3 p-4 rounded-xl
                   bg-rose-50
                   border border-rose-200
                   text-rose-700
                   text-sm font-medium">

            <div class="flex items-center justify-center
                        w-8 h-8 rounded-full
                        bg-rose-100
                        text-rose-600
                        font-bold">
                !
            </div>

            <span class="flex-1">
                {{ session('error') }}
            </span>

            <button type="button" @click="show = false" class="text-rose-500 hover:text-rose-700 text-sm font-bold cursor-pointer">
                ✕
            </button>
        </div>
    @endif


    {{-- =========================================================
        RESUMEN DEL INVENTARIO
    ========================================================== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Disponibles --}}
        <div
            class="bg-white
                   border border-emerald-200
                   rounded-xl
                   p-4
                   shadow-sm
                   hover:shadow-md
                   transition">

            <div class="flex items-center justify-between">

                <div>

                    <p
                        class="text-xs font-bold uppercase
                               tracking-wide text-slate-400">
                        Disponibles
                    </p>

                    <p
                        class="text-2xl font-extrabold
                               text-emerald-600 mt-1">
                        {{ $totalDisponibles }}
                    </p>

                </div>

                <div
                    class="w-10 h-10 rounded-xl
                           bg-emerald-50
                           flex items-center justify-center
                           text-emerald-600
                           font-bold">

                    ✓

                </div>

            </div>

        </div>


        {{-- Apartados --}}
        <div
            class="bg-white
                   border border-amber-200
                   rounded-xl
                   p-4
                   shadow-sm
                   hover:shadow-md
                   transition">

            <div class="flex items-center justify-between">

                <div>

                    <p
                        class="text-xs font-bold uppercase
                               tracking-wide text-slate-400">
                        Apartados
                    </p>

                    <p
                        class="text-2xl font-extrabold
                               text-amber-500 mt-1">
                        {{ $totalApartados }}
                    </p>

                </div>

                <div
                    class="w-10 h-10 rounded-xl
                           bg-amber-50
                           flex items-center justify-center
                           text-amber-500
                           font-bold">

                    !

                </div>

            </div>

        </div>


        {{-- Vendidos --}}
        <div
            class="bg-white
                   border border-slate-200
                   rounded-xl
                   p-4
                   shadow-sm
                   hover:shadow-md
                   transition">

            <div class="flex items-center justify-between">

                <div>

                    <p
                        class="text-xs font-bold uppercase
                               tracking-wide text-slate-400">
                        Vendidos
                    </p>

                    <p
                        class="text-2xl font-extrabold
                               text-slate-600 mt-1">
                        {{ $totalVendidos }}
                    </p>

                </div>

                <div
                    class="w-10 h-10 rounded-xl
                           bg-slate-100
                           flex items-center justify-center
                           text-slate-500
                           font-bold">

                    ✓

                </div>

            </div>

        </div>


        {{-- Total --}}
        <div
            class="bg-white
                   border border-slate-200
                   rounded-xl
                   p-4
                   shadow-sm
                   hover:shadow-md
                   transition">

            <div class="flex items-center justify-between">

                <div>

                    <p
                        class="text-xs font-bold uppercase
                               tracking-wide text-slate-400">
                        Total de lotes
                    </p>

                    <p
                        class="text-2xl font-extrabold
                               text-slate-900 mt-1">
                        {{ $totalTerrenos }}
                    </p>

                </div>

                <div
                    class="w-10 h-10 rounded-xl
                           bg-slate-100
                           flex items-center justify-center
                           text-slate-600
                           font-bold">

                    #

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FILTROS
    ========================================================== --}}
    <div
        class="bg-white
               rounded-2xl
               border border-slate-200
               shadow-sm
               p-4">

        <div
            class="flex flex-col lg:flex-row
                   lg:items-center
                   lg:justify-between
                   gap-4">

            {{-- Buscador --}}
            <div class="relative w-full lg:max-w-md">

                <div
                    class="absolute inset-y-0 left-0 pl-4
                           flex items-center
                           pointer-events-none">

                    <svg
                        class="w-5 h-5 text-slate-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m21 21-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"
                        />

                    </svg>

                </div>

                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Buscar por manzana, lote o ubicación..."
                    class="w-full
                           pl-11 pr-4 py-3
                           border border-slate-200
                           rounded-xl
                           bg-slate-50
                           text-sm text-slate-700
                           placeholder:text-slate-400
                           focus:outline-none
                           focus:ring-2 focus:ring-emerald-500/30
                           focus:border-emerald-500
                           transition">

            </div>


            {{-- Filtros de estado --}}
            <div class="flex flex-wrap items-center gap-2">

                <button
                    type="button"
                    wire:click="$set('filtroEstado', '')"
                    class="px-4 py-2 rounded-lg
                           text-xs font-bold
                           border transition
                           cursor-pointer
                           {{ $filtroEstado === ''
                                ? 'bg-slate-900 text-white border-slate-900'
                                : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">

                    Todos

                </button>


                <button
                    type="button"
                    wire:click="$set('filtroEstado', 'DISPONIBLE')"
                    class="px-4 py-2 rounded-lg
                           text-xs font-bold
                           border transition
                           cursor-pointer
                           {{ $filtroEstado === 'DISPONIBLE'
                                ? 'bg-emerald-600 text-white border-emerald-600'
                                : 'bg-white text-emerald-700 border-emerald-200 hover:bg-emerald-50' }}">

                    Disponibles

                </button>


                <button
                    type="button"
                    wire:click="$set('filtroEstado', 'APARTADO')"
                    class="px-4 py-2 rounded-lg
                           text-xs font-bold
                           border transition
                           cursor-pointer
                           {{ $filtroEstado === 'APARTADO'
                                ? 'bg-amber-500 text-white border-amber-500'
                                : 'bg-white text-amber-700 border-amber-200 hover:bg-amber-50' }}">

                    Apartados

                </button>


                <button
                    type="button"
                    wire:click="$set('filtroEstado', 'VENDIDO')"
                    class="px-4 py-2 rounded-lg
                           text-xs font-bold
                           border transition
                           cursor-pointer
                           {{ $filtroEstado === 'VENDIDO'
                                ? 'bg-slate-600 text-white border-slate-600'
                                : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">

                    Vendidos

                </button>

            </div>

        </div>

    </div>

    {{-- =========================================================
        LLAMADO A LOS ARCHIVOS MODULARES
    ========================================================== --}}

    {{-- Catálogo visual de tarjetas/lotes --}}
    @include('livewire.admin.terrenos.catalogo')

    {{-- Modal de Crear / Editar --}}
    @include('livewire.admin.terrenos.create')

</div>