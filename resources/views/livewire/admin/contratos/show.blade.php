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