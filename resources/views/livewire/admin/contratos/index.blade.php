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


    {{-- =========================================================
        MENSAJES (CON AUTODESAPARICIÓN DE 5 SEGUNDOS)
    ========================================================== --}}
    @if (session()->has('mensaje'))
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 5000)" 
            x-show="show" 
            x-transition:leave="transition ease-in duration-500" 
            x-transition:leave-start="opacity-100 transform scale-100" 
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 font-bold">✓</div>
            <span class="flex-1">{{ session('mensaje') }}</span>
            <button type="button" @click="show = false" class="text-emerald-500 hover:text-emerald-700 font-bold cursor-pointer">✕</button>
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
            class="flex items-center gap-3 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-medium">
            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-rose-100 text-rose-600 font-bold">!</div>
            <span class="flex-1">{{ session('error') }}</span>
            <button type="button" @click="show = false" class="text-rose-500 hover:text-rose-700 font-bold cursor-pointer">✕</button>
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


    {{-- =========================================================
        INCLUSIÓN DE MODALES MODULARES
    ========================================================== --}}
    {{-- Modal para crear contrato nuevo --}}
    @include('livewire.admin.contratos.create')

    {{-- Modal para ver estado de cuenta y amortización --}}
    @include('livewire.admin.contratos.show')

</div>