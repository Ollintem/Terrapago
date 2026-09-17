<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TerraPago - Sistema de Cobro</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="font-sans bg-slate-50 text-slate-800 antialiased flex h-screen overflow-hidden">

    @auth

        @php
            $esAdmin = auth()->user()->rol && in_array(
                strtolower(auth()->user()->rol->nombre),
                ['administrador', 'super admin', 'superadministrador']
            );

            $tieneAcceso = function($claveModulo) use ($esAdmin) {
                if ($esAdmin) return true;

                return auth()->user()->permisos()
                    ->whereHas('modulo', fn($q) => $q->where('clave', $claveModulo))
                    ->where('mostrar', true)
                    ->exists();
            };
        @endphp


        <!-- =====================================================
             SIDEBAR
        ====================================================== -->
        <aside class="w-64 bg-[#0f172a] text-slate-300 flex flex-col justify-between flex-shrink-0 shadow-2xl shadow-slate-950/20">


            <!-- =================================================
                 PARTE SUPERIOR
            ================================================== -->
            <div>

                <!-- =================================================
                     MARCA / LOGO
                ================================================== -->
                <div class="px-5 pt-6 pb-7">

                    <div class="group flex items-center gap-3 cursor-default">


                        <!-- LOGO TP -->
                        <div class="relative">

                            <!-- Resplandor -->
                            <div class="absolute -inset-1 rounded-2xl bg-emerald-500/20 blur-md
                                        opacity-70 group-hover:opacity-100
                                        transition duration-500">
                            </div>


                            <!-- Logo -->
                            <div class="relative h-11 w-11
                                        bg-gradient-to-br from-emerald-400 to-emerald-600
                                        rounded-xl
                                        flex items-center justify-center
                                        text-white font-extrabold text-lg
                                        shadow-lg shadow-emerald-500/30
                                        border border-emerald-300/20
                                        transition-all duration-300
                                        group-hover:scale-105
                                        group-hover:-rotate-3">

                                TP

                            </div>


                            <!-- Punto decorativo -->
                            <span class="absolute -right-0.5 -top-0.5
                                         h-2.5 w-2.5
                                         rounded-full
                                         bg-emerald-300
                                         border-2 border-[#0f172a]
                                         animate-pulse">
                            </span>

                        </div>


                        <!-- NOMBRE -->
                        <div>

                            <h1 class="text-white font-extrabold text-lg tracking-tight leading-none
                                       group-hover:text-emerald-300 transition-colors duration-300">

                                TerraPago

                            </h1>


                            <p class="mt-1 text-[9px]
                                      text-slate-400
                                      font-bold
                                      tracking-[0.18em]
                                      uppercase">

                                Sistema de Cobro

                            </p>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     NAVEGACIÓN
                ================================================== -->
                <nav class="px-3 space-y-6 text-sm">


                    <!-- =================================================
                         PRINCIPAL
                    ================================================== -->
                    <div>

                        <p class="px-3 text-[10px]
                                  font-extrabold
                                  text-slate-500
                                  uppercase
                                  tracking-[0.18em]
                                  mb-2">

                            Principal

                        </p>


                        <!-- TABLERO -->
                        <a href="#"
                           class="group flex items-center gap-3
                                  px-3 py-2.5
                                  rounded-xl
                                  text-slate-400
                                  hover:bg-slate-800/80
                                  hover:text-white
                                  transition-all duration-200
                                  font-semibold
                                  hover:translate-x-1">


                            <!-- Icono -->
                            <span class="flex items-center justify-center
                                         w-8 h-8
                                         rounded-lg
                                         bg-slate-800
                                         text-slate-400
                                         group-hover:bg-emerald-500/15
                                         group-hover:text-emerald-400
                                         transition-all duration-200">

                                <svg class="w-4 h-4"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10"/>

                                </svg>

                            </span>


                            <span>
                                Tablero
                            </span>

                        </a>


                        <!-- COBRANZA -->
                        @if($tieneAcceso('caja'))

                            <a href="#"
                               class="group flex items-center justify-between
                                      px-3 py-2.5
                                      rounded-xl
                                      text-slate-400
                                      hover:bg-slate-800/80
                                      hover:text-white
                                      transition-all duration-200
                                      font-semibold
                                      hover:translate-x-1">


                                <div class="flex items-center gap-3">

                                    <span class="flex items-center justify-center
                                                 w-8 h-8
                                                 rounded-lg
                                                 bg-slate-800
                                                 text-slate-400
                                                 group-hover:bg-emerald-500/15
                                                 group-hover:text-emerald-400
                                                 transition-all duration-200">

                                        <svg class="w-4 h-4"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 10h18M7 15h2m2 0h2m2 0h2M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>

                                        </svg>

                                    </span>


                                    <span>
                                        Cobranza (Caja)
                                    </span>

                                </div>


                                <span class="bg-rose-500
                                             text-white
                                             text-[10px]
                                             font-extrabold
                                             px-2 py-0.5
                                             rounded-full
                                             shadow-sm shadow-rose-500/30
                                             animate-pulse">

                                    4

                                </span>

                            </a>

                        @endif

                    </div>


                    <!-- =================================================
                         CARTERA
                    ================================================== -->
                    @if($tieneAcceso('clientes') || $tieneAcceso('terrenos') || $tieneAcceso('contratos'))

                        <div>

                            <p class="px-3 text-[10px]
                                      font-extrabold
                                      text-slate-500
                                      uppercase
                                      tracking-[0.18em]
                                      mb-2">

                                Cartera

                            </p>


                            <!-- CLIENTES -->
                            @if($tieneAcceso('clientes'))

                                <a href="#"
                                   class="group flex items-center gap-3
                                          px-3 py-2.5
                                          rounded-xl
                                          text-slate-400
                                          hover:bg-slate-800/80
                                          hover:text-white
                                          transition-all duration-200
                                          font-semibold
                                          hover:translate-x-1">


                                    <span class="flex items-center justify-center
                                                 w-8 h-8
                                                 rounded-lg
                                                 bg-slate-800
                                                 text-slate-400
                                                 group-hover:bg-emerald-500/15
                                                 group-hover:text-emerald-400
                                                 transition-all duration-200">

                                        <svg class="w-4 h-4"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m6-6a4 4 0 100-8 4 4 0 000 8zm10-5v6m3-3h-6"/>

                                        </svg>

                                    </span>


                                    <span>
                                        Mis Clientes
                                    </span>

                                </a>

                            @endif


                            <!-- TERRENOS -->
                            @if($tieneAcceso('terrenos'))

                                <a href="#"
                                   class="group flex items-center gap-3
                                          px-3 py-2.5
                                          rounded-xl
                                          text-slate-400
                                          hover:bg-slate-800/80
                                          hover:text-white
                                          transition-all duration-200
                                          font-semibold
                                          hover:translate-x-1">


                                    <span class="flex items-center justify-center
                                                 w-8 h-8
                                                 rounded-lg
                                                 bg-slate-800
                                                 text-slate-400
                                                 group-hover:bg-emerald-500/15
                                                 group-hover:text-emerald-400
                                                 transition-all duration-200">

                                        <svg class="w-4 h-4"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-5h6v5"/>

                                        </svg>

                                    </span>


                                    <span>
                                        Terrenos
                                    </span>

                                </a>

                            @endif


                            <!-- CONTRATOS -->
                            @if($tieneAcceso('contratos'))

                                <a href="#"
                                   class="group flex items-center gap-3
                                          px-3 py-2.5
                                          rounded-xl
                                          text-slate-400
                                          hover:bg-slate-800/80
                                          hover:text-white
                                          transition-all duration-200
                                          font-semibold
                                          hover:translate-x-1">


                                    <span class="flex items-center justify-center
                                                 w-8 h-8
                                                 rounded-lg
                                                 bg-slate-800
                                                 text-slate-400
                                                 group-hover:bg-emerald-500/15
                                                 group-hover:text-emerald-400
                                                 transition-all duration-200">

                                        <svg class="w-4 h-4"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2zM15 3v5h5M9 13h6M9 17h6"/>

                                        </svg>

                                    </span>


                                    <span>
                                        Contratos
                                    </span>

                                </a>

                            @endif

                        </div>

                    @endif


                    <!-- =================================================
                         ADMINISTRACIÓN
                    ================================================== -->
                    @if($tieneAcceso('usuarios') || $tieneAcceso('roles') || $tieneAcceso('auditoria'))

                        <div>

                            <p class="px-3 text-[10px]
                                      font-extrabold
                                      text-slate-500
                                      uppercase
                                      tracking-[0.18em]
                                      mb-2">

                                Administración

                            </p>


                            <!-- USUARIOS -->
                            @if($tieneAcceso('usuarios'))

                                <a href="{{ route('admin.usuarios.index') }}"
                                   class="group relative flex items-center gap-3
                                          px-3 py-2.5
                                          rounded-xl
                                          font-semibold
                                          transition-all duration-200
                                          hover:translate-x-1

                                          {{ request()->routeIs('admin.usuarios.*')
                                                ? 'bg-emerald-500/10 text-emerald-400 shadow-sm shadow-emerald-900/10'
                                                : 'text-slate-400 hover:bg-slate-800/80 hover:text-white' }}">


                                    <!-- Indicador activo -->
                                    @if(request()->routeIs('admin.usuarios.*'))

                                        <span class="absolute left-0
                                                     top-2 bottom-2
                                                     w-1
                                                     rounded-r-full
                                                     bg-emerald-500
                                                     shadow-sm shadow-emerald-500/50">
                                        </span>

                                    @endif


                                    <span class="flex items-center justify-center
                                                 w-8 h-8
                                                 rounded-lg
                                                 transition-all duration-200

                                                 {{ request()->routeIs('admin.usuarios.*')
                                                    ? 'bg-emerald-500/15 text-emerald-400'
                                                    : 'bg-slate-800 text-slate-400 group-hover:bg-emerald-500/15 group-hover:text-emerald-400' }}">

                                        <svg class="w-4 h-4"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m6-6a4 4 0 100-8 4 4 0 000 8zm10-5v6m3-3h-6"/>

                                        </svg>

                                    </span>


                                    <span>
                                        Usuarios
                                    </span>

                                </a>

                            @endif


                            <!-- ROLES -->
                            @if($tieneAcceso('roles'))

                                <a href="{{ route('admin.roles.index') }}"
                                   class="group relative flex items-center gap-3
                                          px-3 py-2.5
                                          rounded-xl
                                          font-semibold
                                          transition-all duration-200
                                          hover:translate-x-1

                                          {{ request()->routeIs('admin.roles.*')
                                                ? 'bg-emerald-500/10 text-emerald-400 shadow-sm shadow-emerald-900/10'
                                                : 'text-slate-400 hover:bg-slate-800/80 hover:text-white' }}">


                                    <!-- Indicador activo -->
                                    @if(request()->routeIs('admin.roles.*'))

                                        <span class="absolute left-0
                                                     top-2 bottom-2
                                                     w-1
                                                     rounded-r-full
                                                     bg-emerald-500
                                                     shadow-sm shadow-emerald-500/50">
                                        </span>

                                    @endif


                                    <span class="flex items-center justify-center
                                                 w-8 h-8
                                                 rounded-lg
                                                 transition-all duration-200

                                                 {{ request()->routeIs('admin.roles.*')
                                                    ? 'bg-emerald-500/15 text-emerald-400'
                                                    : 'bg-slate-800 text-slate-400 group-hover:bg-emerald-500/15 group-hover:text-emerald-400' }}">

                                        <svg class="w-4 h-4"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 15.5a3.5 3.5 0 100-7 3.5 3.5 0 000 7zM19.4 15a1.65 1.65 0 00.33 1.82l.06.06-1.5 1.5-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V20h-2.12v-.5a1.65 1.65 0 00-1-1.51 1.65 1.65 0 00-1.82.33l-.06.06-1.5-1.5.06-.06A1.65 1.65 0 009.4 15a1.65 1.65 0 00-1.51-1H7.5v-2.12H8a1.65 1.65 0 001.51-1 1.65 1.65 0 00-.33-1.82l-.06-.06 1.5-1.5.06.06a1.65 1.65 0 001.82.33 1.65 1.65 0 001-1.51V6h2.12v.5a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06 1.5 1.5-.06.06a1.65 1.65 0 00-.33 1.82 1.65 1.65 0 001.51 1h.5V14h-.5a1.65 1.65 0 00-1.51 1z"/>

                                        </svg>

                                    </span>


                                    <span>
                                        Roles
                                    </span>

                                </a>

                            @endif


                            <!-- AUDITORÍA -->
                            @if($tieneAcceso('auditoria'))

                                <a href="#"
                                   class="group flex items-center gap-3
                                          px-3 py-2.5
                                          rounded-xl
                                          text-slate-400
                                          hover:bg-slate-800/80
                                          hover:text-white
                                          transition-all duration-200
                                          font-semibold
                                          hover:translate-x-1">


                                    <span class="flex items-center justify-center
                                                 w-8 h-8
                                                 rounded-lg
                                                 bg-slate-800
                                                 text-slate-400
                                                 group-hover:bg-emerald-500/15
                                                 group-hover:text-emerald-400
                                                 transition-all duration-200">

                                        <svg class="w-4 h-4"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12h6m-6 4h6M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2zM15 3v5h5"/>

                                        </svg>

                                    </span>


                                    <span>
                                        Auditoría
                                    </span>

                                </a>

                            @endif

                        </div>

                    @endif

                </nav>

            </div>


            <!-- =====================================================
                 USUARIO / SESIÓN
            ====================================================== -->
            <div class="p-4
                        border-t border-slate-800/80
                        bg-[#090d16]">


                <div class="flex items-center justify-between gap-3">


                    <!-- INFORMACIÓN DEL USUARIO -->
                    <div class="flex items-center gap-3 min-w-0">


                        <!-- AVATAR -->
                        <div class="relative flex-shrink-0">

                            <div class="h-9 w-9
                                        rounded-xl
                                        bg-gradient-to-br from-emerald-400 to-emerald-600
                                        text-white
                                        flex items-center justify-center
                                        font-extrabold
                                        text-xs uppercase
                                        shadow-md shadow-emerald-900/30">

                                {{ substr(auth()->user()->nombre ?? 'SA', 0, 2) }}

                            </div>


                            <!-- Estado online -->
                            <span class="absolute -right-0.5 -bottom-0.5
                                         h-2.5 w-2.5
                                         rounded-full
                                         bg-emerald-400
                                         border-2 border-[#090d16]">
                            </span>

                        </div>


                        <!-- TEXTO -->
                        <div class="text-xs min-w-0">

                            <p class="text-white
                                      font-bold
                                      leading-tight
                                      truncate">

                                {{ auth()->user()->nombre ?? 'Usuario' }}

                            </p>


                            <p class="text-[10px]
                                      text-slate-400
                                      font-semibold
                                      mt-0.5
                                      truncate">

                                {{ auth()->user()->rol->nombre ?? 'Administrador' }}

                            </p>

                        </div>

                    </div>


                    <!-- CERRAR SESIÓN -->
                    <form method="POST" action="{{ route('logout') }}">

                        @csrf

                        <button
                            type="submit"
                            title="Cerrar sesión"

                            class="group
                                   flex items-center justify-center
                                   h-9 w-9
                                   rounded-xl
                                   text-slate-400
                                   hover:text-rose-400
                                   hover:bg-rose-500/10
                                   transition-all duration-200
                                   cursor-pointer">


                            <svg
                                class="w-5 h-5 transition-transform duration-200 group-hover:translate-x-0.5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />

                            </svg>

                        </button>

                    </form>

                </div>

            </div>

        </aside>

    @endauth


    <!-- =====================================================
         CONTENIDO
    ====================================================== -->
    <main class="flex-1 overflow-y-auto {{ auth()->check() ? 'p-8' : 'p-0 flex items-center justify-center min-h-screen bg-slate-100' }}">

        @if (isset($slot))

            {{ $slot }}

        @else

            @yield('content')

        @endif

    </main>


    @livewireScripts

</body>
</html>