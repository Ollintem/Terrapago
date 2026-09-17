<div class="p-6 max-w-7xl mx-auto space-y-6">

    {{-- ========================================================= --}}
    {{-- ENCABEZADO --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5">

        <div>

            {{-- VOLVER A USUARIOS --}}
            <a href="{{ route('admin.usuarios.index') }}"
               class="inline-flex items-center gap-2
                      text-sm font-semibold
                      text-emerald-600
                      hover:text-emerald-700
                      mb-4
                      transition-all duration-200
                      hover:-translate-x-1">

                <svg class="w-4 h-4"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 19l-7-7 7-7"/>

                </svg>

                Volver a Usuarios

            </a>


            {{-- SECCIÓN --}}
            <div class="flex items-center gap-2 mb-3">

                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>

                <span class="text-sm font-black uppercase tracking-[0.14em] text-emerald-600">
                    Administración
                </span>

            </div>


            {{-- TÍTULO --}}
            <h1 class="text-4xl font-black tracking-[-0.04em] text-[#0F172A] leading-none">

                Matriz de
                <span class="text-emerald-600">
                    Permisos
                </span>

            </h1>


            {{-- DESCRIPCIÓN --}}
            <p class="text-base text-slate-500 mt-3 font-medium">

                Configura los accesos y acciones disponibles para este usuario.

            </p>

        </div>


        {{-- ===================================================== --}}
        {{-- INFORMACIÓN DEL USUARIO --}}
        {{-- ===================================================== --}}

        <div class="flex items-center gap-3
                    bg-white
                    border border-slate-200
                    rounded-xl
                    px-4 py-3
                    shadow-sm">

            <div class="relative">

                <div class="w-12 h-12
                            rounded-xl
                            bg-emerald-100
                            text-emerald-700
                            flex items-center justify-center
                            font-black text-sm">

                    {{ strtoupper(substr($user->nombre ?? 'SU', 0, 2)) }}

                </div>


                <span class="absolute
                             -bottom-1
                             -right-1
                             w-3 h-3
                             bg-emerald-500
                             border-2
                             border-white
                             rounded-full">
                </span>

            </div>


            <div>

                <p class="text-base font-bold text-[#0F172A]">
                    {{ $user->nombre }}
                </p>

                <p class="text-sm text-slate-500 mt-1 flex items-center gap-2">

                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>

                    {{ $user->rol->nombre ?? 'Sin Rol' }}

                </p>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- NOTIFICACIONES FLASH --}}
    {{-- ========================================================= --}}

    @if (session()->has('mensaje'))

        <div class="bg-emerald-50
                    border-l-4
                    border-emerald-500
                    text-emerald-700
                    p-4
                    rounded-r-lg">

            <p class="text-sm font-medium">
                {{ session('mensaje') }}
            </p>

        </div>

    @endif


    @if (session()->has('message'))

        <div class="bg-emerald-50
                    border-l-4
                    border-emerald-500
                    text-emerald-700
                    p-4
                    rounded-r-lg">

            <p class="text-sm font-medium">
                {{ session('message') }}
            </p>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- CONTENEDOR DE LA TABLA --}}
    {{-- ========================================================= --}}

    <div class="bg-white
                rounded-xl
                shadow-sm
                border border-slate-200
                overflow-hidden">


        {{-- ===================================================== --}}
        {{-- BARRA SUPERIOR --}}
        {{-- ===================================================== --}}

        <div class="p-4
                    border-b border-slate-100
                    flex flex-col sm:flex-row
                    sm:items-center
                    justify-between
                    gap-4">

            <div>

                <h2 class="text-lg font-bold text-slate-800">
                    Permisos por módulo
                </h2>

                <p class="text-sm text-slate-400 mt-0.5">
                    Selecciona las acciones que podrá realizar el usuario.
                </p>

            </div>


            {{-- ================================================= --}}
            {{-- BOTONES MARCAR / DESMARCAR --}}
            {{-- ================================================= --}}

            <div class="flex items-center gap-2">

                <button type="button"
                        wire:click="seleccionarTodo"

                        class="px-3 py-1.5
                               text-xs
                               font-semibold
                               rounded-lg
                               border border-slate-300
                               text-slate-600
                               hover:bg-slate-100
                               transition
                               cursor-pointer">

                    ✓ Marcar Todos

                </button>


                <button type="button"
                        wire:click="deseleccionarTodo"

                        class="px-3 py-1.5
                               text-xs
                               font-semibold
                               rounded-lg
                               border border-slate-300
                               text-slate-600
                               hover:bg-slate-100
                               transition
                               cursor-pointer">

                    ✕ Desmarcar Todos

                </button>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- TABLA --}}
        {{-- ========================================================= --}}

        <div class="overflow-x-auto">

            <table class="w-full text-left border-collapse">


                {{-- ================================================= --}}
                {{-- ENCABEZADOS --}}
                {{-- ================================================= --}}

                <thead>

                    <tr class="bg-slate-50
                               text-slate-600
                               text-xs
                               uppercase
                               tracking-wider
                               font-semibold
                               border-b border-slate-200">


                        <th class="py-4 px-6">
                            Módulo del Sistema
                        </th>


                        <th class="py-4 px-4 text-center">
                            Mostrar
                        </th>


                        <th class="py-4 px-4 text-center">
                            Crear
                        </th>


                        <th class="py-4 px-4 text-center">
                            Editar
                        </th>


                        <th class="py-4 px-4 text-center">
                            Eliminar
                        </th>

                    </tr>

                </thead>


                {{-- ================================================= --}}
                {{-- CUERPO --}}
                {{-- ================================================= --}}

                <tbody class="divide-y divide-slate-100
                             text-sm
                             text-slate-700">


                    @foreach($modulos as $modulo)

                        <tr class="hover:bg-slate-50/60
                                   transition">


                            {{-- ===================================== --}}
                            {{-- MÓDULO --}}
                            {{-- ===================================== --}}

                            <td class="py-4 px-6">

                                <div class="flex items-center gap-4">


                                    <div class="w-11 h-11
                                                rounded-xl
                                                bg-slate-100
                                                text-slate-500
                                                flex
                                                items-center
                                                justify-center
                                                transition-all
                                                duration-200">

                                        <svg class="w-5 h-5"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <rect x="4"
                                                  y="4"
                                                  width="6"
                                                  height="6"
                                                  rx="1"
                                                  stroke-width="1.8"/>

                                            <rect x="14"
                                                  y="4"
                                                  width="6"
                                                  height="6"
                                                  rx="1"
                                                  stroke-width="1.8"/>

                                            <rect x="4"
                                                  y="14"
                                                  width="6"
                                                  height="6"
                                                  rx="1"
                                                  stroke-width="1.8"/>

                                            <rect x="14"
                                                  y="14"
                                                  width="6"
                                                  height="6"
                                                  rx="1"
                                                  stroke-width="1.8"/>

                                        </svg>

                                    </div>


                                    <div>

                                        <p class="font-semibold
                                                  text-slate-900">

                                            {{ $modulo->nombre }}

                                        </p>


                                        <span class="block
                                                     text-[11px]
                                                     text-slate-400
                                                     font-normal
                                                     mt-0.5
                                                     font-mono">

                                            clave: {{ $modulo->clave }}

                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- ===================================== --}}
                            {{-- MOSTRAR --}}
                            {{-- ===================================== --}}

                            <td class="py-4 px-4 text-center">

                                <label class="cursor-pointer">

                                    <input type="checkbox"
                                           wire:model="permisosSeleccionados.{{ $modulo->id }}.mostrar"
                                           class="peer sr-only">


                                    <span class="inline-flex
                                                 items-center
                                                 justify-center

                                                 w-12 h-12

                                                 rounded-xl

                                                 border-2
                                                 border-emerald-200

                                                 bg-emerald-50

                                                 text-emerald-300

                                                 transition-all
                                                 duration-200

                                                 peer-hover:border-emerald-400

                                                 peer-checked:bg-emerald-500
                                                 peer-checked:border-emerald-500
                                                 peer-checked:text-white

                                                 peer-checked:shadow-md
                                                 peer-checked:shadow-emerald-500/30

                                                 peer-checked:scale-105">

                                        <svg class="w-6 h-6"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2.5"
                                                  d="M5 13l4 4L19 7"/>

                                        </svg>

                                    </span>

                                </label>

                            </td>


                            {{-- ===================================== --}}
                            {{-- CREAR --}}
                            {{-- ===================================== --}}

                            <td class="py-4 px-4 text-center">

                                <label class="cursor-pointer">

                                    <input type="checkbox"
                                           wire:model="permisosSeleccionados.{{ $modulo->id }}.crear"
                                           class="peer sr-only">


                                    <span class="inline-flex
                                                 items-center
                                                 justify-center

                                                 w-12 h-12

                                                 rounded-xl

                                                 border-2
                                                 border-blue-200

                                                 bg-blue-50

                                                 text-blue-300

                                                 transition-all
                                                 duration-200

                                                 peer-hover:border-blue-400

                                                 peer-checked:bg-blue-500
                                                 peer-checked:border-blue-500
                                                 peer-checked:text-white

                                                 peer-checked:shadow-md
                                                 peer-checked:shadow-blue-500/30

                                                 peer-checked:scale-105">

                                        <svg class="w-6 h-6"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2.5"
                                                  d="M12 5v14M5 12h14"/>

                                        </svg>

                                    </span>

                                </label>

                            </td>


                            {{-- ===================================== --}}
                            {{-- EDITAR --}}
                            {{-- ===================================== --}}

                            <td class="py-4 px-4 text-center">

                                <label class="cursor-pointer">

                                    <input type="checkbox"
                                           wire:model="permisosSeleccionados.{{ $modulo->id }}.editar"
                                           class="peer sr-only">


                                    <span class="inline-flex
                                                 items-center
                                                 justify-center

                                                 w-12 h-12

                                                 rounded-xl

                                                 border-2
                                                 border-amber-200

                                                 bg-amber-50

                                                 text-amber-300

                                                 transition-all
                                                 duration-200

                                                 peer-hover:border-amber-400

                                                 peer-checked:bg-amber-400
                                                 peer-checked:border-amber-400
                                                 peer-checked:text-white

                                                 peer-checked:shadow-md
                                                 peer-checked:shadow-amber-400/30

                                                 peer-checked:scale-105">

                                        <svg class="w-6 h-6"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2.3"
                                                  d="M16.5 3.5a2.1 2.1 0 013 3L8 18l-4 1 1-4L16.5 3.5z"/>

                                        </svg>

                                    </span>

                                </label>

                            </td>


                            {{-- ===================================== --}}
                            {{-- ELIMINAR --}}
                            {{-- ===================================== --}}

                            <td class="py-4 px-4 text-center">

                                <label class="cursor-pointer">

                                    <input type="checkbox"
                                           wire:model="permisosSeleccionados.{{ $modulo->id }}.eliminar"
                                           class="peer sr-only">


                                    <span class="inline-flex
                                                 items-center
                                                 justify-center

                                                 w-12 h-12

                                                 rounded-xl

                                                 border-2
                                                 border-rose-200

                                                 bg-rose-50

                                                 text-rose-300

                                                 transition-all
                                                 duration-200

                                                 peer-hover:border-rose-400

                                                 peer-checked:bg-rose-500
                                                 peer-checked:border-rose-500
                                                 peer-checked:text-white

                                                 peer-checked:shadow-md
                                                 peer-checked:shadow-rose-500/30

                                                 peer-checked:scale-105">

                                        <svg class="w-6 h-6"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M6 7h12M9 7V5h6v2m-7 0l.7 12h6.6L16 7M10 10v6M14 10v6"/>

                                        </svg>

                                    </span>

                                </label>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- ========================================================= --}}
        {{-- BOTÓN GUARDAR --}}
        {{-- ========================================================= --}}

        <div class="p-4
                    bg-slate-50/50
                    border-t border-slate-100
                    flex justify-end">

            <button type="button"
                    wire:click="guardar"

                    class="px-5 py-2.5
                           bg-emerald-600
                           hover:bg-emerald-700
                           text-white
                           rounded-xl
                           text-sm
                           font-semibold
                           shadow-md
                           shadow-emerald-600/20
                           transition
                           cursor-pointer">

                Guardar Cambios

            </button>

        </div>

    </div>

</div>