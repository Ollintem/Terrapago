<div class="p-6 max-w-7xl mx-auto space-y-6">

    {{-- ============================================================
         ENCABEZADO
    ============================================================ --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        <div>

            {{-- SECCIÓN --}}
            <div class="flex items-center gap-2 mb-2">

                <span
                    class="h-2 w-2 rounded-full
                           bg-emerald-500
                           shadow-sm
                           shadow-emerald-500/50">
                </span>

                <span
                    class="text-[11px]
                           font-bold
                           uppercase
                           tracking-[0.16em]
                           text-emerald-600">
                    Operaciones
                </span>

            </div>

            {{-- TÍTULO --}}
            <h1
                class="text-3xl sm:text-4xl
                       font-extrabold
                       tracking-tight
                       text-slate-900">

                Gestión de
                <span class="text-emerald-600">
                    Contratos
                </span>

            </h1>

            {{-- DESCRIPCIÓN --}}
            <p
                class="mt-2
                       text-sm sm:text-base
                       font-medium
                       text-slate-500
                       max-w-xl">

                Administra los contratos y operaciones de venta de
                <span class="font-semibold text-slate-700">
                    terrenos
                </span>.

            </p>

        </div>


        {{-- BOTÓN FORMALIZAR CONTRATO --}}
        @if(
            (auth()->user()->rol &&
            in_array(
                strtolower(auth()->user()->rol->nombre),
                ['administrador', 'super admin', 'superadministrador']
            ))
            ||
            auth()->user()->permisos()
                ->whereHas(
                    'modulo',
                    fn($q) => $q->where('clave', 'contratos')
                )
                ->where('crear', true)
                ->exists()
        )

            <button
                wire:click="abrirModalCrear"
                class="inline-flex
                       items-center
                       gap-2
                       px-5
                       py-2.5
                       bg-emerald-600
                       hover:bg-emerald-700
                       text-white
                       text-sm
                       font-semibold
                       rounded-xl
                       shadow-md
                       shadow-emerald-600/20
                       transition-all
                       duration-200
                       hover:-translate-y-0.5
                       cursor-pointer"
            >

                <span class="text-base">
                    +
                </span>

                Formalizar Contrato

            </button>

        @endif

    </div>


    {{-- ============================================================
         MENSAJES
    ============================================================ --}}
    @if(session()->has('mensaje'))

        <div
            class="flex items-center gap-3
                   p-4
                   rounded-xl
                   bg-emerald-50
                   border border-emerald-200
                   text-emerald-700
                   text-sm
                   font-medium"
        >

            <div
                class="w-8 h-8
                       rounded-full
                       bg-emerald-100
                       flex items-center
                       justify-center
                       text-emerald-600
                       font-bold"
            >
                ✓
            </div>

            <span>
                {{ session('mensaje') }}
            </span>

        </div>

    @endif


    @if(session()->has('error'))

        <div
            class="flex items-center gap-3
                   p-4
                   rounded-xl
                   bg-rose-50
                   border border-rose-200
                   text-rose-700
                   text-sm
                   font-medium"
        >

            <div
                class="w-8 h-8
                       rounded-full
                       bg-rose-100
                       flex items-center
                       justify-center
                       text-rose-600
                       font-bold"
            >
                !
            </div>

            <span>
                {{ session('error') }}
            </span>

        </div>

    @endif


    {{-- ============================================================
         TABLA DE CONTRATOS
    ============================================================ --}}
    <div
        class="bg-white
               rounded-2xl
               shadow-sm
               border border-slate-200
               overflow-hidden"
    >

        {{-- BUSCADOR --}}
        <div
            class="p-5
                   border-b border-slate-100"
        >

            <div class="relative max-w-md">

                <span
                    class="absolute
                           left-3
                           top-1/2
                           -translate-y-1/2
                           text-slate-400"
                >
                    🔎
                </span>

                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Buscar contrato, cliente, manzana o lote..."
                    class="w-full
                           pl-10
                           pr-4
                           py-2.5
                           border border-slate-300
                           rounded-xl
                           text-sm
                           text-slate-700
                           placeholder:text-slate-400
                           focus:outline-none
                           focus:ring-2
                           focus:ring-emerald-500
                           focus:border-emerald-500
                           transition"
                >

            </div>

        </div>


        {{-- TABLA --}}
        <div class="overflow-x-auto">

            <table class="w-full text-left border-collapse">

                <thead>

                    <tr
                        class="bg-slate-50
                               text-slate-500
                               text-[11px]
                               uppercase
                               tracking-wider
                               font-bold
                               border-b border-slate-200"
                    >

                        <th class="py-4 px-6">
                            Folio / Inicio
                        </th>

                        <th class="py-4 px-6">
                            Cliente
                        </th>

                        <th class="py-4 px-6">
                            Lote Asignado
                        </th>

                        <th class="py-4 px-6">
                            Resumen Financiero
                        </th>

                        <th class="py-4 px-6 text-center">
                            Estado
                        </th>

                        <th class="py-4 px-6 text-right">
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody
                    class="divide-y
                           divide-slate-100
                           text-sm
                           text-slate-700"
                >

                    @forelse($contratos as $c)

                        <tr
                            class="hover:bg-slate-50/70
                                   transition"
                        >

                            {{-- FOLIO --}}
                            <td class="py-4 px-6">

                                <p class="font-bold text-slate-900">
                                    {{ $c->folio }}
                                </p>

                                <p class="text-xs text-slate-400 mt-0.5">

                                    {{ $c->fecha_inicio
                                        ? $c->fecha_inicio->format('d/m/Y')
                                        : 'Sin fecha'
                                    }}

                                </p>

                            </td>


                            {{-- CLIENTE --}}
                            <td class="py-4 px-6">

                                <p class="font-semibold text-slate-800">
                                    {{ $c->cliente->nombre_completo ?? 'Sin cliente' }}
                                </p>

                                @if($c->cliente?->telefono)

                                    <p class="text-xs text-slate-400 mt-0.5">
                                        {{ $c->cliente->telefono }}
                                    </p>

                                @endif

                            </td>


                            {{-- TERRENO --}}
                            <td class="py-4 px-6">

                                <p class="font-semibold text-slate-800">

                                    Mz. {{ $c->terreno->manzana ?? '-' }}
                                    —
                                    Lt. {{ $c->terreno->lote ?? '-' }}

                                </p>

                                <p class="text-xs text-slate-400 mt-0.5">

                                    {{ $c->terreno->superficie ?? '0' }} m²

                                </p>

                            </td>


                            {{-- FINANZAS --}}
                            <td class="py-4 px-6">

                                <p class="text-xs text-slate-500">

                                    Total:

                                    <span class="font-bold text-slate-800">
                                        ${{ number_format($c->precio_total, 2) }}
                                    </span>

                                </p>

                                <p class="text-xs text-slate-500 mt-1">

                                    Saldo:

                                    <span class="font-bold text-rose-600">
                                        ${{ number_format($c->saldo_actual, 2) }}
                                    </span>

                                </p>

                                <p class="text-[11px] text-slate-400 mt-1">

                                    {{ $c->numero_cuotas }}
                                    cuotas
                                    {{ strtolower($c->frecuencia_pago) }}

                                </p>

                            </td>


                            {{-- ESTADO --}}
                            <td class="py-4 px-6 text-center">

                                @if($c->estado === 'ACTIVO')

                                    <span
                                        class="inline-flex
                                               px-3 py-1
                                               rounded-full
                                               bg-emerald-100
                                               text-emerald-700
                                               border border-emerald-200
                                               text-xs
                                               font-bold"
                                    >
                                        Activo
                                    </span>

                                @elseif($c->estado === 'LIQUIDADO')

                                    <span
                                        class="inline-flex
                                               px-3 py-1
                                               rounded-full
                                               bg-blue-100
                                               text-blue-700
                                               border border-blue-200
                                               text-xs
                                               font-bold"
                                    >
                                        Liquidado
                                    </span>

                                @else

                                    <span
                                        class="inline-flex
                                               px-3 py-1
                                               rounded-full
                                               bg-rose-100
                                               text-rose-700
                                               border border-rose-200
                                               text-xs
                                               font-bold"
                                    >
                                        Cancelado
                                    </span>

                                @endif

                            </td>


                            {{-- ACCIONES --}}
                            <td class="py-4 px-6">

                                <div
                                    class="flex
                                           justify-end
                                           items-center
                                           gap-2
                                           flex-wrap"
                                >

                                    <button
                                        wire:click="verEstadoCuenta({{ $c->id }})"
                                        class="px-3
                                               py-2
                                               bg-slate-100
                                               hover:bg-slate-200
                                               text-slate-700
                                               text-xs
                                               font-semibold
                                               rounded-lg
                                               transition
                                               cursor-pointer"
                                    >
                                        Estado de cuenta
                                    </button>


                                    @if($c->estado === 'ACTIVO')

                                        {{-- LIQUIDAR --}}
                                        @if(
                                            (auth()->user()->rol &&
                                            in_array(
                                                strtolower(auth()->user()->rol->nombre),
                                                ['administrador', 'super admin', 'superadministrador']
                                            ))
                                            ||
                                            auth()->user()->permisos()
                                                ->whereHas(
                                                    'modulo',
                                                    fn($q) => $q->where('clave', 'contratos')
                                                )
                                                ->where('editar', true)
                                                ->exists()
                                        )

                                            <button
                                                wire:click="liquidarContrato({{ $c->id }})"
                                                wire:confirm="¿Deseas liquidar el contrato {{ $c->folio }}? Las cuotas pendientes se marcarán como pagadas."
                                                class="px-3
                                                       py-2
                                                       bg-emerald-50
                                                       hover:bg-emerald-100
                                                       text-emerald-700
                                                       text-xs
                                                       font-semibold
                                                       rounded-lg
                                                       transition
                                                       cursor-pointer"
                                            >
                                                Liquidar
                                            </button>

                                        @endif


                                        {{-- CANCELAR --}}
                                        @if(
                                            (auth()->user()->rol &&
                                            in_array(
                                                strtolower(auth()->user()->rol->nombre),
                                                ['administrador', 'super admin', 'superadministrador']
                                            ))
                                            ||
                                            auth()->user()->permisos()
                                                ->whereHas(
                                                    'modulo',
                                                    fn($q) => $q->where('clave', 'contratos')
                                                )
                                                ->where('eliminar', true)
                                                ->exists()
                                        )

                                            <button
                                                wire:click="cancelarContrato({{ $c->id }})"
                                                wire:confirm="¿Seguro que deseas cancelar el contrato {{ $c->folio }}? El terreno volverá a estar disponible."
                                                class="px-3
                                                       py-2
                                                       bg-rose-50
                                                       hover:bg-rose-100
                                                       text-rose-700
                                                       text-xs
                                                       font-semibold
                                                       rounded-lg
                                                       transition
                                                       cursor-pointer"
                                            >
                                                Cancelar
                                            </button>

                                        @endif

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="py-12 text-center"
                            >

                                <div class="text-slate-400">

                                    <div class="text-3xl mb-2">
                                        📄
                                    </div>

                                    <p
                                        class="font-semibold
                                               text-slate-500"
                                    >
                                        No hay contratos registrados
                                    </p>

                                    <p class="text-xs mt-1">
                                        Los contratos formalizados aparecerán aquí.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINACIÓN --}}
        <div class="p-4 border-t border-slate-100">

            {{ $contratos->links() }}

        </div>

    </div>


    {{-- ============================================================
         MODAL FORMALIZAR CONTRATO
    ============================================================ --}}
    @if($modalCrearAbierto)

        <div
            class="fixed inset-0
                   bg-slate-950/50
                   backdrop-blur-sm
                   flex items-center
                   justify-center
                   p-4
                   z-50"
        >

            <div
                class="bg-white
                       rounded-2xl
                       shadow-2xl
                       w-full
                       max-w-5xl
                       max-h-[94vh]
                       overflow-y-auto"
            >

                {{-- ENCABEZADO --}}
                <div
                    class="px-6 py-5
                           border-b border-slate-100
                           flex items-center
                           justify-between"
                >

                    <div>

                        <h3 class="text-xl font-bold text-slate-800">

                            Formalizar
                            <span class="text-emerald-600">
                                Contrato
                            </span>

                        </h3>

                        <p class="text-xs text-slate-500 mt-1">
                            Registra al cliente, asigna el terreno y configura el financiamiento.
                        </p>

                    </div>


                    <button
                        type="button"
                        wire:click="cerrarModalCrear"
                        class="w-9 h-9
                               rounded-lg
                               bg-slate-100
                               hover:bg-slate-200
                               text-slate-500
                               hover:text-slate-700
                               transition
                               cursor-pointer"
                    >
                        ✕
                    </button>

                </div>


                {{-- FORMULARIO --}}
                <form
                    wire:submit.prevent="guardarContrato"
                    novalidate
                >

                    <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">

                        {{-- =================================================
                             COLUMNA IZQUIERDA
                        ================================================== --}}
                        <div class="space-y-5">

                            {{-- DATOS DEL CLIENTE --}}
                            <div
                                class="bg-white
                                       border border-slate-200
                                       rounded-2xl
                                       p-5
                                       shadow-sm"
                            >

                                <div class="flex items-center gap-2 mb-4">

                                    <div
                                        class="w-7 h-7
                                               rounded-lg
                                               bg-emerald-100
                                               text-emerald-700
                                               flex items-center
                                               justify-center
                                               text-xs
                                               font-bold"
                                    >
                                        1
                                    </div>

                                    <h4
                                        class="text-sm
                                               font-bold
                                               uppercase
                                               tracking-wide
                                               text-slate-700"
                                    >
                                        Datos del cliente
                                    </h4>

                                </div>


                                <label
                                    class="block
                                           text-xs
                                           font-semibold
                                           text-slate-500
                                           mb-1"
                                >
                                    Cliente
                                </label>

                                <select
                                    wire:model="cliente_id"
                                    class="w-full
                                           px-3
                                           py-2.5
                                           border border-slate-300
                                           rounded-lg
                                           text-sm
                                           text-slate-700
                                           focus:outline-none
                                           focus:ring-2
                                           focus:ring-emerald-500
                                           focus:border-emerald-500"
                                >

                                    <option value="">
                                        Seleccione un cliente...
                                    </option>

                                    @foreach($clientesDisponibles as $cl)

                                        <option value="{{ $cl->id }}">
                                            {{ $cl->nombre_completo }}

                                            @if($cl->telefono)
                                                — {{ $cl->telefono }}
                                            @endif
                                        </option>

                                    @endforeach

                                </select>

                                @error('cliente_id')

                                    <span class="block mt-1 text-xs text-rose-500">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </div>


                            {{-- LOTE --}}
                            <div
                                class="bg-white
                                       border border-slate-200
                                       rounded-2xl
                                       p-5
                                       shadow-sm"
                            >

                                <div class="flex items-center gap-2 mb-4">

                                    <div
                                        class="w-7 h-7
                                               rounded-lg
                                               bg-emerald-100
                                               text-emerald-700
                                               flex items-center
                                               justify-center
                                               text-xs
                                               font-bold"
                                    >
                                        2
                                    </div>

                                    <h4
                                        class="text-sm
                                               font-bold
                                               uppercase
                                               tracking-wide
                                               text-slate-700"
                                    >
                                        Lote asignado
                                    </h4>

                                </div>


                                <label
                                    class="block
                                           text-xs
                                           font-semibold
                                           text-slate-500
                                           mb-1"
                                >
                                    Terreno disponible
                                </label>

                                <select
                                    wire:model.live="terreno_id"
                                    class="w-full
                                           px-3
                                           py-2.5
                                           border border-slate-300
                                           rounded-lg
                                           text-sm
                                           text-slate-700
                                           focus:outline-none
                                           focus:ring-2
                                           focus:ring-emerald-500
                                           focus:border-emerald-500"
                                >

                                    <option value="">
                                        Seleccione un terreno...
                                    </option>

                                    @foreach($terrenosDisponibles as $ter)

                                        <option value="{{ $ter->id }}">

                                            Mz. {{ $ter->manzana }}
                                            —
                                            Lt. {{ $ter->lote }}
                                            (${{ number_format($ter->precio, 2) }})

                                        </option>

                                    @endforeach

                                </select>

                                @error('terreno_id')

                                    <span class="block mt-1 text-xs text-rose-500">
                                        {{ $message }}
                                    </span>

                                @enderror


                                @if($terreno_id)

                                    @php
                                        $terrenoSeleccionado =
                                            $terrenosDisponibles->firstWhere(
                                                'id',
                                                $terreno_id
                                            );
                                    @endphp

                                    @if($terrenoSeleccionado)

                                        <div
                                            class="mt-4
                                                   p-4
                                                   rounded-xl
                                                   bg-emerald-50
                                                   border border-emerald-200"
                                        >

                                            <div class="flex items-center gap-3">

                                                <div
                                                    class="w-10 h-10
                                                           rounded-lg
                                                           bg-emerald-500
                                                           text-white
                                                           flex items-center
                                                           justify-center"
                                                >
                                                    🏠
                                                </div>

                                                <div>

                                                    <p class="font-bold text-emerald-800">

                                                        Mz.
                                                        {{ $terrenoSeleccionado->manzana }}
                                                        —
                                                        Lt.
                                                        {{ $terrenoSeleccionado->lote }}

                                                    </p>

                                                    <p class="text-xs text-emerald-600">

                                                        {{ $terrenoSeleccionado->superficie }}
                                                        m²
                                                        · Disponible

                                                    </p>

                                                </div>

                                            </div>

                                        </div>

                                    @endif

                                @endif

                            </div>


                            {{-- CONFIGURACIÓN FINANCIERA --}}
                            <div
                                class="bg-white
                                       border border-slate-200
                                       rounded-2xl
                                       p-5
                                       shadow-sm"
                            >

                                <div class="flex items-center gap-2 mb-5">

                                    <div
                                        class="w-7 h-7
                                               rounded-lg
                                               bg-emerald-100
                                               text-emerald-700
                                               flex items-center
                                               justify-center
                                               text-xs
                                               font-bold"
                                    >
                                        3
                                    </div>

                                    <h4
                                        class="text-sm
                                               font-bold
                                               uppercase
                                               tracking-wide
                                               text-slate-700"
                                    >
                                        Configuración financiera
                                    </h4>

                                </div>


                                {{-- PRECIO --}}
                                <div class="mb-4">

                                    <label
                                        class="block
                                               text-xs
                                               font-semibold
                                               text-slate-500
                                               mb-1"
                                    >
                                        Precio acordado (MXN)
                                    </label>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="1"
                                        wire:model.live="precio_total"
                                        class="w-full
                                               px-3
                                               py-2.5
                                               border border-slate-300
                                               rounded-lg
                                               text-sm
                                               font-semibold
                                               text-slate-800
                                               focus:outline-none
                                               focus:ring-2
                                               focus:ring-emerald-500
                                               focus:border-emerald-500"
                                    >

                                    @error('precio_total')

                                        <span class="block mt-1 text-xs text-rose-500">
                                            {{ $message }}
                                        </span>

                                    @enderror

                                </div>


                                {{-- ENGANCHE --}}
                                <div class="mb-4">

                                    <label
                                        class="block
                                               text-xs
                                               font-semibold
                                               text-slate-500
                                               mb-1"
                                    >
                                        Enganche inicial (MXN)
                                    </label>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        wire:model.live="enganche"
                                        class="w-full
                                               px-3
                                               py-2.5
                                               border border-slate-300
                                               rounded-lg
                                               text-sm
                                               font-semibold
                                               text-emerald-700
                                               focus:outline-none
                                               focus:ring-2
                                               focus:ring-emerald-500
                                               focus:border-emerald-500"
                                    >

                                    @error('enganche')

                                        <span class="block mt-1 text-xs text-rose-500">
                                            {{ $message }}
                                        </span>

                                    @enderror

                                </div>


                                {{-- SALDO --}}
                                <div
                                    class="p-4
                                           bg-slate-50
                                           rounded-xl
                                           border border-slate-100
                                           flex items-center
                                           justify-between"
                                >

                                    <span class="text-xs text-slate-500">
                                        Saldo a financiar
                                    </span>

                                    <span class="font-bold text-slate-800">
                                        ${{ number_format($saldo_inicial, 2) }}
                                    </span>

                                </div>


                                {{-- CUOTAS / FRECUENCIA --}}
                                <div class="grid grid-cols-2 gap-3 mt-4">

                                    <div>

                                        <label
                                            class="block
                                                   text-xs
                                                   font-semibold
                                                   text-slate-500
                                                   mb-1"
                                        >
                                            Plazo (cuotas)
                                        </label>

                                        <input
                                            type="number"
                                            min="1"
                                            max="360"
                                            wire:model="numero_cuotas"
                                            class="w-full
                                                   px-3
                                                   py-2.5
                                                   border border-slate-300
                                                   rounded-lg
                                                   text-sm
                                                   focus:outline-none
                                                   focus:ring-2
                                                   focus:ring-emerald-500"
                                        >

                                        @error('numero_cuotas')

                                            <span class="block mt-1 text-xs text-rose-500">
                                                {{ $message }}
                                            </span>

                                        @enderror

                                    </div>


                                    <div>

                                        <label
                                            class="block
                                                   text-xs
                                                   font-semibold
                                                   text-slate-500
                                                   mb-1"
                                        >
                                            Frecuencia de pago
                                        </label>

                                        <select
                                            wire:model="frecuencia_pago"
                                            class="w-full
                                                   px-3
                                                   py-2.5
                                                   border border-slate-300
                                                   rounded-lg
                                                   text-sm
                                                   focus:outline-none
                                                   focus:ring-2
                                                   focus:ring-emerald-500"
                                        >

                                            <option value="SEMANAL">
                                                Semanal
                                            </option>

                                            <option value="QUINCENAL">
                                                Quincenal
                                            </option>

                                            <option value="MENSUAL">
                                                Mensual
                                            </option>

                                            <option value="ANUAL">
                                                Anual
                                            </option>

                                        </select>

                                        @error('frecuencia_pago')

                                            <span class="block mt-1 text-xs text-rose-500">
                                                {{ $message }}
                                            </span>

                                        @enderror

                                    </div>

                                </div>


                                {{-- FECHA --}}
                                <div class="mt-4">

                                    <label
                                        class="block
                                               text-xs
                                               font-semibold
                                               text-slate-500
                                               mb-1"
                                    >
                                        Fecha de inicio
                                    </label>

                                    <input
                                        type="date"
                                        wire:model="fecha_inicio"
                                        class="w-full
                                               px-3
                                               py-2.5
                                               border border-slate-300
                                               rounded-lg
                                               text-sm
                                               focus:outline-none
                                               focus:ring-2
                                               focus:ring-emerald-500"
                                    >

                                    @error('fecha_inicio')

                                        <span class="block mt-1 text-xs text-rose-500">
                                            {{ $message }}
                                        </span>

                                    @enderror

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             COLUMNA DERECHA
                        ================================================== --}}
                        <div
                            class="bg-white
                                   border border-slate-200
                                   rounded-2xl
                                   shadow-sm
                                   overflow-hidden
                                   flex flex-col
                                   min-h-[520px]"
                        >

                            <div
                                class="p-5
                                       border-b border-slate-100
                                       flex items-center
                                       justify-between"
                            >

                                <div>

                                    <h4 class="font-bold text-slate-800">

                                        Tabla de
                                        <span class="text-emerald-600">
                                            Amortización
                                        </span>

                                    </h4>

                                    <p class="text-xs text-slate-400 mt-1">

                                        {{ $numero_cuotas }}
                                        cuotas
                                        ·
                                        {{ strtolower($frecuencia_pago) }}

                                    </p>

                                </div>


                                <div
                                    class="px-3 py-1.5
                                           rounded-full
                                           bg-emerald-50
                                           border border-emerald-200
                                           text-emerald-700
                                           text-xs
                                           font-bold"
                                >

                                    Capital:
                                    ${{ number_format($saldo_inicial, 2) }}

                                </div>

                            </div>


                            {{-- TABLA DE AMORTIZACIÓN --}}
                            <div class="overflow-y-auto flex-1">

                                <table
                                    class="w-full
                                           text-left
                                           border-collapse
                                           text-xs"
                                >

                                    <thead
                                        class="sticky top-0
                                               bg-slate-50
                                               border-b
                                               border-slate-200
                                               text-slate-400
                                               uppercase
                                               tracking-wide
                                               text-[9px]
                                               font-bold"
                                    >

                                        <tr>

                                            <th class="px-4 py-3">
                                                #
                                            </th>

                                            <th class="px-4 py-3">
                                                Fecha
                                            </th>

                                            <th class="px-4 py-3 text-right">
                                                Monto
                                            </th>

                                            <th class="px-4 py-3 text-right">
                                                Saldo
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody class="divide-y divide-slate-100">

                                        @if($saldo_inicial > 0 && $numero_cuotas > 0)

                                            @php

                                                $previewCuota = round(
                                                    $saldo_inicial / $numero_cuotas,
                                                    2
                                                );

                                                $previewSaldo = $saldo_inicial;

                                                $previewFecha = \Carbon\Carbon::parse(
                                                    $fecha_inicio ?: now()->format('Y-m-d')
                                                );

                                            @endphp


                                            @for(
                                                $i = 1;
                                                $i <= min($numero_cuotas, 60);
                                                $i++
                                            )

                                                @php

                                                    $fechaVencimiento = match ($frecuencia_pago) {

                                                        'SEMANAL' =>
                                                            (clone $previewFecha)->addWeeks($i),

                                                        'QUINCENAL' =>
                                                            (clone $previewFecha)->addDays($i * 15),

                                                        'MENSUAL' =>
                                                            (clone $previewFecha)->addMonthsNoOverflow($i),

                                                        'ANUAL' =>
                                                            (clone $previewFecha)->addYears($i),

                                                    };


                                                    if ($i === $numero_cuotas) {

                                                        $montoPreview = $previewSaldo;
                                                        $saldoPreview = 0;

                                                    } else {

                                                        $montoPreview = $previewCuota;

                                                        $saldoPreview = max(
                                                            0,
                                                            round(
                                                                $previewSaldo - $previewCuota,
                                                                2
                                                            )
                                                        );

                                                    }

                                                    $previewSaldo = $saldoPreview;

                                                @endphp


                                                <tr
                                                    class="hover:bg-slate-50
                                                           transition"
                                                >

                                                    <td
                                                        class="px-4 py-3
                                                               font-semibold
                                                               text-slate-500"
                                                    >
                                                        {{ str_pad(
                                                            $i,
                                                            2,
                                                            '0',
                                                            STR_PAD_LEFT
                                                        ) }}
                                                    </td>


                                                    <td class="px-4 py-3">

                                                        <span
                                                            class="font-medium
                                                                   text-slate-700"
                                                        >
                                                            {{ $fechaVencimiento->format('d M') }}
                                                        </span>

                                                        <span
                                                            class="block
                                                                   text-[10px]
                                                                   text-slate-400"
                                                        >
                                                            {{ $fechaVencimiento->format('Y') }}
                                                        </span>

                                                    </td>


                                                    <td
                                                        class="px-4 py-3
                                                               text-right
                                                               font-bold
                                                               text-slate-800"
                                                    >

                                                        ${{ number_format(
                                                            $montoPreview,
                                                            2
                                                        ) }}

                                                    </td>


                                                    <td
                                                        class="px-4 py-3
                                                               text-right
                                                               font-medium
                                                               text-slate-500"
                                                    >

                                                        ${{ number_format(
                                                            $saldoPreview,
                                                            2
                                                        ) }}

                                                    </td>

                                                </tr>

                                            @endfor


                                            @if($numero_cuotas > 60)

                                                <tr>

                                                    <td
                                                        colspan="4"
                                                        class="p-4
                                                               text-center
                                                               text-xs
                                                               text-slate-400"
                                                    >

                                                        Se muestran las primeras 60 cuotas.
                                                        Las {{ $numero_cuotas }} cuotas completas
                                                        se generarán al formalizar el contrato.

                                                    </td>

                                                </tr>

                                            @endif

                                        @else

                                            <tr>

                                                <td
                                                    colspan="4"
                                                    class="p-10
                                                           text-center"
                                                >

                                                    <div
                                                        class="text-slate-300
                                                               text-3xl"
                                                    >
                                                        📊
                                                    </div>

                                                    <p
                                                        class="text-sm
                                                               font-semibold
                                                               text-slate-400
                                                               mt-2"
                                                    >
                                                        La tabla de amortización aparecerá aquí
                                                    </p>

                                                    <p
                                                        class="text-xs
                                                               text-slate-400
                                                               mt-1"
                                                    >
                                                        Selecciona un terreno y configura el financiamiento.
                                                    </p>

                                                </td>

                                            </tr>

                                        @endif

                                    </tbody>

                                </table>

                            </div>


                            {{-- RESUMEN --}}
                            <div
                                class="border-t
                                       border-slate-100
                                       bg-slate-50
                                       p-4"
                            >

                                <div class="grid grid-cols-3 gap-3 text-center">

                                    <div>

                                        <span
                                            class="block
                                                   text-[10px]
                                                   uppercase
                                                   tracking-wide
                                                   text-slate-400"
                                        >
                                            Total a financiar
                                        </span>

                                        <span
                                            class="block
                                                   mt-1
                                                   font-bold
                                                   text-slate-800"
                                        >

                                            ${{ number_format(
                                                $saldo_inicial,
                                                2
                                            ) }}

                                        </span>

                                    </div>


                                    <div>

                                        <span
                                            class="block
                                                   text-[10px]
                                                   uppercase
                                                   tracking-wide
                                                   text-slate-400"
                                        >
                                            Número de cuotas
                                        </span>

                                        <span
                                            class="block
                                                   mt-1
                                                   font-bold
                                                   text-emerald-600"
                                        >

                                            {{ $numero_cuotas }}

                                        </span>

                                    </div>


                                    <div>

                                        <span
                                            class="block
                                                   text-[10px]
                                                   uppercase
                                                   tracking-wide
                                                   text-slate-400"
                                        >
                                            Pago aproximado
                                        </span>

                                        <span
                                            class="block
                                                   mt-1
                                                   font-bold
                                                   text-emerald-600"
                                        >

                                            ${{ number_format(
                                                $numero_cuotas > 0
                                                    ? $saldo_inicial / $numero_cuotas
                                                    : 0,
                                                2
                                            ) }}

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- BOTONES --}}
                    <div
                        class="px-6 py-4
                               border-t border-slate-100
                               flex justify-end
                               gap-3"
                    >

                        <button
                            type="button"
                            wire:click="cerrarModalCrear"
                            class="px-5
                                   py-2.5
                                   border border-slate-300
                                   text-slate-600
                                   hover:bg-slate-50
                                   rounded-xl
                                   text-sm
                                   font-semibold
                                   transition
                                   cursor-pointer"
                        >
                            Cancelar
                        </button>


                        <button
                            type="submit"
                            class="px-5
                                   py-2.5
                                   bg-emerald-600
                                   hover:bg-emerald-700
                                   text-white
                                   rounded-xl
                                   text-sm
                                   font-semibold
                                   shadow-md
                                   shadow-emerald-600/20
                                   transition
                                   cursor-pointer"
                        >
                            Formalizar Contrato
                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endif


    {{-- ============================================================
         MODAL ESTADO DE CUENTA
    ============================================================ --}}
    @if($modalDetalleAbierto && $contratoSeleccionado)

        <div
            class="fixed inset-0
                   bg-slate-950/50
                   backdrop-blur-sm
                   flex items-center
                   justify-center
                   p-4
                   z-50"
        >

            <div
                class="bg-white
                       rounded-2xl
                       shadow-2xl
                       w-full
                       max-w-5xl
                       max-h-[92vh]
                       flex flex-col
                       overflow-hidden"
            >

                {{-- ENCABEZADO --}}
                <div
                    class="p-5
                           border-b border-slate-100
                           flex justify-between
                           items-start"
                >

                    <div>

                        <h3 class="text-xl font-bold text-slate-800">

                            Estado de
                            <span class="text-emerald-600">
                                Cuenta
                            </span>

                        </h3>

                        <p class="text-xs text-slate-500 mt-1">

                            Folio:

                            <span class="font-semibold text-slate-700">
                                {{ $contratoSeleccionado->folio }}
                            </span>

                            ·

                            Cliente:

                            <span class="font-semibold text-slate-700">
                                {{ $contratoSeleccionado->cliente->nombre_completo ?? 'Sin cliente' }}
                            </span>

                        </p>

                    </div>


                    <button
                        wire:click="cerrarModalDetalle"
                        class="w-9 h-9
                               rounded-lg
                               bg-slate-100
                               hover:bg-slate-200
                               text-slate-500
                               cursor-pointer"
                    >
                        ✕
                    </button>

                </div>


                {{-- RESUMEN --}}
                <div class="p-5">

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

                        <div
                            class="p-4
                                   rounded-xl
                                   bg-slate-50
                                   border border-slate-100"
                        >

                            <span
                                class="text-[10px]
                                       uppercase
                                       tracking-wide
                                       text-slate-400"
                            >
                                Precio total
                            </span>

                            <p
                                class="font-bold
                                       text-slate-800
                                       mt-1"
                            >

                                ${{ number_format(
                                    $contratoSeleccionado->precio_total,
                                    2
                                ) }}

                            </p>

                        </div>


                        <div
                            class="p-4
                                   rounded-xl
                                   bg-emerald-50
                                   border border-emerald-100"
                        >

                            <span
                                class="text-[10px]
                                       uppercase
                                       tracking-wide
                                       text-emerald-600"
                            >
                                Enganche
                            </span>

                            <p
                                class="font-bold
                                       text-emerald-700
                                       mt-1"
                            >

                                ${{ number_format(
                                    $contratoSeleccionado->enganche,
                                    2
                                ) }}

                            </p>

                        </div>


                        <div
                            class="p-4
                                   rounded-xl
                                   bg-blue-50
                                   border border-blue-100"
                        >

                            <span
                                class="text-[10px]
                                       uppercase
                                       tracking-wide
                                       text-blue-600"
                            >
                                Financiado
                            </span>

                            <p
                                class="font-bold
                                       text-blue-700
                                       mt-1"
                            >

                                ${{ number_format(
                                    $contratoSeleccionado->saldo_inicial,
                                    2
                                ) }}

                            </p>

                        </div>


                        <div
                            class="p-4
                                   rounded-xl
                                   bg-rose-50
                                   border border-rose-100"
                        >

                            <span
                                class="text-[10px]
                                       uppercase
                                       tracking-wide
                                       text-rose-600"
                            >
                                Saldo pendiente
                            </span>

                            <p
                                class="font-bold
                                       text-rose-700
                                       mt-1"
                            >

                                ${{ number_format(
                                    $contratoSeleccionado->saldo_actual,
                                    2
                                ) }}

                            </p>

                        </div>

                    </div>

                </div>


                {{-- TABLA --}}
                <div
                    class="overflow-y-auto
                           flex-1
                           mx-5
                           border border-slate-200
                           rounded-xl"
                >

                    <table
                        class="w-full
                               text-left
                               border-collapse
                               text-xs"
                    >

                        <thead
                            class="sticky top-0
                                   bg-slate-50
                                   border-b
                                   border-slate-200
                                   text-slate-500
                                   uppercase
                                   tracking-wide
                                   text-[10px]
                                   font-bold"
                        >

                            <tr>

                                <th class="p-3">
                                    Cuota
                                </th>

                                <th class="p-3">
                                    Fecha de vencimiento
                                </th>

                                <th class="p-3 text-right">
                                    Monto
                                </th>

                                <th class="p-3 text-right">
                                    Saldo anterior
                                </th>

                                <th class="p-3 text-right">
                                    Saldo restante
                                </th>

                                <th class="p-3 text-center">
                                    Estado
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @forelse(
                                $contratoSeleccionado->cuotas
                                as $cuota
                            )

                                <tr
                                    class="hover:bg-slate-50
                                           transition"
                                >

                                    <td
                                        class="p-3
                                               font-semibold
                                               text-slate-700"
                                    >

                                        {{ str_pad(
                                            $cuota->numero_cuota,
                                            2,
                                            '0',
                                            STR_PAD_LEFT
                                        ) }}

                                    </td>


                                    <td class="p-3">

                                        @if($cuota->fecha_vencimiento)

                                            {{ \Carbon\Carbon::parse(
                                                $cuota->fecha_vencimiento
                                            )->format('d/m/Y') }}

                                        @else

                                            Sin fecha

                                        @endif

                                    </td>


                                    <td
                                        class="p-3
                                               text-right
                                               font-bold
                                               text-slate-800"
                                    >

                                        ${{ number_format(
                                            $cuota->monto,
                                            2
                                        ) }}

                                    </td>


                                    <td
                                        class="p-3
                                               text-right
                                               text-slate-500"
                                    >

                                        ${{ number_format(
                                            $cuota->saldo_anterior,
                                            2
                                        ) }}

                                    </td>


                                    <td
                                        class="p-3
                                               text-right
                                               font-semibold
                                               text-slate-700"
                                    >

                                        ${{ number_format(
                                            $cuota->saldo_restante,
                                            2
                                        ) }}

                                    </td>


                                    <td class="p-3 text-center">

                                        @if($cuota->estado === 'PAGADA')

                                            <span
                                                class="px-2.5
                                                       py-1
                                                       rounded-full
                                                       bg-emerald-100
                                                       text-emerald-700
                                                       text-[10px]
                                                       font-bold"
                                            >
                                                Pagada
                                            </span>

                                        @elseif($cuota->estado === 'VENCIDA')

                                            <span
                                                class="px-2.5
                                                       py-1
                                                       rounded-full
                                                       bg-rose-100
                                                       text-rose-700
                                                       text-[10px]
                                                       font-bold"
                                            >
                                                Vencida
                                            </span>

                                        @else

                                            <span
                                                class="px-2.5
                                                       py-1
                                                       rounded-full
                                                       bg-amber-100
                                                       text-amber-700
                                                       text-[10px]
                                                       font-bold"
                                            >
                                                Pendiente
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="6"
                                        class="p-10
                                               text-center
                                               text-slate-400"
                                    >
                                        No hay cuotas registradas para este contrato.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- PIE --}}
                <div
                    class="p-5
                           mt-4
                           border-t border-slate-100
                           flex flex-col
                           sm:flex-row
                           justify-between
                           items-center
                           gap-3"
                >

                    <div>

                        @if($contratoSeleccionado->estado === 'ACTIVO')

                            @if(
                                (auth()->user()->rol &&
                                in_array(
                                    strtolower(auth()->user()->rol->nombre),
                                    ['administrador', 'super admin', 'superadministrador']
                                ))
                                ||
                                auth()->user()->permisos()
                                    ->whereHas(
                                        'modulo',
                                        fn($q) => $q->where('clave', 'contratos')
                                    )
                                    ->where('editar', true)
                                    ->exists()
                            )

                                <button
                                    wire:click="liquidarContrato({{ $contratoSeleccionado->id }})"
                                    wire:confirm="¿Confirmas la liquidación total de la deuda por ${{ number_format($contratoSeleccionado->saldo_actual, 2) }}?"
                                    class="px-4
                                           py-2.5
                                           bg-emerald-600
                                           hover:bg-emerald-700
                                           text-white
                                           rounded-xl
                                           text-xs
                                           font-semibold
                                           transition
                                           cursor-pointer"
                                >
                                    Liquidar deuda total
                                </button>

                            @endif

                        @else

                            <span
                                class="text-xs
                                       font-semibold
                                       text-slate-400"
                            >
                                Contrato:
                                {{ ucfirst(
                                    strtolower(
                                        $contratoSeleccionado->estado
                                    )
                                ) }}
                            </span>

                        @endif

                    </div>


                    <button
                        type="button"
                        wire:click="cerrarModalDetalle"
                        class="px-5
                               py-2.5
                               bg-slate-800
                               hover:bg-slate-900
                               text-white
                               rounded-xl
                               text-xs
                               font-semibold
                               transition
                               cursor-pointer"
                    >
                        Cerrar estado de cuenta
                    </button>

                </div>

            </div>

        </div>

    @endif

</div>