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