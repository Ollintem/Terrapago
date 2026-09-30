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
               p-3 sm:p-4
               z-50"
    >

        <div
            class="bg-white
                   rounded-2xl
                   shadow-2xl
                   w-full
                   max-w-5xl
                   max-h-[94vh]
                   flex flex-col
                   overflow-hidden"
        >

            {{-- =================================================
                 ENCABEZADO
            ================================================== --}}

            <div
                class="px-5 py-4
                       border-b border-slate-100
                       flex items-center
                       justify-between
                       flex-shrink-0"
            >

                <div>

                    <p
                        class="text-xs
                               uppercase
                               tracking-wider
                               font-bold
                               text-emerald-600"
                    >
                        Operaciones
                    </p>

                    <h3
                        class="text-xl
                               font-bold
                               text-slate-800
                               mt-0.5"
                    >
                        Formalizar
                        <span class="text-emerald-600">
                            Contrato
                        </span>
                    </h3>

                    <p class="text-xs text-slate-500 mt-1">
                        Configura el terreno, condiciones financieras y tabla de amortización.
                    </p>

                </div>


                <button
                    type="button"
                    wire:click="cerrarModalCrear"
                    class="w-9 h-9
                           rounded-xl
                           bg-slate-100
                           hover:bg-slate-200
                           text-slate-500
                           hover:text-slate-700
                           transition
                           cursor-pointer
                           flex items-center
                           justify-center"
                >
                    ✕
                </button>

            </div>


            {{-- =================================================
                 FORMULARIO
            ================================================== --}}

            <form
                wire:submit.prevent="guardarContrato"
                class="flex-1
                       min-h-0
                       flex flex-col"
            >

                {{-- =================================================
                     CONTENIDO
                ================================================== --}}

                <div
                    class="flex-1
                           min-h-0
                           overflow-y-auto
                           p-5"
                >

                    <div
                        class="grid
                               grid-cols-1
                               lg:grid-cols-2
                               gap-5"
                    >

                        {{-- =================================================
                             COLUMNA IZQUIERDA
                        ================================================== --}}

                        <div class="space-y-5">


                            {{-- =================================================
                                 CLIENTE
                            ================================================== --}}

                            <div
                                class="rounded-2xl
                                       border border-slate-200
                                       bg-white
                                       p-4
                                       shadow-sm"
                            >

                                <div class="flex items-center gap-3 mb-4">

                                    <div
                                        class="w-9 h-9
                                               rounded-xl
                                               bg-blue-50
                                               text-blue-600
                                               flex items-center
                                               justify-center"
                                    >

                                        <svg
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m8-8a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm6-3a4 4 0 0 1 0 8m4 3v-2a4 4 0 0 0-3-3.87"
                                            />
                                        </svg>

                                    </div>

                                    <div>

                                        <h4
                                            class="font-bold
                                                   text-slate-800"
                                        >
                                            Datos del cliente
                                        </h4>

                                        <p
                                            class="text-xs
                                                   text-slate-400"
                                        >
                                            Selecciona al comprador del terreno.
                                        </p>

                                    </div>

                                </div>


                                <label
                                    class="block
                                           text-xs
                                           font-bold
                                           uppercase
                                           tracking-wide
                                           text-slate-600
                                           mb-2"
                                >
                                    Comprador *
                                </label>


                                <div class="relative">

                                    <div
                                        class="absolute
                                               left-3
                                               top-1/2
                                               -translate-y-1/2
                                               text-slate-400
                                               pointer-events-none"
                                    >

                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m8-8a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm6-3a4 4 0 0 1 0 8m4 3v-2a4 4 0 0 0-3-3.87"
                                            />
                                        </svg>

                                    </div>


                                    <select
                                        wire:model="cliente_id"
                                        class="w-full
                                               appearance-none
                                               rounded-xl
                                               border border-slate-300
                                               bg-white
                                               py-3
                                               pl-10
                                               pr-10
                                               text-sm
                                               font-medium
                                               text-slate-700
                                               shadow-sm
                                               outline-none
                                               transition
                                               hover:border-slate-400
                                               focus:border-emerald-500
                                               focus:ring-2
                                               focus:ring-emerald-100"
                                    >

                                        <option value="">
                                            Seleccione un cliente...
                                        </option>

                                        @foreach($clientesDisponibles as $cliente)

                                            <option value="{{ $cliente->id }}">
                                                {{ $cliente->nombre_completo }}
                                                @if($cliente->telefono)
                                                    — {{ $cliente->telefono }}
                                                @endif
                                            </option>

                                        @endforeach

                                    </select>


                                    <div
                                        class="absolute
                                               right-3
                                               top-1/2
                                               -translate-y-1/2
                                               text-slate-400
                                               pointer-events-none"
                                    >

                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="m6 9 6 6 6-6"
                                            />
                                        </svg>

                                    </div>

                                </div>


                                @error('cliente_id')

                                    <p
                                        class="mt-1.5
                                               text-xs
                                               font-medium
                                               text-rose-600"
                                    >
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- =================================================
                                 TERRENO
                            ================================================== --}}

                            <div
                                class="rounded-2xl
                                       border border-slate-200
                                       bg-white
                                       p-4
                                       shadow-sm"
                            >

                                <div class="flex items-center gap-3 mb-4">

                                    <div
                                        class="w-9 h-9
                                               rounded-xl
                                               bg-emerald-50
                                               text-emerald-600
                                               flex items-center
                                               justify-center"
                                    >

                                        <svg
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6"
                                            />
                                        </svg>

                                    </div>

                                    <div>

                                        <h4
                                            class="font-bold
                                                   text-slate-800"
                                        >
                                            Lote asignado
                                        </h4>

                                        <p
                                            class="text-xs
                                                   text-slate-400"
                                        >
                                            Selecciona el terreno que será vendido.
                                        </p>

                                    </div>

                                </div>


                                <label
                                    class="block
                                           text-xs
                                           font-bold
                                           uppercase
                                           tracking-wide
                                           text-slate-600
                                           mb-2"
                                >
                                    Terreno disponible *
                                </label>


                                <div class="relative">

                                    <div
                                        class="absolute
                                               left-3
                                               top-1/2
                                               -translate-y-1/2
                                               text-slate-400
                                               pointer-events-none"
                                    >

                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 20V10l8-6 8 6v10M8 20v-6h8v6"
                                            />
                                        </svg>

                                    </div>


                                    <select
                                        wire:model.live="terreno_id"
                                        class="w-full
                                               appearance-none
                                               rounded-xl
                                               border border-slate-300
                                               bg-white
                                               py-3
                                               pl-10
                                               pr-10
                                               text-sm
                                               font-medium
                                               text-slate-700
                                               shadow-sm
                                               outline-none
                                               transition
                                               hover:border-slate-400
                                               focus:border-emerald-500
                                               focus:ring-2
                                               focus:ring-emerald-100"
                                    >

                                        <option value="">
                                            Seleccione un terreno...
                                        </option>

                                        @foreach($terrenosDisponibles as $terreno)

                                            <option value="{{ $terreno->id }}">

                                                Mz. {{ $terreno->manzana }}
                                                —
                                                Lt. {{ $terreno->lote }}

                                                (${{ number_format($terreno->precio, 2) }})

                                            </option>

                                        @endforeach

                                    </select>


                                    <div
                                        class="absolute
                                               right-3
                                               top-1/2
                                               -translate-y-1/2
                                               text-slate-400
                                               pointer-events-none"
                                    >

                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="m6 9 6 6 6-6"
                                            />
                                        </svg>

                                    </div>

                                </div>


                                @error('terreno_id')

                                    <p
                                        class="mt-1.5
                                               text-xs
                                               font-medium
                                               text-rose-600"
                                    >
                                        {{ $message }}
                                    </p>

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
                                            class="mt-3
                                                   rounded-xl
                                                   border border-emerald-100
                                                   bg-emerald-50
                                                   p-3"
                                        >

                                            <div
                                                class="flex
                                                       items-center
                                                       justify-between
                                                       gap-3"
                                            >

                                                <div>

                                                    <p
                                                        class="text-[10px]
                                                               uppercase
                                                               tracking-wide
                                                               font-bold
                                                               text-emerald-600"
                                                    >
                                                        Terreno seleccionado
                                                    </p>

                                                    <p
                                                        class="text-sm
                                                               font-bold
                                                               text-slate-800
                                                               mt-0.5"
                                                    >
                                                        Mz. {{ $terrenoSeleccionado->manzana }}
                                                        —
                                                        Lote {{ $terrenoSeleccionado->lote }}
                                                    </p>

                                                </div>


                                                <div
                                                    class="text-right"
                                                >

                                                    <p
                                                        class="text-[10px]
                                                               uppercase
                                                               tracking-wide
                                                               text-slate-400"
                                                    >
                                                        Superficie
                                                    </p>

                                                    <p
                                                        class="text-sm
                                                               font-bold
                                                               text-emerald-700"
                                                    >
                                                        {{ $terrenoSeleccionado->superficie }}
                                                        m²
                                                    </p>

                                                </div>

                                            </div>

                                        </div>

                                    @endif

                                @endif

                            </div>


                            {{-- =================================================
                                 CONFIGURACIÓN FINANCIERA
                            ================================================== --}}

                            <div
                                class="rounded-2xl
                                       border border-slate-200
                                       bg-slate-50
                                       p-4
                                       shadow-sm"
                            >

                                <div class="flex items-center gap-3 mb-4">

                                    <div
                                        class="w-9 h-9
                                               rounded-xl
                                               bg-amber-50
                                               text-amber-600
                                               flex items-center
                                               justify-center"
                                    >

                                        <svg
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10v1m0 10v1m8-6a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"
                                            />
                                        </svg>

                                    </div>

                                    <div>

                                        <h4
                                            class="font-bold
                                                   text-slate-800"
                                        >
                                            Configuración financiera
                                        </h4>

                                        <p
                                            class="text-xs
                                                   text-slate-400"
                                        >
                                            Define el precio acordado y las condiciones del financiamiento.
                                        </p>

                                    </div>

                                </div>


                                <div
                                    class="grid
                                           grid-cols-1
                                           sm:grid-cols-2
                                           gap-4"
                                >

                                    {{-- PRECIO --}}

                                    <div>

                                        <label
                                            class="block
                                                   text-xs
                                                   font-bold
                                                   uppercase
                                                   tracking-wide
                                                   text-slate-600
                                                   mb-2"
                                        >
                                            Precio acordado *
                                        </label>


                                        <div class="relative">

                                            <span
                                                class="absolute
                                                       left-3
                                                       top-1/2
                                                       -translate-y-1/2
                                                       text-slate-400
                                                       font-bold
                                                       pointer-events-none"
                                            >
                                                $
                                            </span>


                                            <input
                                                type="number"
                                                step="0.01"
                                                min="1"
                                                wire:model.live="precio_total"
                                                onwheel="event.preventDefault(); this.blur();"
                                                onkeydown="if (event.key === 'ArrowUp' || event.key === 'ArrowDown') event.preventDefault();"
                                                class="w-full
                                                       rounded-xl
                                                       border border-slate-300
                                                       bg-white
                                                       py-3
                                                       pl-8
                                                       pr-3
                                                       text-sm
                                                       font-bold
                                                       text-slate-800
                                                       shadow-sm
                                                       outline-none
                                                       transition
                                                       hover:border-slate-400
                                                       focus:border-emerald-500
                                                       focus:ring-2
                                                       focus:ring-emerald-100"
                                            >

                                        </div>


                                        @error('precio_total')

                                            <p
                                                class="mt-1.5
                                                       text-xs
                                                       font-medium
                                                       text-rose-600"
                                            >
                                                {{ $message }}
                                            </p>

                                        @enderror

                                    </div>


                                    {{-- ENGANCHE --}}

                                    <div>

                                        <label
                                            class="block
                                                   text-xs
                                                   font-bold
                                                   uppercase
                                                   tracking-wide
                                                   text-slate-600
                                                   mb-2"
                                        >
                                            Enganche inicial *
                                        </label>


                                        <div class="relative">

                                            <span
                                                class="absolute
                                                       left-3
                                                       top-1/2
                                                       -translate-y-1/2
                                                       text-emerald-500
                                                       font-bold
                                                       pointer-events-none"
                                            >
                                                $
                                            </span>


                                            <input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                wire:model.live="enganche"
                                                onwheel="event.preventDefault(); this.blur();"
                                                onkeydown="if (event.key === 'ArrowUp' || event.key === 'ArrowDown') event.preventDefault();"
                                                class="w-full
                                                       rounded-xl
                                                       border border-slate-300
                                                       bg-white
                                                       py-3
                                                       pl-8
                                                       pr-3
                                                       text-sm
                                                       font-bold
                                                       text-emerald-700
                                                       shadow-sm
                                                       outline-none
                                                       transition
                                                       hover:border-slate-400
                                                       focus:border-emerald-500
                                                       focus:ring-2
                                                       focus:ring-emerald-100"
                                            >

                                        </div>


                                        @error('enganche')

                                            <p
                                                class="mt-1.5
                                                       text-xs
                                                       font-medium
                                                       text-rose-600"
                                            >
                                                {{ $message }}
                                            </p>

                                        @enderror

                                    </div>


                                    {{-- SALDO --}}

                                    <div>

                                        <label
                                            class="block
                                                   text-xs
                                                   font-bold
                                                   uppercase
                                                   tracking-wide
                                                   text-slate-600
                                                   mb-2"
                                        >
                                            Saldo a financiar
                                        </label>


                                        <div
                                            class="w-full
                                                   rounded-xl
                                                   border border-blue-100
                                                   bg-blue-50
                                                   py-3
                                                   px-3
                                                   text-sm
                                                   font-bold
                                                   text-blue-700"
                                        >

                                            ${{ number_format($saldo_inicial, 2) }}

                                        </div>

                                    </div>


                                    {{-- CUOTAS --}}

                                    <div>

                                        <label
                                            class="block
                                                   text-xs
                                                   font-bold
                                                   uppercase
                                                   tracking-wide
                                                   text-slate-600
                                                   mb-2"
                                        >
                                            Plazo de cuotas *
                                        </label>


                                        <div class="relative">

                                            <input
                                                type="number"
                                                min="1"
                                                max="360"
                                                wire:model.live="numero_cuotas"
                                                onwheel="event.preventDefault(); this.blur();"
                                                onkeydown="if (event.key === 'ArrowUp' || event.key === 'ArrowDown') event.preventDefault();"
                                                class="w-full
                                                       rounded-xl
                                                       border border-slate-300
                                                       bg-white
                                                       py-3
                                                       px-3
                                                       pr-16
                                                       text-sm
                                                       font-bold
                                                       text-slate-800
                                                       shadow-sm
                                                       outline-none
                                                       transition
                                                       hover:border-slate-400
                                                       focus:border-emerald-500
                                                       focus:ring-2
                                                       focus:ring-emerald-100"
                                            >


                                            <span
                                                class="absolute
                                                       right-3
                                                       top-1/2
                                                       -translate-y-1/2
                                                       text-[10px]
                                                       uppercase
                                                       tracking-wide
                                                       font-bold
                                                       text-slate-400
                                                       pointer-events-none"
                                            >
                                                cuotas
                                            </span>

                                        </div>


                                        @error('numero_cuotas')

                                            <p
                                                class="mt-1.5
                                                       text-xs
                                                       font-medium
                                                       text-rose-600"
                                            >
                                                {{ $message }}
                                            </p>

                                        @enderror

                                    </div>


                                    {{-- FRECUENCIA --}}

                                    <div>

                                        <label
                                            class="block
                                                   text-xs
                                                   font-bold
                                                   uppercase
                                                   tracking-wide
                                                   text-slate-600
                                                   mb-2"
                                        >
                                            Frecuencia de pago *
                                        </label>


                                        <div class="relative">

                                            <div
                                                class="absolute
                                                       left-3
                                                       top-1/2
                                                       -translate-y-1/2
                                                       text-blue-500
                                                       pointer-events-none"
                                            >

                                                <svg
                                                    class="w-4 h-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z"
                                                    />
                                                </svg>

                                            </div>


                                            <select
                                                wire:model.live="frecuencia_pago"
                                                class="w-full
                                                       appearance-none
                                                       rounded-xl
                                                       border border-slate-300
                                                       bg-white
                                                       py-3
                                                       pl-10
                                                       pr-10
                                                       text-sm
                                                       font-semibold
                                                       text-slate-700
                                                       shadow-sm
                                                       outline-none
                                                       transition
                                                       hover:border-slate-400
                                                       focus:border-blue-500
                                                       focus:ring-2
                                                       focus:ring-blue-100"
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


                                            <div
                                                class="absolute
                                                       right-3
                                                       top-1/2
                                                       -translate-y-1/2
                                                       text-slate-400
                                                       pointer-events-none"
                                            >

                                                <svg
                                                    class="w-4 h-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="m6 9 6 6 6-6"
                                                    />
                                                </svg>

                                            </div>

                                        </div>


                                        @error('frecuencia_pago')

                                            <p
                                                class="mt-1.5
                                                       text-xs
                                                       font-medium
                                                       text-rose-600"
                                            >
                                                {{ $message }}
                                            </p>

                                        @enderror

                                    </div>


                                    {{-- FECHA --}}

                                    <div>

                                        <label
                                            class="block
                                                   text-xs
                                                   font-bold
                                                   uppercase
                                                   tracking-wide
                                                   text-slate-600
                                                   mb-2"
                                        >
                                            Fecha de inicio *
                                        </label>


                                        <input
                                            type="date"
                                            wire:model="fecha_inicio"
                                            class="w-full
                                                   rounded-xl
                                                   border border-slate-300
                                                   bg-white
                                                   py-3
                                                   px-3
                                                   text-sm
                                                   font-semibold
                                                   text-slate-700
                                                   shadow-sm
                                                   outline-none
                                                   transition
                                                   hover:border-slate-400
                                                   focus:border-emerald-500
                                                   focus:ring-2
                                                   focus:ring-emerald-100"
                                        >


                                        @error('fecha_inicio')

                                            <p
                                                class="mt-1.5
                                                       text-xs
                                                       font-medium
                                                       text-rose-600"
                                            >
                                                {{ $message }}
                                            </p>

                                        @enderror

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             COLUMNA DERECHA
                        ================================================== --}}

                        <div
                            class="rounded-2xl
                                   border border-slate-200
                                   bg-white
                                   shadow-sm
                                   overflow-hidden
                                   flex
                                   flex-col
                                   min-h-0"
                        >

                            {{-- ENCABEZADO TABLA --}}

                            <div
                                class="p-5
                                       border-b border-slate-100
                                       flex items-center
                                       justify-between
                                       gap-3
                                       flex-shrink-0"
                            >

                                <div>

                                    <h4
                                        class="font-bold
                                               text-slate-800"
                                    >
                                        Tabla de
                                        <span class="text-emerald-600">
                                            Amortización
                                        </span>
                                    </h4>

                                    <p
                                        class="text-xs
                                               text-slate-400
                                               mt-1"
                                    >
                                        {{ $numero_cuotas }}
                                        cuotas
                                        ·
                                        {{ ucfirst(strtolower($frecuencia_pago)) }}
                                    </p>

                                </div>


                                <div
                                    class="w-9 h-9
                                           rounded-xl
                                           bg-emerald-50
                                           text-emerald-600
                                           flex items-center
                                           justify-center"
                                >

                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 10h18M3 14h18M7 6h10M7 18h10"
                                        />
                                    </svg>

                                </div>

                            </div>


                            {{-- RESUMEN TABLA --}}

                            <div
                                class="grid
                                       grid-cols-2
                                       gap-3
                                       p-4
                                       border-b border-slate-100
                                       bg-slate-50
                                       flex-shrink-0"
                            >

                                @php

                                    $previewCuotas =
                                        max(1, (int) $numero_cuotas);

                                    $previewSaldoInicial =
                                        (float) ($saldo_inicial ?: 0);

                                    $previewCuota =
                                        round(
                                            $previewSaldoInicial /
                                            $previewCuotas,
                                            2
                                        );

                                @endphp


                                <div>

                                    <p
                                        class="text-[10px]
                                               uppercase
                                               tracking-wide
                                               text-slate-400"
                                    >
                                        Total financiado
                                    </p>

                                    <p
                                        class="text-sm
                                               font-bold
                                               text-slate-800
                                               mt-0.5"
                                    >
                                        ${{ number_format($previewSaldoInicial, 2) }}
                                    </p>

                                </div>


                                <div>

                                    <p
                                        class="text-[10px]
                                               uppercase
                                               tracking-wide
                                               text-slate-400"
                                    >
                                        Pago aproximado
                                    </p>

                                    <p
                                        class="text-sm
                                               font-bold
                                               text-emerald-600
                                               mt-0.5"
                                    >
                                        ${{ number_format($previewCuota, 2) }}
                                    </p>

                                </div>

                            </div>


                            {{-- TABLA CON ALTURA FIJA --}}

                            <div
                                class="h-[390px]
                                       min-h-0
                                       overflow-y-auto
                                       overflow-x-auto
                                       flex-shrink-0"
                            >

                                <table
                                    class="w-full
                                           min-w-[560px]
                                           text-left
                                           border-collapse
                                           text-xs
                                           table-fixed"
                                >

                                    <thead
                                        class="sticky
                                               top-0
                                               z-10
                                               bg-slate-50
                                               border-b
                                               border-slate-200
                                               text-slate-500
                                               uppercase
                                               tracking-wide
                                               text-[9px]
                                               font-bold"
                                    >

                                        <tr>

                                            <th
                                                class="p-3
                                                       w-14"
                                            >
                                                #
                                            </th>

                                            <th
                                                class="p-3
                                                       w-32"
                                            >
                                                Vencimiento
                                            </th>

                                            <th
                                                class="p-3
                                                       text-right"
                                            >
                                                Monto
                                            </th>

                                            <th
                                                class="p-3
                                                       text-right"
                                            >
                                                Saldo
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody
                                        class="divide-y
                                               divide-slate-100"
                                    >

                                        @php

                                            $previewSaldo =
                                                $previewSaldoInicial;

                                            $previewFecha =
                                                \Carbon\Carbon::parse(
                                                    $fecha_inicio ?: now()->format('Y-m-d')
                                                );

                                        @endphp


                                        @for(
                                            $i = 1;
                                            $i <= min($previewCuotas, 360);
                                            $i++
                                        )

                                            @php

                                                $fechaVencimiento =
                                                    match ($frecuencia_pago) {

                                                        'SEMANAL' =>
                                                            (clone $previewFecha)
                                                                ->addWeeks($i),

                                                        'QUINCENAL' =>
                                                            (clone $previewFecha)
                                                                ->addDays($i * 15),

                                                        'MENSUAL' =>
                                                            (clone $previewFecha)
                                                                ->addMonthsNoOverflow($i),

                                                        'ANUAL' =>
                                                            (clone $previewFecha)
                                                                ->addYears($i),

                                                    };


                                                if ($i === $previewCuotas) {

                                                    $montoPreview =
                                                        $previewSaldo;

                                                    $saldoPreview =
                                                        0;

                                                } else {

                                                    $montoPreview =
                                                        $previewCuota;

                                                    $saldoPreview =
                                                        max(
                                                            0,
                                                            round(
                                                                $previewSaldo -
                                                                $previewCuota,
                                                                2
                                                            )
                                                        );

                                                }

                                                $previewSaldo =
                                                    $saldoPreview;

                                            @endphp


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
                                                        $i,
                                                        2,
                                                        '0',
                                                        STR_PAD_LEFT
                                                    ) }}
                                                </td>


                                                <td
                                                    class="p-3
                                                           text-slate-600
                                                           whitespace-nowrap"
                                                >
                                                    {{ $fechaVencimiento->format('d/m/Y') }}
                                                </td>


                                                <td
                                                    class="p-3
                                                           text-right
                                                           font-semibold
                                                           text-slate-800"
                                                >
                                                    ${{ number_format(
                                                        $montoPreview,
                                                        2
                                                    ) }}
                                                </td>


                                                <td
                                                    class="p-3
                                                           text-right
                                                           text-slate-500"
                                                >
                                                    ${{ number_format(
                                                        $saldoPreview,
                                                        2
                                                    ) }}
                                                </td>

                                            </tr>

                                        @endfor

                                    </tbody>

                                </table>

                            </div>


                            {{-- NOTA --}}

                            <div
                                class="px-4
                                       py-3
                                       border-t border-slate-100
                                       bg-slate-50
                                       flex-shrink-0"
                            >

                                <div class="flex items-start gap-2">

                                    <svg
                                        class="w-4 h-4
                                               text-blue-500
                                               mt-0.5
                                               flex-shrink-0"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z"
                                        />
                                    </svg>

                                    <p
                                        class="text-[10px]
                                               leading-relaxed
                                               text-slate-500"
                                    >
                                        La última cuota absorbe cualquier diferencia de redondeo
                                        para que el saldo final quede exactamente en $0.00.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     BOTONES
                ================================================== --}}

                <div
                    class="px-5 py-4
                           border-t border-slate-100
                           bg-white
                           flex
                           justify-end
                           gap-3
                           flex-shrink-0"
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

                        <span class="inline-flex items-center gap-2">

                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 3v18m9-9H3"
                                />
                            </svg>

                            Formalizar contrato

                        </span>

                    </button>

                </div>

            </form>

        </div>

    </div>

@endif