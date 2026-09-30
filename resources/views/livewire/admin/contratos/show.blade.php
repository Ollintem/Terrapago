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

            {{-- =================================================
                 ENCABEZADO
            ================================================== --}}

            <div
                class="p-5
                       border-b border-slate-100
                       flex justify-between
                       items-start
                       flex-shrink-0"
            >

                <div>

                    <p
                        class="text-[10px]
                               uppercase
                               tracking-widest
                               font-bold
                               text-emerald-600
                               mb-1"
                    >
                        Información del contrato
                    </p>


                    <h3
                        class="text-xl
                               font-bold
                               text-slate-800"
                    >

                        Estado de

                        <span class="text-emerald-600">
                            Cuenta
                        </span>

                    </h3>


                    <p
                        class="text-xs
                               text-slate-500
                               mt-1"
                    >

                        Folio:

                        <span
                            class="font-semibold
                                   text-slate-700"
                        >
                            {{ $contratoSeleccionado->folio }}
                        </span>

                        <span class="mx-1">
                            ·
                        </span>

                        Cliente:

                        <span
                            class="font-semibold
                                   text-slate-700"
                        >
                            {{ $contratoSeleccionado->cliente->nombre_completo ?? 'Sin cliente' }}
                        </span>

                    </p>

                </div>


                <button
                    type="button"
                    wire:click="cerrarModalDetalle"
                    class="w-9 h-9
                           rounded-lg
                           bg-slate-100
                           hover:bg-slate-200
                           text-slate-500
                           hover:text-slate-700
                           cursor-pointer
                           transition
                           flex
                           items-center
                           justify-center"
                    title="Cerrar"
                >
                    ✕
                </button>

            </div>


            {{-- =================================================
                 RESUMEN FINANCIERO
            ================================================== --}}

            <div
                class="p-5
                       flex-shrink-0"
            >

                <div
                    class="grid
                           grid-cols-2
                           md:grid-cols-4
                           gap-3"
                >

                    {{-- PRECIO TOTAL --}}

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
                                   text-slate-400
                                   font-semibold"
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


                    {{-- ENGANCHE --}}

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
                                   text-emerald-600
                                   font-semibold"
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


                    {{-- FINANCIADO --}}

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
                                   text-blue-600
                                   font-semibold"
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


                    {{-- SALDO PENDIENTE --}}

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
                                   text-rose-600
                                   font-semibold"
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


            {{-- =================================================
                 TABLA DE AMORTIZACIÓN
            ================================================== --}}

            <div
                class="overflow-y-auto
                       flex-1
                       min-h-0
                       mx-5
                       border border-slate-200
                       rounded-xl"
            >

                <table
                    class="w-full
                           min-w-[760px]
                           text-left
                           border-collapse
                           text-xs"
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


                    <tbody
                        class="divide-y
                               divide-slate-100"
                    >

                        @forelse(
                            $contratoSeleccionado->cuotas
                            as $cuota
                        )

                            <tr
                                class="hover:bg-slate-50
                                       transition"
                            >

                                {{-- CUOTA --}}

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


                                {{-- FECHA --}}

                                <td
                                    class="p-3
                                           text-slate-600"
                                >

                                    @if($cuota->fecha_vencimiento)

                                        {{ \Carbon\Carbon::parse(
                                            $cuota->fecha_vencimiento
                                        )->format('d/m/Y') }}

                                    @else

                                        Sin fecha

                                    @endif

                                </td>


                                {{-- MONTO --}}

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


                                {{-- SALDO ANTERIOR --}}

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


                                {{-- SALDO RESTANTE --}}

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


                                {{-- ESTADO --}}

                                <td
                                    class="p-3
                                           text-center"
                                >

                                    @if($cuota->estado === 'PAGADA')

                                        <span
                                            class="inline-flex
                                                   items-center
                                                   px-2.5
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
                                            class="inline-flex
                                                   items-center
                                                   px-2.5
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
                                            class="inline-flex
                                                   items-center
                                                   px-2.5
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

                                    No hay cuotas registradas
                                    para este contrato.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- =================================================
                 PIE DEL MODAL
            ================================================== --}}

            <div
                class="p-5
                       mt-4
                       border-t border-slate-100
                       flex
                       flex-col
                       sm:flex-row
                       justify-between
                       items-center
                       gap-3
                       flex-shrink-0"
            >

                {{-- ACCIONES DE CONTRATO --}}

                <div
                    class="flex
                           flex-wrap
                           items-center
                           gap-2"
                >

                    @if($contratoSeleccionado->estado === 'ACTIVO')

                        @if(
                            (
                                auth()->user()->rol &&
                                in_array(
                                    strtolower(auth()->user()->rol->nombre),
                                    [
                                        'administrador',
                                        'super admin',
                                        'superadministrador'
                                    ]
                                )
                            )
                            ||
                            auth()->user()->permisos()
                                ->whereHas(
                                    'modulo',
                                    fn($q) => $q->where(
                                        'clave',
                                        'contratos'
                                    )
                                )
                                ->where('editar', true)
                                ->exists()
                        )

                            {{-- LIQUIDAR --}}

                            <button
                                type="button"
                                wire:click="liquidarContrato({{ $contratoSeleccionado->id }})"
                                wire:confirm="¿Confirmas la liquidación total de la deuda por ${{ number_format($contratoSeleccionado->saldo_actual, 2) }}?"
                                class="inline-flex
                                       items-center
                                       justify-center
                                       gap-2
                                       px-4
                                       py-2.5
                                       bg-emerald-600
                                       hover:bg-emerald-700
                                       text-white
                                       rounded-xl
                                       text-xs
                                       font-semibold
                                       transition
                                       cursor-pointer
                                       shadow-sm
                                       shadow-emerald-600/20"
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
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>

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


                    {{-- =================================================
                         IMPRIMIR CONTRATO
                    ================================================== --}}

                    <a
                        href="{{ route(
                            'contratos.pdf',
                            $contratoSeleccionado->id
                        ) }}"
                        target="_blank"
                        class="inline-flex
                               items-center
                               justify-center
                               gap-2
                               px-4
                               py-2.5
                               bg-blue-600
                               hover:bg-blue-700
                               text-white
                               rounded-xl
                               text-xs
                               font-semibold
                               transition
                               cursor-pointer
                               shadow-sm
                               shadow-blue-600/20"
                        title="Generar contrato en PDF"
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
                                d="M6 9V3h12v6M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v7H6v-7Z"
                            />
                        </svg>

                        Imprimir contrato

                    </a>

                </div>


                {{-- CERRAR --}}

                <button
                    type="button"
                    wire:click="cerrarModalDetalle"
                    class="inline-flex
                           items-center
                           justify-center
                           gap-2
                           px-5
                           py-2.5
                           bg-slate-800
                           hover:bg-slate-900
                           text-white
                           rounded-xl
                           text-xs
                           font-semibold
                           transition
                           cursor-pointer
                           whitespace-nowrap"
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
                            d="M6 18 18 6M6 6l12 12"
                        />
                    </svg>

                    Cerrar estado de cuenta

                </button>

            </div>

        </div>

    </div>

@endif