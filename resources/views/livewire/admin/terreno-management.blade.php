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
        MENSAJES
    ========================================================== --}}

    @if (session()->has('mensaje'))

        <div
            class="flex items-center gap-3 p-4 rounded-xl
                   bg-emerald-50
                   border border-emerald-200
                   text-emerald-700
                   text-sm font-medium">

            <div
                class="flex items-center justify-center
                       w-8 h-8 rounded-full
                       bg-emerald-100
                       text-emerald-600
                       font-bold">

                ✓

            </div>

            <span>
                {{ session('mensaje') }}
            </span>

        </div>

    @endif


    @if (session()->has('error'))

        <div
            class="flex items-center gap-3 p-4 rounded-xl
                   bg-rose-50
                   border border-rose-200
                   text-rose-700
                   text-sm font-medium">

            <div
                class="flex items-center justify-center
                       w-8 h-8 rounded-full
                       bg-rose-100
                       text-rose-600
                       font-bold">

                !

            </div>

            <span>
                {{ session('error') }}
            </span>

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
        CATÁLOGO VISUAL DE TERRENOS
    ========================================================== --}}
    <div>

        <div class="flex items-center justify-between mb-4">

            <div>

                <h2 class="text-lg font-bold text-slate-900">
                    Catálogo de lotes
                </h2>

                <p class="text-xs text-slate-500 mt-1">
                    {{ $terrenos->total() }} lotes encontrados
                </p>

            </div>

        </div>


        @if($terrenos->count() > 0)

            <div
                class="grid grid-cols-1
                       sm:grid-cols-2
                       xl:grid-cols-3
                       gap-5">

                @foreach($terrenos as $terreno)

                    {{-- =================================================
                        TARJETA DEL TERRENO
                    ================================================== --}}
                    <div
                        class="bg-white
                               rounded-2xl
                               border border-slate-200
                               shadow-sm
                               hover:shadow-xl
                               hover:-translate-y-1
                               transition-all duration-200
                               overflow-hidden">

                        {{-- Área visual superior --}}
                        @if($terreno->estado === 'DISPONIBLE')

                            <div
                                class="relative h-36
                                       bg-emerald-50
                                       flex items-center justify-center">

                        @elseif($terreno->estado === 'APARTADO')

                            <div
                                class="relative h-36
                                       bg-amber-50
                                       flex items-center justify-center">

                        @else

                            <div
                                class="relative h-36
                                       bg-slate-100
                                       flex items-center justify-center">

                        @endif


                            {{-- Etiqueta de estado --}}
                            @if($terreno->estado === 'DISPONIBLE')

                                <span
                                    class="absolute top-3 right-3
                                           px-2.5 py-1
                                           rounded-full
                                           text-[10px] font-bold
                                           bg-white
                                           text-emerald-600
                                           border border-emerald-200">

                                    Disponible

                                </span>

                            @elseif($terreno->estado === 'APARTADO')

                                <span
                                    class="absolute top-3 right-3
                                           px-2.5 py-1
                                           rounded-full
                                           text-[10px] font-bold
                                           bg-white
                                           text-amber-600
                                           border border-amber-200">

                                    Apartado

                                </span>

                            @else

                                <span
                                    class="absolute top-3 right-3
                                           px-2.5 py-1
                                           rounded-full
                                           text-[10px] font-bold
                                           bg-white
                                           text-slate-500
                                           border border-slate-200">

                                    Vendido

                                </span>

                            @endif


                            {{-- Representación visual del lote --}}
                            @if($terreno->estado === 'DISPONIBLE')

                                <div
                                    class="relative w-20 h-12
                                           border-2 border-emerald-500
                                           rounded-sm
                                           flex items-center justify-center">

                                    <span
                                        class="text-[9px] font-bold
                                               text-emerald-600">

                                        {{ $terreno->medidas }}

                                    </span>

                                </div>

                            @elseif($terreno->estado === 'APARTADO')

                                <div
                                    class="relative w-20 h-12
                                           border-2 border-amber-400
                                           rounded-sm
                                           flex items-center justify-center">

                                    <span
                                        class="text-[9px] font-bold
                                               text-amber-600">

                                        {{ $terreno->medidas }}

                                    </span>

                                </div>

                            @else

                                <div
                                    class="relative w-20 h-12
                                           border-2 border-slate-300
                                           rounded-sm
                                           flex items-center justify-center">

                                    <span
                                        class="text-[9px] font-bold
                                               text-slate-400">

                                        {{ $terreno->medidas }}

                                    </span>

                                </div>

                            @endif

                        </div>


                        {{-- Información --}}
                        <div class="p-5">

                            <div
                                class="flex items-start
                                       justify-between
                                       gap-3">

                                <div>

                                    <p
                                        class="text-xs
                                               text-slate-400
                                               font-medium">

                                        Mza. {{ $terreno->manzana }}

                                    </p>

                                    <h3
                                        class="text-lg
                                               font-extrabold
                                               text-slate-900">

                                        L-{{ $terreno->lote }}

                                    </h3>

                                </div>

                                <div class="text-right">

                                    <p class="text-xs text-slate-400">
                                        Superficie
                                    </p>

                                    <p
                                        class="text-sm
                                               font-bold
                                               text-slate-600">

                                        {{ number_format($terreno->superficie, 2) }} m²

                                    </p>

                                </div>

                            </div>


                            {{-- Precio --}}
                            <div class="mt-4">

                                <p
                                    class="text-2xl
                                           font-extrabold
                                           text-slate-900">

                                    ${{ number_format($terreno->precio, 0) }}

                                </p>

                                @if($terreno->ubicacion)

                                    <p
                                        class="text-xs
                                               text-slate-400
                                               mt-1
                                               truncate">

                                        {{ $terreno->ubicacion }}

                                    </p>

                                @else

                                    <p
                                        class="text-xs
                                               text-slate-400
                                               mt-1">

                                        Sin referencia de ubicación

                                    </p>

                                @endif

                            </div>


                            {{-- Acciones --}}
                            <div class="mt-5">

                                @if($terreno->estado === 'DISPONIBLE')

                                    <div class="flex gap-2">

                                        {{-- Editar --}}
                                        @if(
                                            (auth()->user()->rol &&
                                            in_array(
                                                strtolower(auth()->user()->rol->nombre),
                                                ['administrador', 'super admin', 'superadministrador']
                                            ))
                                            ||
                                            auth()->user()->permisos()
                                                ->whereHas('modulo', fn($q) => $q->where('clave', 'terrenos'))
                                                ->where('editar', true)
                                                ->exists()
                                        )

                                            <button
                                                type="button"
                                                wire:click="abrirModalEditar({{ $terreno->id }})"
                                                class="flex-1
                                                       py-2.5
                                                       rounded-lg
                                                       bg-emerald-600
                                                       hover:bg-emerald-700
                                                       text-white
                                                       text-xs font-bold
                                                       transition
                                                       cursor-pointer">

                                                Editar lote

                                            </button>

                                        @endif


                                        {{-- Eliminar --}}
                                        @if(
                                            (auth()->user()->rol &&
                                            in_array(
                                                strtolower(auth()->user()->rol->nombre),
                                                ['administrador', 'super admin', 'superadministrador']
                                            ))
                                            ||
                                            auth()->user()->permisos()
                                                ->whereHas('modulo', fn($q) => $q->where('clave', 'terrenos'))
                                                ->where('eliminar', true)
                                                ->exists()
                                        )

                                            <button
                                                type="button"
                                                wire:click="eliminar({{ $terreno->id }})"
                                                wire:confirm="¿Está seguro de que desea eliminar el Lote {{ $terreno->lote }} de la Manzana {{ $terreno->manzana }}?"
                                                class="px-3 py-2.5
                                                       rounded-lg
                                                       bg-rose-50
                                                       hover:bg-rose-100
                                                       text-rose-600
                                                       text-xs font-bold
                                                       transition
                                                       cursor-pointer">

                                                Eliminar

                                            </button>

                                        @endif

                                    </div>


                                @elseif($terreno->estado === 'APARTADO')

                                    <div class="flex gap-2">

                                        {{-- Editar --}}
                                        @if(
                                            (auth()->user()->rol &&
                                            in_array(
                                                strtolower(auth()->user()->rol->nombre),
                                                ['administrador', 'super admin', 'superadministrador']
                                            ))
                                            ||
                                            auth()->user()->permisos()
                                                ->whereHas('modulo', fn($q) => $q->where('clave', 'terrenos'))
                                                ->where('editar', true)
                                                ->exists()
                                        )

                                            <button
                                                type="button"
                                                wire:click="abrirModalEditar({{ $terreno->id }})"
                                                class="flex-1
                                                       py-2.5
                                                       rounded-lg
                                                       bg-amber-100
                                                       hover:bg-amber-200
                                                       text-amber-700
                                                       text-xs font-bold
                                                       transition
                                                       cursor-pointer">

                                                Ver / Editar

                                            </button>

                                        @endif


                                        {{-- Eliminar --}}
                                        @if(
                                            (auth()->user()->rol &&
                                            in_array(
                                                strtolower(auth()->user()->rol->nombre),
                                                ['administrador', 'super admin', 'superadministrador']
                                            ))
                                            ||
                                            auth()->user()->permisos()
                                                ->whereHas('modulo', fn($q) => $q->where('clave', 'terrenos'))
                                                ->where('eliminar', true)
                                                ->exists()
                                        )

                                            <button
                                                type="button"
                                                wire:click="eliminar({{ $terreno->id }})"
                                                wire:confirm="¿Está seguro de que desea eliminar el Lote {{ $terreno->lote }} de la Manzana {{ $terreno->manzana }}?"
                                                class="px-3 py-2.5
                                                       rounded-lg
                                                       bg-rose-50
                                                       hover:bg-rose-100
                                                       text-rose-600
                                                       text-xs font-bold
                                                       transition
                                                       cursor-pointer">

                                                Eliminar

                                            </button>

                                        @endif

                                    </div>


                                @else

                                    <div class="flex gap-2">

                                        {{-- Editar --}}
                                        @if(
                                            (auth()->user()->rol &&
                                            in_array(
                                                strtolower(auth()->user()->rol->nombre),
                                                ['administrador', 'super admin', 'superadministrador']
                                            ))
                                            ||
                                            auth()->user()->permisos()
                                                ->whereHas('modulo', fn($q) => $q->where('clave', 'terrenos'))
                                                ->where('editar', true)
                                                ->exists()
                                        )

                                            <button
                                                type="button"
                                                wire:click="abrirModalEditar({{ $terreno->id }})"
                                                class="flex-1
                                                       py-2.5
                                                       rounded-lg
                                                       bg-slate-100
                                                       hover:bg-slate-200
                                                       text-slate-600
                                                       text-xs font-bold
                                                       transition
                                                       cursor-pointer">

                                                Ver / Editar

                                            </button>

                                        @endif


                                        {{-- Eliminar --}}
                                        @if(
                                            (auth()->user()->rol &&
                                            in_array(
                                                strtolower(auth()->user()->rol->nombre),
                                                ['administrador', 'super admin', 'superadministrador']
                                            ))
                                            ||
                                            auth()->user()->permisos()
                                                ->whereHas('modulo', fn($q) => $q->where('clave', 'terrenos'))
                                                ->where('eliminar', true)
                                                ->exists()
                                        )

                                            <button
                                                type="button"
                                                wire:click="eliminar({{ $terreno->id }})"
                                                wire:confirm="¿Está seguro de que desea eliminar el Lote {{ $terreno->lote }} de la Manzana {{ $terreno->manzana }}?"
                                                class="px-3 py-2.5
                                                       rounded-lg
                                                       bg-rose-50
                                                       hover:bg-rose-100
                                                       text-rose-600
                                                       text-xs font-bold
                                                       transition
                                                       cursor-pointer">

                                                Eliminar

                                            </button>

                                        @endif

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>


            {{-- Paginación --}}
            <div class="mt-6">
                {{ $terrenos->links() }}
            </div>


        @else

            {{-- Sin resultados --}}
            <div
                class="bg-white
                       rounded-2xl
                       border border-slate-200
                       p-12
                       text-center">

                <div
                    class="w-16 h-16
                           mx-auto
                           rounded-2xl
                           bg-slate-100
                           flex items-center justify-center
                           text-slate-400">

                    <svg
                        class="w-8 h-8"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-5h6v5"
                        />

                    </svg>

                </div>

                <h3
                    class="mt-4
                           text-lg
                           font-bold
                           text-slate-800">

                    No se encontraron terrenos

                </h3>

                <p
                    class="mt-1
                           text-sm
                           text-slate-500">

                    No hay lotes que coincidan con los filtros seleccionados.

                </p>

            </div>

        @endif

    </div>


    {{-- =========================================================
        MODAL CREAR / EDITAR
    ========================================================== --}}
    @if($modalAbierto)

        <div
            class="fixed inset-0
                   bg-slate-900/60
                   backdrop-blur-sm
                   flex items-center justify-center
                   p-4
                   z-50">

            <div
                class="bg-white
                       rounded-2xl
                       shadow-2xl
                       w-full
                       max-w-lg
                       max-h-[90vh]
                       overflow-y-auto">

                {{-- Encabezado modal --}}
                <div
                    class="flex items-center justify-between
                           px-6 py-5
                           border-b border-slate-100">

                    <div>

                        <p
                            class="text-xs
                                   font-bold
                                   uppercase
                                   tracking-widest
                                   text-emerald-600">

                            Inventario

                        </p>

                        <h3
                            class="text-xl
                                   font-extrabold
                                   text-slate-900
                                   mt-1">

                            {{ $terrenoId
                                ? 'Editar Lote / Terreno'
                                : 'Registrar Nuevo Lote' }}

                        </h3>

                    </div>


                    <button
                        type="button"
                        wire:click="cerrarModal"
                        class="w-9 h-9
                               rounded-lg
                               bg-slate-100
                               hover:bg-slate-200
                               text-slate-500
                               flex items-center justify-center
                               transition
                               cursor-pointer">

                        <span class="text-xl leading-none">
                            ×
                        </span>

                    </button>

                </div>


                {{-- Formulario --}}
                <form
                    wire:submit.prevent="guardar"
                    novalidate
                    class="p-6 space-y-5">

                    {{-- Manzana / Lote --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Manzana --}}
                        <div>

                            <label
                                class="block
                                       text-xs
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-slate-600
                                       mb-1.5">

                                Manzana *

                            </label>

                            <input
                                type="text"
                                wire:model="manzana"
                                placeholder="Ej. 04"
                                class="w-full
                                       px-4 py-2.5
                                       border border-slate-200
                                       rounded-xl
                                       bg-slate-50
                                       text-sm
                                       focus:outline-none
                                       focus:ring-2
                                       focus:ring-emerald-500/30
                                       focus:border-emerald-500">

                            @error('manzana')

                                <span
                                    class="block
                                           text-xs
                                           text-rose-500
                                           mt-1">

                                    {{ $message }}

                                </span>

                            @enderror

                        </div>


                        {{-- Lote --}}
                        <div>

                            <label
                                class="block
                                       text-xs
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-slate-600
                                       mb-1.5">

                                Lote *

                            </label>

                            <input
                                type="text"
                                wire:model="lote"
                                placeholder="Ej. 01"
                                class="w-full
                                       px-4 py-2.5
                                       border border-slate-200
                                       rounded-xl
                                       bg-slate-50
                                       text-sm
                                       focus:outline-none
                                       focus:ring-2
                                       focus:ring-emerald-500/30
                                       focus:border-emerald-500">

                            @error('lote')

                                <span
                                    class="block
                                           text-xs
                                           text-rose-500
                                           mt-1">

                                    {{ $message }}

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Medidas / Superficie --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Medidas --}}
                        <div>

                            <label
                                class="block
                                       text-xs
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-slate-600
                                       mb-1.5">

                                Medidas *

                            </label>

                            <input
                                type="text"
                                wire:model="medidas"
                                placeholder="Ej. 10x20 m"
                                class="w-full
                                       px-4 py-2.5
                                       border border-slate-200
                                       rounded-xl
                                       bg-slate-50
                                       text-sm
                                       focus:outline-none
                                       focus:ring-2
                                       focus:ring-emerald-500/30
                                       focus:border-emerald-500">

                            @error('medidas')

                                <span
                                    class="block
                                           text-xs
                                           text-rose-500
                                           mt-1">

                                    {{ $message }}

                                </span>

                            @enderror

                        </div>


                        {{-- Superficie --}}
                        <div>

                            <label
                                class="block
                                       text-xs
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-slate-600
                                       mb-1.5">

                                Superficie (m²) *

                            </label>

                            <input
                                type="number"
                                step="0.01"
                                wire:model="superficie"
                                placeholder="200.00"
                                class="w-full
                                       px-4 py-2.5
                                       border border-slate-200
                                       rounded-xl
                                       bg-slate-50
                                       text-sm
                                       focus:outline-none
                                       focus:ring-2
                                       focus:ring-emerald-500/30
                                       focus:border-emerald-500">

                            @error('superficie')

                                <span
                                    class="block
                                           text-xs
                                           text-rose-500
                                           mt-1">

                                    {{ $message }}

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Precio / Estado --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Precio --}}
                        <div>

                            <label
                                class="block
                                       text-xs
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-slate-600
                                       mb-1.5">

                                Precio ($) *

                            </label>

                            <input
                                type="number"
                                step="0.01"
                                wire:model="precio"
                                placeholder="400000.00"
                                class="w-full
                                       px-4 py-2.5
                                       border border-slate-200
                                       rounded-xl
                                       bg-slate-50
                                       text-sm
                                       focus:outline-none
                                       focus:ring-2
                                       focus:ring-emerald-500/30
                                       focus:border-emerald-500">

                            @error('precio')

                                <span
                                    class="block
                                           text-xs
                                           text-rose-500
                                           mt-1">

                                    {{ $message }}

                                </span>

                            @enderror

                        </div>


                        {{-- Estado --}}
                        <div>

                            <label
                                class="block
                                       text-xs
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-slate-600
                                       mb-1.5">

                                Estado *

                            </label>

                            <select
                                wire:model="estado"
                                class="w-full
                                       px-4 py-2.5
                                       border border-slate-200
                                       rounded-xl
                                       bg-slate-50
                                       text-sm
                                       focus:outline-none
                                       focus:ring-2
                                       focus:ring-emerald-500/30
                                       focus:border-emerald-500">

                                <option value="DISPONIBLE">
                                    DISPONIBLE
                                </option>

                                <option value="APARTADO">
                                    APARTADO
                                </option>

                                <option value="VENDIDO">
                                    VENDIDO
                                </option>

                            </select>

                            @error('estado')

                                <span
                                    class="block
                                           text-xs
                                           text-rose-500
                                           mt-1">

                                    {{ $message }}

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Ubicación --}}
                    <div>

                        <label
                            class="block
                                   text-xs
                                   font-bold
                                   uppercase
                                   tracking-wider
                                   text-slate-600
                                   mb-1.5">

                            Ubicación / Referencia

                        </label>

                        <input
                            type="text"
                            wire:model="ubicacion"
                            placeholder="Ej. Esquina norte, frente al parque"
                            class="w-full
                                   px-4 py-2.5
                                   border border-slate-200
                                   rounded-xl
                                   bg-slate-50
                                   text-sm
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-emerald-500/30
                                   focus:border-emerald-500">

                        @error('ubicacion')

                            <span
                                class="block
                                       text-xs
                                       text-rose-500
                                       mt-1">

                                {{ $message }}

                            </span>

                        @enderror

                    </div>


                    {{-- Botones --}}
                    <div
                        class="flex justify-end
                               gap-3
                               pt-4
                               border-t border-slate-100">

                        <button
                            type="button"
                            wire:click="cerrarModal"
                            class="px-5 py-2.5
                                   border border-slate-200
                                   text-slate-600
                                   hover:bg-slate-50
                                   rounded-xl
                                   text-sm font-bold
                                   transition
                                   cursor-pointer">

                            Cancelar

                        </button>


                        <button
                            type="submit"
                            class="px-5 py-2.5
                                   bg-emerald-600
                                   hover:bg-emerald-700
                                   text-white
                                   rounded-xl
                                   text-sm font-bold
                                   shadow-lg
                                   shadow-emerald-600/20
                                   transition
                                   cursor-pointer">

                            {{ $terrenoId
                                ? 'Actualizar Lote'
                                : 'Guardar Lote' }}

                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endif

</div>