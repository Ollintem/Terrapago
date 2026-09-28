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