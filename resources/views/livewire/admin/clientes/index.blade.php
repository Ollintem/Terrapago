<div class="p-6 max-w-7xl mx-auto">

    {{-- =========================================================
         ENCABEZADO
    ========================================================== --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        <div>

            {{-- Indicador de sección --}}
            <div class="flex items-center gap-2 mb-2">

                <span class="h-2 w-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></span>

                <span class="text-[11px] font-bold uppercase tracking-[0.16em] text-emerald-600">
                    Administración
                </span>

            </div>

            {{-- Título --}}
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900">

                Gestión de

                <span class="text-emerald-600">
                    Clientes
                </span>

            </h1>

            {{-- Descripción --}}
            <p class="mt-2 text-sm sm:text-base font-medium text-slate-500 max-w-xl">

                Administra la información de clientes, contratos y seguimiento de pagos de

                <span class="font-semibold text-slate-700">
                    TerraPago
                </span>.

            </p>

        </div>


        {{-- BOTÓN NUEVO CLIENTE --}}
        @if(auth()->user()->rol && in_array(strtolower(auth()->user()->rol->nombre), ['administrador', 'super admin', 'superadministrador'])
            || auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'clientes'))->where('crear', true)->exists())

            <button
                wire:click="abrirModalCrear"
                class="group flex items-center gap-2
                       bg-emerald-600 hover:bg-emerald-700
                       text-white px-4 py-2.5 rounded-xl
                       font-semibold text-sm
                       shadow-lg shadow-emerald-600/20
                       hover:shadow-emerald-600/30
                       hover:-translate-y-0.5
                       transition-all duration-200
                       cursor-pointer">

                <span class="text-lg leading-none transition-transform duration-200 group-hover:rotate-90">
                    +
                </span>

                Nuevo Cliente

            </button>

        @endif

    </div>


    {{-- =========================================================
        NOTIFICACIONES (CON AUTODESAPARICIÓN DE 5 SEGUNDOS)
    ========================================================== --}}
    @if (session()->has('mensaje'))
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 5000)" 
            x-show="show" 
            x-transition:leave="transition ease-in duration-500" 
            x-transition:leave-start="opacity-100 transform scale-100" 
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            class="flex items-center justify-between bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-5 rounded-r-lg">
            <p class="text-sm font-semibold">{{ session('mensaje') }}</p>
            <button type="button" @click="show = false" class="text-emerald-500 hover:text-emerald-700 font-bold">✕</button>
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
            class="flex items-center justify-between bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 mb-5 rounded-r-lg">
            <p class="text-sm font-semibold">{{ session('error') }}</p>
            <button type="button" @click="show = false" class="text-rose-500 hover:text-rose-700 font-bold">✕</button>
        </div>
    @endif

    {{-- =========================================================
        INCLUSIÓN DE TARJETAS DE ESTADÍSTICAS
    ========================================================== --}}
    @include('livewire.admin.clientes.estadisticas')


    {{-- =========================================================
         BUSCADOR
    ========================================================== --}}
    <div class="bg-white rounded-2xl
                border border-slate-200
                shadow-sm p-5 mb-6">

        <div class="flex flex-col lg:flex-row
                    lg:items-center lg:justify-between
                    gap-4">

            <div class="relative w-full max-w-xl">

                <div class="absolute inset-y-0 left-0
                            flex items-center pl-4
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
                            d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"/>

                    </svg>

                </div>

                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Buscar por nombre, CURP/RFC o teléfono..."

                    class="w-full pl-12 pr-4 py-3
                           bg-white
                           border border-slate-200
                           rounded-xl
                           text-sm font-medium text-slate-700
                           placeholder:text-slate-400
                           shadow-sm
                           focus:outline-none
                           focus:ring-2 focus:ring-emerald-500/30
                           focus:border-emerald-500
                           transition">

            </div>


            <div class="text-xs font-semibold text-slate-500">

                {{ $clientes->total() }} clientes registrados

            </div>

        </div>

    </div>


    {{-- =========================================================
         TABLA DE CLIENTES
    ========================================================== --}}
    <div class="bg-white rounded-2xl
                shadow-sm border border-slate-200
                overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-left border-collapse">

                <thead>

                    <tr class="bg-slate-50
                               text-slate-500
                               text-[11px]
                               uppercase
                               tracking-[0.12em]
                               font-bold
                               border-b border-slate-100">

                        <th class="p-4 min-w-[210px]">
                            Cliente
                        </th>

                        <th class="p-4">
                            Teléfono
                        </th>

                        <th class="p-4">
                            Terreno
                        </th>

                        <th class="p-4">
                            Saldo deudor
                        </th>

                        <th class="p-4">
                            Último pago
                        </th>

                        <th class="p-4 text-center">
                            Estado
                        </th>

                        <th class="p-4 text-center">
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100 text-sm">


                    @forelse($clientes as $cliente)

                        @php

                            $contrato = $cliente->contratos
                                ->where('estado', 'ACTIVO')
                                ->sortByDesc('id')
                                ->first();

                            $terreno = $contrato?->terreno;

                            $cuotas = $contrato?->cuotas ?? collect();

                            $cuotaVencida = $cuotas
                                ->where('estado', 'VENCIDA')
                                ->sortBy('fecha_vencimiento')
                                ->first();

                            $cuotaProxima = $cuotas
                                ->where('estado', 'PENDIENTE')
                                ->sortBy('fecha_vencimiento')
                                ->first();

                            $ultimaCuotaPagada = $cuotas
                                ->where('estado', 'PAGADA')
                                ->sortByDesc('fecha_vencimiento')
                                ->first();

                            if ($cuotaVencida) {

                                $estadoCuenta = 'moroso';

                            } elseif (
                                $cuotaProxima &&
                                $cuotaProxima->fecha_vencimiento &&
                                now()->diffInDays($cuotaProxima->fecha_vencimiento, false) >= 0 &&
                                now()->diffInDays($cuotaProxima->fecha_vencimiento, false) <= 15
                            ) {

                                $estadoCuenta = 'proximo';

                            } elseif ($contrato) {

                                $estadoCuenta = 'corriente';

                            } else {

                                $estadoCuenta = 'sin_contrato';

                            }

                        @endphp


                        {{-- FILA PRINCIPAL --}}
                        <tr class="hover:bg-slate-50/60 transition duration-150">


                            {{-- CLIENTE --}}
                            <td class="p-4">

                                <div class="flex items-center gap-3">

                                    <div class="h-10 w-10 rounded-full
                                                bg-emerald-50
                                                border border-emerald-100
                                                text-emerald-600
                                                flex items-center justify-center
                                                text-xs font-bold
                                                uppercase
                                                flex-shrink-0">

                                        {{ substr($cliente->nombre ?? 'C', 0, 1) }}

                                    </div>

                                    <div>

                                        <p class="font-bold text-slate-900">
                                            {{ $cliente->nombre_completo }}
                                        </p>

                                        @if($cliente->curp)

                                            <p class="text-[10px] text-slate-400 font-medium">
                                                {{ $cliente->curp }}
                                            </p>

                                        @elseif($cliente->rfc)

                                            <p class="text-[10px] text-slate-400 font-medium">
                                                {{ $cliente->rfc }}
                                            </p>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- TELÉFONO --}}
                            <td class="p-4">

                                <span class="font-medium text-slate-700">
                                    {{ $cliente->telefono }}
                                </span>

                                @if($cliente->email)

                                    <p class="text-[11px] text-slate-400 mt-0.5">
                                        {{ $cliente->email }}
                                    </p>

                                @endif

                            </td>


                            {{-- TERRENO --}}
                            <td class="p-4">

                                @if($terreno)

                                    <span class="inline-flex items-center
                                                 px-2.5 py-1.5
                                                 rounded-lg
                                                 bg-slate-100
                                                 border border-slate-200
                                                 text-xs font-semibold
                                                 text-slate-700">

                                        Mz. {{ $terreno->manzana }}

                                        ·

                                        Lt. {{ $terreno->lote }}

                                    </span>

                                @else

                                    <span class="text-xs text-slate-400 italic">
                                        Sin terreno
                                    </span>

                                @endif

                            </td>


                            {{-- SALDO --}}
                            <td class="p-4">

                                @if($contrato)

                                    <p class="font-extrabold text-slate-900">

                                        ${{ number_format((float) $contrato->saldo_actual, 2) }}

                                    </p>

                                    <p class="text-[10px] text-slate-400">
                                        Saldo actual
                                    </p>

                                @else

                                    <span class="text-xs text-slate-400">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- ÚLTIMO PAGO --}}
                            <td class="p-4">

                                @if($ultimaCuotaPagada)

                                    <p class="font-medium text-slate-700">

                                        {{ $ultimaCuotaPagada->fecha_vencimiento
                                            ? $ultimaCuotaPagada->fecha_vencimiento->format('d/m/Y')
                                            : '—'
                                        }}

                                    </p>

                                    <p class="text-[10px] text-emerald-600 font-semibold">
                                        Cuota {{ $ultimaCuotaPagada->numero_cuota }}
                                    </p>

                                @else

                                    <span class="text-xs text-slate-400">
                                        Sin pagos
                                    </span>

                                @endif

                            </td>


                            {{-- ESTADO --}}
                            <td class="p-4 text-center">

                                @if($estadoCuenta === 'moroso')

                                    <span class="inline-flex items-center gap-1.5
                                                 px-3 py-1.5
                                                 rounded-full
                                                 bg-rose-50
                                                 border border-rose-200
                                                 text-rose-700
                                                 text-xs font-bold">

                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>

                                        Moroso

                                    </span>

                                @elseif($estadoCuenta === 'proximo')

                                    <span class="inline-flex items-center gap-1.5
                                                 px-3 py-1.5
                                                 rounded-full
                                                 bg-amber-50
                                                 border border-amber-200
                                                 text-amber-700
                                                 text-xs font-bold">

                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>

                                        Próximo

                                    </span>

                                @elseif($estadoCuenta === 'corriente')

                                    <span class="inline-flex items-center gap-1.5
                                                 px-3 py-1.5
                                                 rounded-full
                                                 bg-emerald-50
                                                 border border-emerald-200
                                                 text-emerald-700
                                                 text-xs font-bold">

                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>

                                        Al corriente

                                    </span>

                                @else

                                    <span class="inline-flex items-center
                                                 px-3 py-1.5
                                                 rounded-full
                                                 bg-slate-100
                                                 border border-slate-200
                                                 text-slate-500
                                                 text-xs font-semibold">

                                        Sin contrato

                                    </span>

                                @endif

                            </td>


                            {{-- ACCIONES --}}
                            <td class="p-4">

                                <div class="flex items-center justify-center gap-2">


                                    {{-- EDITAR --}}
                                    @if($esAdmin || auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'clientes'))->where('editar', true)->exists())

                                        <button
                                            wire:click="abrirModalEditar({{ $cliente->id }})"

                                            class="flex items-center gap-1.5
                                                   text-blue-600
                                                   hover:text-blue-800
                                                   bg-blue-50
                                                   hover:bg-blue-100
                                                   border border-blue-100
                                                   px-3 py-2
                                                   rounded-lg
                                                   text-xs font-semibold
                                                   transition
                                                   cursor-pointer">

                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5h2m-7 14h14M5 19l4-4 10-10a2.121 2.121 0 0 0-3-3L6 12l-1 7z"/>

                                            </svg>

                                            Editar

                                        </button>

                                    @endif


                                    {{-- ELIMINAR --}}
                                    @if($esAdmin || auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'clientes'))->where('eliminar', true)->exists())

                                        <button
                                            wire:click="eliminar({{ $cliente->id }})"

                                            wire:confirm="¿Seguro que deseas eliminar al cliente {{ $cliente->nombre_completo }}?"

                                            class="flex items-center justify-center
                                                   text-rose-600
                                                   hover:text-rose-800
                                                   bg-rose-50
                                                   hover:bg-rose-100
                                                   border border-rose-100
                                                   px-3 py-2
                                                   rounded-lg
                                                   text-xs font-semibold
                                                   transition
                                                   cursor-pointer">

                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M6 7h12m-9 0V5h6v2m-7 0v12a2 2 0 002 2h4a2 2 0 002-2V7M10 11v6m4-6v6"/>

                                            </svg>

                                        </button>

                                    @endif

                                </div>

                            </td>

                        </tr>


                        {{-- =================================================
                             DETALLE DEL CLIENTE
                        ================================================== --}}
                        @if($contrato)

                            <tr class="bg-slate-50/40">

                                <td colspan="7" class="px-6 pb-5 pt-0">

                                    <div class="ml-[52px]
                                                grid grid-cols-1
                                                sm:grid-cols-3
                                                gap-3">


                                        {{-- CUOTA --}}
                                        <div class="bg-white
                                                    border border-slate-200
                                                    rounded-xl p-4">

                                            <p class="text-[10px]
                                                      font-bold
                                                      uppercase
                                                      tracking-wider
                                                      text-slate-400">

                                                Cuota {{ ucfirst(strtolower($contrato->frecuencia_pago)) }}

                                            </p>

                                            @php

                                                $cuotaPendiente = $cuotas
                                                    ->whereIn('estado', ['PENDIENTE', 'VENCIDA'])
                                                    ->sortBy('fecha_vencimiento')
                                                    ->first();

                                            @endphp

                                            <p class="mt-1 text-lg
                                                      font-extrabold
                                                      text-slate-900">

                                                @if($cuotaPendiente)

                                                    ${{ number_format((float) $cuotaPendiente->monto, 2) }}

                                                @else

                                                    —

                                                @endif

                                            </p>

                                        </div>


                                        {{-- PRÓXIMO VENCIMIENTO --}}
                                        <div class="bg-white
                                                    border border-slate-200
                                                    rounded-xl p-4">

                                            <p class="text-[10px]
                                                      font-bold
                                                      uppercase
                                                      tracking-wider
                                                      text-slate-400">

                                                Próximo vencimiento

                                            </p>

                                            <p class="mt-1 text-lg
                                                      font-extrabold
                                                      text-slate-900">

                                                @if($cuotaProxima)

                                                    {{ $cuotaProxima->fecha_vencimiento->format('d M Y') }}

                                                @elseif($cuotaVencida)

                                                    <span class="text-rose-600">
                                                        Cuota vencida
                                                    </span>

                                                @else

                                                    —

                                                @endif

                                            </p>

                                        </div>


                                        {{-- CUOTAS --}}
                                        <div class="bg-white
                                                    border border-slate-200
                                                    rounded-xl p-4">

                                            <p class="text-[10px]
                                                      font-bold
                                                      uppercase
                                                      tracking-wider
                                                      text-slate-400">

                                                Plan de pagos

                                            </p>

                                            <p class="mt-1 text-lg
                                                      font-extrabold
                                                      text-slate-900">

                                                {{ $contrato->numero_cuotas }}

                                                <span class="text-xs
                                                             font-medium
                                                             text-slate-400">

                                                    cuotas

                                                </span>

                                            </p>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @endif


                    @empty

                        <tr>

                            <td colspan="7" class="p-12 text-center">

                                <div class="flex flex-col items-center justify-center">

                                    <div class="w-14 h-14 rounded-2xl
                                                bg-slate-100
                                                flex items-center justify-center
                                                mb-4">

                                        <svg
                                            class="w-7 h-7 text-slate-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6H6a4 4 0 01-4-4v-1a4 4 0 014-4h7a4 4 0 014 4v1a4 4 0 01-4 4zm0-10a4 4 0 100-8 4 4 0 000 8z"/>

                                        </svg>

                                    </div>

                                    <p class="text-sm font-semibold text-slate-600">
                                        No hay clientes registrados.
                                    </p>

                                    <p class="text-xs text-slate-400 mt-1">
                                        Los clientes aparecerán aquí cuando sean registrados.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINACIÓN --}}
        <div class="p-4 border-t border-slate-100 bg-slate-50/30">

            {{ $clientes->links() }}

        </div>

    </div>

    {{-- =========================================================
        INCLUSIÓN DEL MODAL CREAR / EDITAR
    ========================================================== --}}
    @include('livewire.admin.clientes.create')

</div>