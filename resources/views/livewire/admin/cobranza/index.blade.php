<div class="p-6 max-w-7xl mx-auto space-y-6">

    {{-- ========================================================= --}}
    {{-- ENCABEZADO --}}
    {{-- ========================================================= --}}

    <div>
        <p class="text-sm text-slate-500">
            Operaciones / Caja
        </p>

        <h1 class="text-2xl font-bold text-slate-800">
            Cobranza
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Registra pagos, consulta saldos, cuotas y administra los cobros de los contratos.
        </p>
    </div>


    {{-- ========================================================= --}}
    {{-- MENSAJES --}}
    {{-- ========================================================= --}}

    @if (session()->has('success'))

        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-700">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                <div class="flex items-start gap-3">

                    <div class="w-9 h-9 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">

                        <svg
                            class="w-5 h-5 text-emerald-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>

                    </div>

                    <div>

                        <p class="font-semibold text-emerald-800">
                            Operación registrada correctamente
                        </p>

                        <p class="mt-1">
                            {{ session('success') }}
                        </p>

                    </div>

                </div>


                @if ($ultimaOperacionFolio)

                    <div class="flex flex-wrap gap-2">

                        {{-- ================================================= --}}
                        {{-- VER RECIBO --}}
                        {{-- ================================================= --}}

                        <button
                            type="button"
                            onclick="abrirDocumentoCobranza(
                                '{{ route('caja.recibo.ver', $ultimaOperacionFolio) }}',
                                'Recibo de pago'
                            )"
                            class="inline-flex items-center justify-center gap-2
                                   px-4 py-2.5 rounded-lg
                                   bg-slate-800 text-white
                                   font-semibold text-sm
                                   hover:bg-slate-900
                                   transition shadow-sm whitespace-nowrap"
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
                                    d="M2.25 12s3.5-6 9.75-6 9.75 6 9.75 6-3.5 6-9.75 6-9.75-6-9.75-6Z"
                                />
                                <circle
                                    cx="12"
                                    cy="12"
                                    r="2.5"
                                    stroke-width="2"
                                />
                            </svg>

                            Ver recibo

                        </button>


                        {{-- ================================================= --}}
                        {{-- PDF --}}
                        {{-- ================================================= --}}

                        <button
                            type="button"
                            onclick="abrirDocumentoCobranza(
                                '{{ route('caja.recibo', $ultimaOperacionFolio) }}',
                                'Recibo de pago - PDF'
                            )"
                            class="inline-flex items-center justify-center gap-2
                                   px-4 py-2.5 rounded-lg
                                   bg-emerald-600 text-white
                                   font-semibold text-sm
                                   hover:bg-emerald-700
                                   transition shadow-sm whitespace-nowrap"
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
                                    d="M12 10v6m0 0 3-3m-3 3-3-3m-5 5h16M5 3h9l5 5v13H5V3Z"
                                />
                            </svg>

                            Generar recibo PDF

                        </button>

                    </div>

                @endif

            </div>

        </div>

    @endif


    @if (session()->has('error'))

        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">

            {{ session('error') }}

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- BUSCADOR --}}
    {{-- ========================================================= --}}

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">

        <div class="mb-4">

            <h2 class="text-lg font-semibold text-slate-800">
                Buscar cuenta
            </h2>

            <p class="text-sm text-slate-500">
                Busca en tiempo real por folio, nombre del cliente, teléfono, correo, manzana o lote.
            </p>

        </div>


        <div class="relative">

            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar por folio, cliente, teléfono, correo o lote..."
                class="w-full rounded-xl border border-slate-300 px-4 py-3 pl-11 text-sm
                       focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100
                       outline-none transition"
            >

            <svg
                class="absolute left-4 top-3.5 w-5 h-5 text-slate-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"
                />
            </svg>

        </div>


        {{-- RESULTADOS --}}

        @if (!empty($contratosEncontrados))

            <div class="mt-5 overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>

                        <tr class="border-b border-slate-200 text-left text-slate-500">

                            <th class="px-4 py-3 font-semibold">
                                Folio
                            </th>

                            <th class="px-4 py-3 font-semibold">
                                Cliente
                            </th>

                            <th class="px-4 py-3 font-semibold">
                                Terreno
                            </th>

                            <th class="px-4 py-3 font-semibold">
                                Saldo deudor
                            </th>

                            <th class="px-4 py-3 font-semibold">
                                Estado
                            </th>

                            <th class="px-4 py-3 font-semibold text-right">
                                Acción
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @foreach ($contratosEncontrados as $contrato)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-4 py-3 font-semibold text-slate-800">
                                    {{ $contrato->folio }}
                                </td>

                                <td class="px-4 py-3 text-slate-600">

                                    {{ $contrato->cliente->nombre ?? 'Sin cliente' }}
                                    {{ $contrato->cliente->apellidos ?? '' }}

                                </td>

                                <td class="px-4 py-3 text-slate-600">

                                    @if ($contrato->terreno)

                                        Manzana {{ $contrato->terreno->manzana ?? '-' }},
                                        Lote {{ $contrato->terreno->lote ?? '-' }}

                                    @else

                                        Sin terreno

                                    @endif

                                </td>

                                <td class="px-4 py-3 font-semibold text-slate-800">
                                    ${{ number_format($contrato->saldo_actual, 2) }}
                                </td>

                                <td class="px-4 py-3">

                                    @if ($contrato->estado === 'LIQUIDADO')

                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full
                                                     bg-emerald-100 text-emerald-700 text-xs font-semibold">
                                            Liquidado
                                        </span>

                                    @elseif ($contrato->estado === 'CANCELADO')

                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full
                                                     bg-slate-200 text-slate-700 text-xs font-semibold">
                                            Cancelado
                                        </span>

                                    @else

                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full
                                                     bg-blue-100 text-blue-700 text-xs font-semibold">
                                            Activo
                                        </span>

                                    @endif

                                </td>

                                <td class="px-4 py-3 text-right">

                                    <button
                                        type="button"
                                        wire:click="seleccionarContrato({{ $contrato->id }})"
                                        class="inline-flex items-center px-3 py-2 rounded-lg
                                               bg-slate-800 text-white text-xs font-semibold
                                               hover:bg-slate-700 transition"
                                    >
                                        Seleccionar
                                    </button>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @elseif(strlen($search) >= 2)

            <div class="mt-5 rounded-xl bg-slate-50 border border-slate-200 p-5 text-center">

                <p class="text-sm text-slate-500">
                    No se encontraron contratos con esa búsqueda.
                </p>

            </div>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- CONTRATO SELECCIONADO --}}
    {{-- ========================================================= --}}

    @if ($contratoSeleccionado)

        @php

            $hoy = now()->startOfDay();

            $cuotasPendientes = $contratoSeleccionado->cuotas
                ->filter(function ($cuota) {
                    return in_array(
                        $cuota->estado,
                        ['PENDIENTE', 'VENCIDA']
                    );
                });

            $montoVencido = $cuotasPendientes
                ->filter(function ($cuota) use ($hoy) {
                    return $cuota->fecha_vencimiento
                        && $cuota->fecha_vencimiento->lt($hoy);
                })
                ->sum(function ($cuota) {
                    return max(
                        0,
                        (float) $cuota->monto -
                        (float) $cuota->monto_pagado
                    );
                });

            $proximaCuota = $cuotasPendientes
                ->filter(function ($cuota) {
                    return $cuota->fecha_vencimiento;
                })
                ->sortBy('fecha_vencimiento')
                ->first();

            $saldoDeudor = max(
                0,
                (float) $contratoSeleccionado->saldo_actual
            );

        @endphp


        <div class="space-y-6">

            {{-- ================================================= --}}
            {{-- INFORMACIÓN DEL CONTRATO --}}
            {{-- ================================================= --}}

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

                <div class="p-5 border-b border-slate-200">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <div>

                            <div class="flex items-center gap-3 flex-wrap">

                                <div>

                                    <p class="text-sm text-slate-500">
                                        Contrato seleccionado
                                    </p>

                                    <h2 class="text-xl font-bold text-slate-800">
                                        {{ $contratoSeleccionado->folio }}
                                    </h2>

                                </div>


                                @if ($contratoSeleccionado->estado === 'LIQUIDADO')

                                    <span class="inline-flex items-center px-3 py-1 rounded-full
                                                 bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        LIQUIDADO
                                    </span>

                                @elseif ($contratoSeleccionado->estado === 'CANCELADO')

                                    <span class="inline-flex items-center px-3 py-1 rounded-full
                                                 bg-slate-200 text-slate-700 text-xs font-bold">
                                        CANCELADO
                                    </span>

                                @else

                                    <span class="inline-flex items-center px-3 py-1 rounded-full
                                                 bg-blue-100 text-blue-700 text-xs font-bold">
                                        ACTIVO
                                    </span>

                                @endif

                            </div>


                            <p class="text-sm text-slate-500 mt-1">

                                {{ $contratoSeleccionado->cliente->nombre ?? 'Sin cliente' }}
                                {{ $contratoSeleccionado->cliente->apellidos ?? '' }}

                            </p>


                            @if ($contratoSeleccionado->terreno)

                                <p class="text-sm text-slate-500 mt-1">

                                    Manzana {{ $contratoSeleccionado->terreno->manzana ?? '-' }},
                                    Lote {{ $contratoSeleccionado->terreno->lote ?? '-' }}

                                </p>

                            @endif

                        </div>


                        @if (
                            $contratoSeleccionado->estado !== 'LIQUIDADO' &&
                            $contratoSeleccionado->estado !== 'CANCELADO' &&
                            $saldoDeudor > 0
                        )

                            <button
                                type="button"
                                wire:click="abrirCobro"
                                class="inline-flex items-center justify-center px-5 py-3 rounded-xl
                                       bg-emerald-600 text-white font-semibold
                                       hover:bg-emerald-700 transition shadow-sm"
                            >
                                Registrar cobro
                            </button>

                        @endif

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- RESUMEN FINANCIERO --}}
                {{-- ================================================= --}}

                <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-slate-200">

                    <div class="p-5">

                        <div class="flex items-center justify-between gap-3">

                            <div>

                                <p class="text-xs uppercase tracking-wide text-slate-400">
                                    Saldo deudor total
                                </p>

                                <p class="text-2xl font-bold text-emerald-600 mt-1">
                                    ${{ number_format($saldoDeudor, 2) }}
                                </p>

                            </div>


                            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">

                                <svg
                                    class="w-5 h-5 text-emerald-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8c-2.21 0-4 1.12-4 2.5S9.79 13 12 13s4 1.12 4 2.5S14.21 18 12 18m0-10V6m0 12v-2M5 12a7 7 0 1 0 14 0 7 7 0 0 0-14 0Z"
                                    />
                                </svg>

                            </div>

                        </div>

                        <p class="text-xs text-slate-500 mt-2">
                            Saldo pendiente del contrato.
                        </p>

                    </div>


                    <div class="p-5">

                        <div class="flex items-center justify-between gap-3">

                            <div>

                                <p class="text-xs uppercase tracking-wide text-slate-400">
                                    Monto vencido
                                </p>

                                <p class="text-2xl font-bold
                                    {{ $montoVencido > 0 ? 'text-rose-600' : 'text-slate-800' }}
                                    mt-1"
                                >
                                    ${{ number_format($montoVencido, 2) }}
                                </p>

                            </div>


                            <div class="w-10 h-10 rounded-xl
                                {{ $montoVencido > 0 ? 'bg-rose-100' : 'bg-slate-100' }}
                                flex items-center justify-center"
                            >

                                <svg
                                    class="w-5 h-5 {{ $montoVencido > 0 ? 'text-rose-600' : 'text-slate-500' }}"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v4m0 4h.01M10.29 3.86 2.82 17a2 2 0 0 0 1.74 3h14.88a2 2 0 0 0 1.74-3L13.71 3.86a2 2 0 0 0-3.42 0Z"
                                    />
                                </svg>

                            </div>

                        </div>

                        @if ($montoVencido > 0)

                            <p class="text-xs text-rose-600 mt-2">
                                Existe saldo de cuotas vencidas.
                            </p>

                        @else

                            <p class="text-xs text-slate-500 mt-2">
                                No tiene monto vencido.
                            </p>

                        @endif

                    </div>


                    <div class="p-5">

                        <div class="flex items-center justify-between gap-3">

                            <div>

                                <p class="text-xs uppercase tracking-wide text-slate-400">
                                    Próxima fecha de pago
                                </p>


                                @if ($proximaCuota)

                                    <p class="text-2xl font-bold text-slate-800 mt-1">
                                        {{ optional($proximaCuota->fecha_vencimiento)->format('d/m/Y') }}
                                    </p>

                                    <p class="text-xs text-slate-500 mt-1">

                                        Cuota #{{ $proximaCuota->numero_cuota }}
                                        · Pendiente:
                                        ${{ number_format(
                                            max(
                                                0,
                                                (float) $proximaCuota->monto -
                                                (float) $proximaCuota->monto_pagado
                                            ),
                                            2
                                        ) }}

                                    </p>

                                @else

                                    <p class="text-lg font-bold text-slate-800 mt-1">
                                        Sin pagos pendientes
                                    </p>

                                    <p class="text-xs text-slate-500 mt-1">
                                        El contrato no tiene cuotas pendientes.
                                    </p>

                                @endif

                            </div>


                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">

                                <svg
                                    class="w-5 h-5 text-blue-600"
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

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- INFORMACIÓN GENERAL --}}
                {{-- ================================================= --}}

                <div class="grid grid-cols-1 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-slate-200 border-t border-slate-200">

                    <div class="p-5">

                        <p class="text-xs uppercase tracking-wide text-slate-400">
                            Precio total
                        </p>

                        <p class="text-lg font-bold text-slate-800 mt-1">
                            ${{ number_format($contratoSeleccionado->precio_total, 2) }}
                        </p>

                    </div>


                    <div class="p-5">

                        <p class="text-xs uppercase tracking-wide text-slate-400">
                            Enganche
                        </p>

                        <p class="text-lg font-bold text-slate-800 mt-1">
                            ${{ number_format($contratoSeleccionado->enganche, 2) }}
                        </p>

                    </div>


                    <div class="p-5">

                        <p class="text-xs uppercase tracking-wide text-slate-400">
                            Número de cuotas
                        </p>

                        <p class="text-lg font-bold text-slate-800 mt-1">
                            {{ $contratoSeleccionado->numero_cuotas }}
                        </p>

                    </div>


                    <div class="p-5">

                        <p class="text-xs uppercase tracking-wide text-slate-400">
                            Frecuencia
                        </p>

                        <p class="text-lg font-bold text-slate-800 mt-1">
                            {{ $contratoSeleccionado->frecuencia_pago }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- TABLA DE CUOTAS --}}
            {{-- ================================================= --}}

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

                <div class="p-5 border-b border-slate-200">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

                        <div>

                            <h2 class="text-lg font-semibold text-slate-800">
                                Cuotas del contrato
                            </h2>

                            <p class="text-sm text-slate-500 mt-1">
                                Consulta el estado de las cuotas y los pagos realizados.
                            </p>

                        </div>


                        <div class="text-sm text-slate-500">

                            Cuotas pendientes:

                            <strong class="text-slate-800">
                                {{ $cuotasPendientes->count() }}
                            </strong>

                        </div>

                    </div>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-slate-50">

                            <tr class="border-b border-slate-200 text-left">

                                <th class="px-5 py-3 font-semibold text-slate-600">
                                    Cuota
                                </th>

                                <th class="px-5 py-3 font-semibold text-slate-600">
                                    Vencimiento
                                </th>

                                <th class="px-5 py-3 font-semibold text-slate-600">
                                    Monto
                                </th>

                                <th class="px-5 py-3 font-semibold text-slate-600">
                                    Pagado
                                </th>

                                <th class="px-5 py-3 font-semibold text-slate-600">
                                    Pendiente
                                </th>

                                <th class="px-5 py-3 font-semibold text-slate-600">
                                    Estado
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @forelse ($contratoSeleccionado->cuotas as $cuota)

                                @php

                                    $pendiente = max(
                                        0,
                                        (float) $cuota->monto -
                                        (float) $cuota->monto_pagado
                                    );

                                @endphp


                                <tr class="hover:bg-slate-50 transition">

                                    <td class="px-5 py-4 font-semibold text-slate-800">
                                        #{{ $cuota->numero_cuota }}
                                    </td>

                                    <td class="px-5 py-4 text-slate-600">
                                        {{ optional($cuota->fecha_vencimiento)->format('d/m/Y') }}
                                    </td>

                                    <td class="px-5 py-4 text-slate-700">
                                        ${{ number_format($cuota->monto, 2) }}
                                    </td>

                                    <td class="px-5 py-4 text-slate-700">
                                        ${{ number_format($cuota->monto_pagado, 2) }}
                                    </td>

                                    <td class="px-5 py-4 font-semibold text-slate-800">
                                        ${{ number_format($pendiente, 2) }}
                                    </td>

                                    <td class="px-5 py-4">

                                        @if ($cuota->estado === 'PAGADA')

                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full
                                                         bg-emerald-100 text-emerald-700 text-xs font-semibold">
                                                Pagada
                                            </span>

                                        @elseif ($cuota->estado === 'VENCIDA')

                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full
                                                         bg-rose-100 text-rose-700 text-xs font-semibold">
                                                Vencida
                                            </span>

                                        @else

                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full
                                                         bg-amber-100 text-amber-700 text-xs font-semibold">
                                                Pendiente
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="6"
                                        class="px-5 py-8 text-center text-sm text-slate-500"
                                    >
                                        Este contrato no tiene cuotas registradas.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- MODAL DE COBRO --}}
    {{-- ========================================================= --}}

    @if ($modalCobroAbierto)

        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4"
            wire:keydown.escape="cerrarCobro"
        >

            <div
                class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
                wire:click="cerrarCobro"
            ></div>


            <div
                class="relative w-full max-w-xl max-h-[92vh] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col"
            >

                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between flex-shrink-0">

                    <div>

                        <p class="text-sm text-slate-500">
                            Registrar operación
                        </p>

                        <h2 class="text-xl font-bold text-slate-800">
                            Registrar cobro
                        </h2>

                    </div>


                    <button
                        type="button"
                        wire:click="cerrarCobro"
                        class="w-8 h-8 rounded-lg text-slate-400
                               hover:bg-slate-100 hover:text-slate-700 transition"
                    >
                        ✕
                    </button>

                </div>


                <form
                    wire:submit.prevent="registrarCobro"
                    class="p-4 sm:p-5 space-y-4 overflow-y-auto"
                >

                    @if ($contratoSeleccionado)

                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">

                                <div>

                                    <p class="text-xs uppercase tracking-wide text-slate-400">
                                        Contrato
                                    </p>

                                    <p class="font-bold text-slate-800">
                                        {{ $contratoSeleccionado->folio }}
                                    </p>

                                    <p class="text-sm text-slate-500">

                                        {{ $contratoSeleccionado->cliente->nombre ?? '' }}
                                        {{ $contratoSeleccionado->cliente->apellidos ?? '' }}

                                    </p>

                                </div>


                                <div class="text-left sm:text-right">

                                    <p class="text-xs uppercase tracking-wide text-slate-400">
                                        Saldo deudor
                                    </p>

                                    <p class="text-lg font-bold text-emerald-600">
                                        ${{ number_format($contratoSeleccionado->saldo_actual, 2) }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    @endif


                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Aplicación del pago
                        </label>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">

                            <label
                                class="relative flex cursor-pointer rounded-xl border-2 p-4 transition
                                    {{ $modo_pago === 'NORMAL'
                                        ? 'border-emerald-500 bg-emerald-50 ring-2 ring-emerald-100'
                                        : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50'
                                    }}"
                            >

                                <input
                                    type="radio"
                                    wire:model.live="modo_pago"
                                    value="NORMAL"
                                    class="peer sr-only"
                                >

                                <div class="w-full pr-8">

                                    <div class="absolute right-3 top-3 flex h-5 w-5 items-center justify-center rounded-full border-2
                                        {{ $modo_pago === 'NORMAL'
                                            ? 'border-emerald-500 bg-emerald-500'
                                            : 'border-slate-300 bg-white'
                                        }}">

                                        @if ($modo_pago === 'NORMAL')
                                            <span class="h-2 w-2 rounded-full bg-white"></span>
                                        @endif

                                    </div>

                                    <p class="font-semibold text-slate-800">
                                        Pagar cuota
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-slate-500">
                                        Permite pagar parcial o totalmente una sola cuota vencida o actual.
                                    </p>

                                </div>

                            </label>


                            <label
                                class="relative flex cursor-pointer rounded-xl border-2 p-4 transition
                                    {{ $modo_pago === 'ADELANTO'
                                        ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-100'
                                        : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50'
                                    }}"
                            >

                                <input
                                    type="radio"
                                    wire:model.live="modo_pago"
                                    value="ADELANTO"
                                    class="peer sr-only"
                                >

                                <div class="w-full pr-8">

                                    <div class="absolute right-3 top-3 flex h-5 w-5 items-center justify-center rounded-full border-2
                                        {{ $modo_pago === 'ADELANTO'
                                            ? 'border-blue-500 bg-blue-500'
                                            : 'border-slate-300 bg-white'
                                        }}">

                                        @if ($modo_pago === 'ADELANTO')
                                            <span class="h-2 w-2 rounded-full bg-white"></span>
                                        @endif

                                    </div>

                                    <p class="font-semibold text-slate-800">
                                        Adelantar pago
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-slate-500">
                                        Permite distribuir el monto entre varias cuotas futuras.
                                    </p>

                                </div>

                            </label>

                        </div>


                        @error('modo_pago')

                            <p class="mt-1 text-sm text-rose-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    @if ($contratoSeleccionado)

                        @php

                            $cuotaParaPago = $contratoSeleccionado->cuotas
                                ->filter(function ($cuota) {
                                    return in_array(
                                        $cuota->estado,
                                        ['PENDIENTE', 'VENCIDA']
                                    )
                                    && $cuota->fecha_vencimiento
                                    && $cuota->fecha_vencimiento->lte(
                                        now()->startOfDay()
                                    );
                                })
                                ->sortBy('numero_cuota')
                                ->first();

                            $saldoCuotaParaPago = $cuotaParaPago
                                ? max(
                                    0,
                                    (float) $cuotaParaPago->monto -
                                    (float) $cuotaParaPago->monto_pagado
                                )
                                : 0;

                        @endphp


                        @if ($modo_pago === 'NORMAL')

                            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2.5">

                                @if ($cuotaParaPago && $saldoCuotaParaPago > 0)

                                    <p class="text-sm font-semibold text-emerald-800">
                                        Cuota #{{ $cuotaParaPago->numero_cuota }}
                                    </p>

                                    <p class="text-xs text-emerald-700 mt-1">

                                        Saldo pendiente de esta cuota:

                                        <strong>
                                            ${{ number_format($saldoCuotaParaPago, 2) }}
                                        </strong>

                                    </p>

                                    <p class="text-xs text-emerald-700 mt-1">

                                        Puedes realizar un abono parcial o liquidar esta cuota,
                                        pero no puedes exceder este monto.

                                    </p>

                                @else

                                    <p class="text-sm font-semibold text-emerald-800">
                                        No hay una cuota vencida o actual disponible.
                                    </p>

                                    <p class="text-xs text-emerald-700 mt-1">

                                        Para pagar cuotas futuras utiliza la opción
                                        <strong>Adelantar pago</strong>.

                                    </p>

                                @endif

                            </div>

                        @else

                            <div class="rounded-xl border border-blue-200 bg-blue-50 px-3 py-2.5">

                                <p class="text-sm font-semibold text-blue-800">
                                    Adelanto de pago
                                </p>

                                <p class="text-xs text-blue-700 mt-1">

                                    El monto podrá distribuirse entre varias cuotas pendientes,
                                    comenzando por la cuota más próxima.

                                </p>

                                <p class="text-xs text-blue-700 mt-1">

                                    El máximo permitido es el saldo total del contrato:

                                    <strong>
                                        ${{ number_format($contratoSeleccionado->saldo_actual, 2) }}
                                    </strong>

                                </p>

                            </div>

                        @endif

                    @endif


                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Monto a cobrar
                        </label>


                        <div class="relative">

                            <span class="absolute left-4 top-3.5 text-slate-400 font-semibold">
                                $
                            </span>


                            <input
                                type="number"
                                step="0.01"
                                min="0.01"
                                wire:model.live="monto"
                                placeholder="0.00"
                                onwheel="event.preventDefault(); this.blur();"
                                onkeydown="if (event.key === 'ArrowUp' || event.key === 'ArrowDown') event.preventDefault();"
                                class="w-full rounded-xl border border-slate-300
                                       px-4 py-2.5 pl-9 text-sm
                                       focus:border-emerald-500
                                       focus:ring-2 focus:ring-emerald-100
                                       outline-none transition"
                            >

                        </div>


                        @error('monto')

                            <p class="mt-1 text-sm text-rose-600">
                                {{ $message }}
                            </p>

                        @enderror


                        @if ($contratoSeleccionado)

                            @if ($modo_pago === 'NORMAL' && isset($saldoCuotaParaPago))

                                <p class="mt-1 text-xs text-slate-500">

                                    Máximo para esta cuota:

                                    <strong>
                                        ${{ number_format($saldoCuotaParaPago, 2) }}
                                    </strong>

                                </p>

                            @else

                                <p class="mt-1 text-xs text-slate-500">

                                    Máximo permitido por el saldo del contrato:

                                    <strong>
                                        ${{ number_format($contratoSeleccionado->saldo_actual, 2) }}
                                    </strong>

                                </p>

                            @endif

                        @endif

                    </div>


                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Método de pago
                        </label>


                        <select
                            wire:model.live="metodo_pago"
                            class="w-full rounded-xl border border-slate-300
                                   px-4 py-2.5 text-sm bg-white
                                   focus:border-emerald-500
                                   focus:ring-2 focus:ring-emerald-100
                                   outline-none transition"
                        >

                            <option value="EFECTIVO">
                                Efectivo
                            </option>

                            <option value="TRANSFERENCIA">
                                Transferencia
                            </option>

                            <option value="DEPOSITO">
                                Depósito
                            </option>

                            <option value="TARJETA">
                                Tarjeta
                            </option>

                        </select>


                        @error('metodo_pago')

                            <p class="mt-1 text-sm text-rose-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Fecha y hora del pago
                        </label>


                        <input
                            type="datetime-local"
                            wire:model.live="fecha_pago"
                            class="w-full rounded-xl border border-slate-300
                                   px-4 py-3 text-sm
                                   focus:border-emerald-500
                                   focus:ring-2 focus:ring-emerald-100
                                   outline-none transition"
                        >


                        @error('fecha_pago')

                            <p class="mt-1 text-sm text-rose-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">

                            Referencia

                            <span class="text-slate-400 font-normal">
                                (opcional)
                            </span>

                        </label>


                        <input
                            type="text"
                            wire:model.live="referencia"
                            maxlength="100"
                            placeholder="Número de transferencia, depósito, etc."
                            class="w-full rounded-xl border border-slate-300
                                   px-4 py-3 text-sm
                                   focus:border-emerald-500
                                   focus:ring-2 focus:ring-emerald-100
                                   outline-none transition"
                        >


                        @error('referencia')

                            <p class="mt-1 text-sm text-rose-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">

                            Observaciones

                            <span class="text-slate-400 font-normal">
                                (opcional)
                            </span>

                        </label>


                        <textarea
                            wire:model.live="observaciones"
                            rows="2"
                            placeholder="Agrega alguna observación sobre el cobro..."
                            class="w-full rounded-xl border border-slate-300
                                   px-4 py-3 text-sm
                                   focus:border-emerald-500
                                   focus:ring-2 focus:ring-emerald-100
                                   outline-none transition resize-none"
                        ></textarea>


                        @error('observaciones')

                            <p class="mt-1 text-sm text-rose-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2">

                        <button
                            type="button"
                            wire:click="cerrarCobro"
                            class="px-4 py-2.5 rounded-xl border border-slate-300
                                   text-slate-700 font-semibold
                                   hover:bg-slate-50 transition"
                        >
                            Cancelar
                        </button>


                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="px-4 py-2.5 rounded-xl bg-emerald-600
                                   text-white font-semibold
                                   hover:bg-emerald-700
                                   disabled:opacity-50
                                   disabled:cursor-not-allowed
                                   transition"
                        >

                            <span
                                wire:loading.remove
                                wire:target="registrarCobro"
                            >
                                Registrar cobro
                            </span>


                            <span
                                wire:loading
                                wire:target="registrarCobro"
                            >
                                Registrando...
                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- VENTANA EMERGENTE PARA RECIBO / PDF --}}
    {{-- ========================================================= --}}

    <div
        id="documentoCobranzaModal"
        class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/70 backdrop-blur-sm p-2 sm:p-4"
    >

        {{-- CONTENEDOR --}}
        <div
            class="relative w-full h-full max-w-6xl max-h-[96vh]
                   bg-white rounded-2xl shadow-2xl overflow-hidden
                   flex flex-col"
        >

            {{-- ================================================= --}}
            {{-- CABECERA --}}
            {{-- ================================================= --}}

            <div
                class="flex items-center justify-between gap-4
                       px-4 sm:px-5 py-3
                       border-b border-slate-200
                       bg-white flex-shrink-0"
            >

                <div class="min-w-0">

                    <p class="text-xs uppercase tracking-wide text-slate-400">
                        TerraPago
                    </p>

                    <h2
                        id="documentoCobranzaTitulo"
                        class="text-base sm:text-lg font-bold text-slate-800 truncate"
                    >
                        Documento
                    </h2>

                </div>


                <button
                    type="button"
                    onclick="cerrarDocumentoCobranza()"
                    class="flex-shrink-0 inline-flex items-center justify-center
                           w-9 h-9 rounded-lg
                           text-slate-500
                           hover:bg-slate-100
                           hover:text-slate-800
                           transition"
                    aria-label="Cerrar documento"
                    title="Cerrar"
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
                            d="M6 6l12 12M18 6L6 18"
                        />
                    </svg>

                </button>

            </div>


            {{-- ================================================= --}}
            {{-- CONTENIDO DEL DOCUMENTO --}}
            {{-- ================================================= --}}

            <div class="relative flex-1 min-h-0 bg-slate-100">

                {{-- CARGANDO --}}

                <div
                    id="documentoCobranzaCargando"
                    class="absolute inset-0 z-10 flex items-center justify-center bg-white"
                >

                    <div class="text-center">

                        <div
                            class="mx-auto w-10 h-10 rounded-full
                                   border-4 border-slate-200
                                   border-t-emerald-600
                                   animate-spin"
                        ></div>

                        <p class="mt-4 text-sm font-semibold text-slate-700">
                            Cargando documento...
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            Espera un momento.
                        </p>

                    </div>

                </div>


                <iframe
                    id="documentoCobranzaFrame"
                    src="about:blank"
                    class="w-full h-full border-0 bg-white"
                    title="Documento de cobranza"
                ></iframe>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- JAVASCRIPT DE LA VENTANA EMERGENTE --}}
    {{-- ========================================================= --}}

    <script>
        function abrirDocumentoCobranza(url, titulo) {

            const modal = document.getElementById('documentoCobranzaModal');
            const frame = document.getElementById('documentoCobranzaFrame');
            const tituloElemento = document.getElementById('documentoCobranzaTitulo');
            const cargando = document.getElementById('documentoCobranzaCargando');

            if (!modal || !frame) {
                return;
            }

            tituloElemento.textContent = titulo || 'Documento';

            cargando.classList.remove('hidden');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');

            frame.onload = function () {

                cargando.classList.add('hidden');

            };

            frame.src = url;
        }


        function cerrarDocumentoCobranza() {

            const modal = document.getElementById('documentoCobranzaModal');
            const frame = document.getElementById('documentoCobranzaFrame');
            const cargando = document.getElementById('documentoCobranzaCargando');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.body.classList.remove('overflow-hidden');

            if (frame) {
                frame.src = 'about:blank';
            }

            if (cargando) {
                cargando.classList.remove('hidden');
            }
        }


        document.addEventListener('keydown', function (event) {

            if (event.key !== 'Escape') {
                return;
            }

            const modal = document.getElementById('documentoCobranzaModal');

            if (
                modal &&
                !modal.classList.contains('hidden')
            ) {

                cerrarDocumentoCobranza();

            }

        });
    </script>

</div>