<div class="p-6 max-w-[1600px] mx-auto space-y-6">

    {{-- ============================================================
         ENCABEZADO
    ============================================================ --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        <div>

            <div class="flex items-center gap-2 mb-2">

                <span class="h-2 w-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></span>

                <span class="text-[11px] font-bold uppercase tracking-[0.16em] text-emerald-600">
                    Operaciones
                </span>

            </div>

            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900">

                Gestión de

                <span class="text-emerald-600">
                    Contratos
                </span>

            </h1>

            <p class="mt-2 text-sm sm:text-base font-medium text-slate-500 max-w-xl">

                Administra los contratos y operaciones de venta de

                <span class="font-semibold text-slate-700">
                    terrenos
                </span>.

            </p>

        </div>


        {{-- ========================================================
             BOTÓN FORMALIZAR CONTRATO
        ========================================================= --}}
        @if(
            (auth()->user()->rol &&
            in_array(
                strtolower(auth()->user()->rol->nombre),
                ['administrador', 'super admin', 'superadministrador']
            ))
            ||
            auth()->user()->permisos()
                ->whereHas('modulo', fn($q) => $q->where('clave', 'contratos'))
                ->where('crear', true)
                ->exists()
        )

            <button
                wire:click="abrirModalCrear"
                class="inline-flex items-center justify-center gap-2
                       rounded-xl bg-emerald-600 px-5 py-3
                       text-sm font-bold text-white
                       shadow-sm shadow-emerald-600/20
                       transition hover:bg-emerald-700
                       focus:outline-none focus:ring-2
                       focus:ring-emerald-500 focus:ring-offset-2"
            >

                <span class="text-xl leading-none">
                    +
                </span>

                Formalizar Contrato

            </button>

        @endif

    </div>


    {{-- ============================================================
         MENSAJE DE ÉXITO
    ============================================================ --}}
    @if (session()->has('mensaje'))

        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition
            class="flex items-start gap-3 rounded-2xl
                   border border-emerald-200
                   bg-emerald-50 px-5 py-4
                   text-emerald-800"
        >

            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 text-emerald-600"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 13l4 4L19 7"
                    />
                </svg>

            </div>

            <div>

                <p class="font-bold">
                    Operación realizada
                </p>

                <p class="text-sm mt-0.5">
                    {{ session('mensaje') }}
                </p>

            </div>

        </div>

    @endif


    {{-- ============================================================
         MENSAJE DE ERROR
    ============================================================ --}}
    @if (session()->has('error'))

        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition
            class="flex items-start gap-3 rounded-2xl
                   border border-red-200
                   bg-red-50 px-5 py-4
                   text-red-800"
        >

            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 text-red-600"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v4m0 4h.01M10.29 3.86l-8.82 15a2 2 0 001.71 2.14h17.64a2 2 0 001.71-2.14l-8.82-15a2 2 0 00-3.42 0z"
                    />
                </svg>

            </div>

            <div>

                <p class="font-bold">
                    No se pudo completar la operación
                </p>

                <p class="text-sm mt-0.5">
                    {{ session('error') }}
                </p>

            </div>

        </div>

    @endif


    {{-- ============================================================
         MODAL: CONTRATO FORMALIZADO
    ============================================================ --}}
    @if($mostrarContratoCreado && $contratoCreadoId)

        <div class="fixed inset-0 z-[70] flex items-center justify-center p-4">

            {{-- Fondo --}}
            <div
                class="absolute inset-0 bg-slate-950/50 backdrop-blur-sm"
                wire:click="cerrarContratoCreado"
            ></div>


            {{-- Modal --}}
            <div
                class="relative w-full max-w-md overflow-hidden
                       rounded-3xl bg-white shadow-2xl"
            >

                <div class="px-6 pt-7 pb-5 text-center">

                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center
                               rounded-full bg-emerald-100"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-9 w-9 text-emerald-600"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>

                    </div>


                    <h3 class="mt-5 text-xl font-extrabold text-slate-900">
                        Contrato formalizado
                    </h3>


                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        El contrato se registró correctamente y su tabla
                        de amortización fue generada.
                    </p>

                </div>


                <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50 px-6 py-5">

                    {{-- ================================================
                         VER / IMPRIMIR
                    ================================================= --}}
                    <a
                        href="{{ route('contratos.pdf', $contratoCreadoId) }}"
                        wire:click="cerrarContratoCreado"
                        class="inline-flex w-full items-center justify-center
                               gap-2 rounded-xl bg-blue-600 px-5 py-3
                               text-sm font-bold text-white
                               shadow-sm transition
                               hover:bg-blue-700
                               focus:outline-none focus:ring-2
                               focus:ring-blue-500 focus:ring-offset-2"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z"
                            />
                        </svg>

                        Ver / imprimir contrato

                    </a>


                    {{-- ================================================
                         CONTINUAR
                    ================================================= --}}
                    <button
                        type="button"
                        wire:click="cerrarContratoCreado"
                        class="inline-flex w-full items-center justify-center
                               gap-2 rounded-xl border border-slate-300
                               bg-white px-5 py-3
                               text-sm font-bold text-slate-700
                               transition hover:bg-slate-100"
                    >

                        Continuar

                    </button>

                </div>

            </div>

        </div>

    @endif


    {{-- ============================================================
         TABLA PRINCIPAL
    ============================================================ --}}
    <div
        class="overflow-hidden rounded-2xl border border-slate-200
               bg-white shadow-sm"
    >

        {{-- ========================================================
             BUSCADOR
        ========================================================= --}}
        <div
            class="flex flex-col gap-4 border-b border-slate-200
                   bg-slate-50/70 p-5
                   lg:flex-row lg:items-center lg:justify-between"
        >

            <div>

                <h2 class="text-base font-extrabold text-slate-900">
                    Contratos registrados
                </h2>

                <p class="mt-1 text-xs font-medium text-slate-500">
                    Consulta contratos, clientes y operaciones.
                </p>

            </div>


            <div class="relative w-full lg:w-96">

                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-slate-400"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                        />
                    </svg>

                </div>


                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por folio, cliente o terreno..."
                    class="w-full rounded-xl border border-slate-300
                           bg-white py-3 pl-11 pr-4
                           text-sm font-medium text-slate-700
                           shadow-sm outline-none transition
                           placeholder:text-slate-400
                           hover:border-slate-400
                           focus:border-emerald-500
                           focus:ring-2 focus:ring-emerald-100"
                >

            </div>

        </div>


        {{-- ========================================================
             TABLA
        ========================================================= --}}
        <div class="w-full overflow-x-auto">

            <table class="w-full min-w-[1500px] table-auto">

                <thead class="bg-slate-100/80">

                    <tr>

                        <th
                            class="w-[220px] px-6 py-4 text-left text-[11px]
                                   font-extrabold uppercase tracking-wider
                                   text-slate-500"
                        >
                            Folio / Inicio
                        </th>

                        <th
                            class="w-[250px] px-6 py-4 text-left text-[11px]
                                   font-extrabold uppercase tracking-wider
                                   text-slate-500"
                        >
                            Cliente
                        </th>

                        <th
                            class="w-[190px] px-6 py-4 text-left text-[11px]
                                   font-extrabold uppercase tracking-wider
                                   text-slate-500"
                        >
                            Lote asignado
                        </th>

                        <th
                            class="w-[270px] px-6 py-4 text-left text-[11px]
                                   font-extrabold uppercase tracking-wider
                                   text-slate-500"
                        >
                            Resumen financiero
                        </th>

                        <th
                            class="w-[170px] px-6 py-4 text-center text-[11px]
                                   font-extrabold uppercase tracking-wider
                                   text-slate-500"
                        >
                            Estado
                        </th>

                        <th
                            class="w-[430px] min-w-[430px] px-6 py-4 text-center
                                   text-[11px] font-extrabold uppercase
                                   tracking-wider text-slate-500"
                        >
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($contratos as $c)

                        <tr class="transition hover:bg-slate-50/70">


                            {{-- =================================================
                                 FOLIO
                            ================================================== --}}
                            <td class="px-6 py-5">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-10 w-10 shrink-0
                                               items-center justify-center
                                               rounded-xl bg-emerald-50"
                                    >

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 text-emerald-600"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M14 3v5h5"
                                            />
                                        </svg>

                                    </div>


                                    <div>

                                        <p class="font-extrabold text-slate-900">
                                            {{ $c->folio }}
                                        </p>

                                        <p class="mt-0.5 text-xs font-medium text-slate-500">
                                            {{ $c->fecha_inicio?->format('d/m/Y') ?? '—' }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                 CLIENTE
                            ================================================== --}}
                            <td class="px-6 py-5">

                                <p class="font-bold text-slate-800">

                                    {{ $c->cliente->nombre ?? '' }}
                                    {{ $c->cliente->apellidos ?? '' }}

                                </p>


                                @if($c->cliente?->telefono)

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $c->cliente->telefono }}
                                    </p>

                                @endif

                            </td>


                            {{-- =================================================
                                 TERRENO
                            ================================================== --}}
                            <td class="px-6 py-5">

                                <p class="font-bold text-slate-800">
                                    Lote {{ $c->terreno->lote ?? '—' }}
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    Manzana {{ $c->terreno->manzana ?? '—' }}
                                </p>

                            </td>


                            {{-- =================================================
                                 FINANZAS
                            ================================================== --}}
                            <td class="px-6 py-5">

                                <div class="space-y-1">

                                    <div class="flex items-center justify-between gap-5">

                                        <span class="text-xs font-medium text-slate-500">
                                            Precio
                                        </span>

                                        <span class="text-sm font-extrabold text-slate-800">
                                            ${{ number_format($c->precio_total, 2) }}
                                        </span>

                                    </div>


                                    <div class="flex items-center justify-between gap-5">

                                        <span class="text-xs font-medium text-slate-500">
                                            Enganche
                                        </span>

                                        <span class="text-xs font-bold text-slate-700">
                                            ${{ number_format($c->enganche, 2) }}
                                        </span>

                                    </div>


                                    <div class="flex items-center justify-between gap-5">

                                        <span class="text-xs font-medium text-slate-500">
                                            Saldo
                                        </span>

                                        <span class="text-sm font-extrabold text-emerald-600">
                                            ${{ number_format($c->saldo_actual, 2) }}
                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                 ESTADO
                            ================================================== --}}
                            <td class="px-6 py-5 text-center">

                                @if($c->estado === 'ACTIVO')

                                    <span
                                        class="inline-flex items-center gap-1.5
                                               rounded-full bg-emerald-100
                                               px-3 py-1.5 text-[11px]
                                               font-extrabold text-emerald-700"
                                    >

                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                                        ACTIVO

                                    </span>

                                @elseif($c->estado === 'LIQUIDADO')

                                    <span
                                        class="inline-flex items-center gap-1.5
                                               rounded-full bg-blue-100
                                               px-3 py-1.5 text-[11px]
                                               font-extrabold text-blue-700"
                                    >

                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>

                                        LIQUIDADO

                                    </span>

                                @else

                                    <span
                                        class="inline-flex items-center gap-1.5
                                               rounded-full bg-red-100
                                               px-3 py-1.5 text-[11px]
                                               font-extrabold text-red-700"
                                    >

                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>

                                        CANCELADO

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 ACCIONES
                            ================================================== --}}
                            <td class="px-6 py-5">

                                <div
                                    class="flex w-full min-w-[400px]
                                           flex-nowrap items-center
                                           justify-center gap-2
                                           whitespace-nowrap"
                                >


                                    {{-- =================================================
                                         VER / REIMPRIMIR CONTRATO
                                    ================================================== --}}
                                    <a
                                        href="{{ route('contratos.pdf', $c->id) }}"
                                        title="Ver / reimprimir contrato"
                                        class="inline-flex shrink-0 items-center justify-center
                                               gap-2 rounded-lg
                                               border border-blue-200
                                               bg-blue-50 px-3 py-2
                                               text-xs font-bold text-blue-700
                                               transition
                                               hover:border-blue-300
                                               hover:bg-blue-100"
                                    >

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 shrink-0"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12z"
                                            />

                                            <circle
                                                cx="12"
                                                cy="12"
                                                r="2.5"
                                            />
                                        </svg>

                                        Ver contrato

                                    </a>


                                    {{-- =================================================
                                         ESTADO DE CUENTA
                                    ================================================== --}}
                                    <button
                                        type="button"
                                        wire:click="verEstadoCuenta({{ $c->id }})"
                                        title="Ver estado de cuenta"
                                        class="inline-flex shrink-0 items-center justify-center
                                               gap-2 rounded-lg
                                               border border-slate-200
                                               bg-white px-3 py-2
                                               text-xs font-bold text-slate-700
                                               transition
                                               hover:border-slate-300
                                               hover:bg-slate-50"
                                    >

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 shrink-0"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M9 17h6m-6-4h6m-6-4h6M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"
                                            />

                                        </svg>

                                        Estado de cuenta

                                    </button>


                                    {{-- =================================================
                                         LIQUIDAR
                                    ================================================== --}}
                                    @if($c->estado === 'ACTIVO')

                                        @if(
                                            (auth()->user()->rol &&
                                            in_array(
                                                strtolower(auth()->user()->rol->nombre),
                                                ['administrador', 'super admin', 'superadministrador']
                                            ))
                                            ||
                                            auth()->user()->permisos()
                                                ->whereHas('modulo', fn($q) => $q->where('clave', 'contratos'))
                                                ->where('editar', true)
                                                ->exists()
                                        )

                                            <button
                                                type="button"
                                                wire:click="liquidarContrato({{ $c->id }})"
                                                wire:confirm="¿Deseas liquidar la deuda total de este contrato?"
                                                title="Liquidar contrato"
                                                class="inline-flex shrink-0 items-center justify-center
                                                       gap-2 rounded-lg
                                                       border border-emerald-200
                                                       bg-emerald-50 px-3 py-2
                                                       text-xs font-bold text-emerald-700
                                                       transition
                                                       hover:border-emerald-300
                                                       hover:bg-emerald-100"
                                            >

                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 shrink-0"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                >

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7"
                                                    />

                                                </svg>

                                                Liquidar

                                            </button>

                                        @endif


                                        {{-- ============================================
                                             CANCELAR
                                        ============================================= --}}
                                        @if(
                                            (auth()->user()->rol &&
                                            in_array(
                                                strtolower(auth()->user()->rol->nombre),
                                                ['administrador', 'super admin', 'superadministrador']
                                            ))
                                            ||
                                            auth()->user()->permisos()
                                                ->whereHas('modulo', fn($q) => $q->where('clave', 'contratos'))
                                                ->where('eliminar', true)
                                                ->exists()
                                        )

                                            <button
                                                type="button"
                                                wire:click="cancelarContrato({{ $c->id }})"
                                                wire:confirm="¿Deseas cancelar este contrato? Esta acción no se puede deshacer."
                                                title="Cancelar contrato"
                                                class="inline-flex shrink-0 items-center justify-center
                                                       gap-2 rounded-lg
                                                       border border-red-200
                                                       bg-red-50 px-3 py-2
                                                       text-xs font-bold text-red-700
                                                       transition
                                                       hover:border-red-300
                                                       hover:bg-red-100"
                                            >

                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 shrink-0"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                >

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12"
                                                    />

                                                </svg>

                                                Cancelar

                                            </button>

                                        @endif

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="px-6 py-16 text-center">

                                <div
                                    class="mx-auto flex h-16 w-16
                                           items-center justify-center
                                           rounded-2xl bg-slate-100"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-8 w-8 text-slate-400"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M14 3v5h5"
                                        />

                                    </svg>

                                </div>


                                <h3 class="mt-4 text-base font-extrabold text-slate-800">
                                    No hay contratos registrados
                                </h3>


                                <p class="mt-1 text-sm text-slate-500">
                                    Los contratos que formalices aparecerán aquí.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ========================================================
             PAGINACIÓN
        ========================================================= --}}
        {{-- Se eliminó la paginación para quitar Previous / Next --}}

    </div>


    {{-- ============================================================
         MODAL CREAR CONTRATO
    ============================================================ --}}
    @include('livewire.admin.contratos.create')


    {{-- ============================================================
         MODAL ESTADO DE CUENTA
    ============================================================ --}}
    @include('livewire.admin.contratos.show')

</div>