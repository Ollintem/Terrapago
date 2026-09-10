<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TerraPago - Sistema de Cobro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 antialiased font-sans flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#0B1527] text-gray-300 flex flex-col justify-between shrink-0 select-none">
        <div>
            <!-- LOGO -->
            <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-800">
                <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                </div>
                <div>
                    <h1 class="text-white font-bold tracking-wide leading-tight">TerraPago</h1>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Sistema de Cobro</p>
                </div>
            </div>

            <nav class="mt-4 px-3 space-y-6 text-sm">
                <!-- PRINCIPAL -->
                <div>
                    <span class="px-3 text-[11px] font-semibold tracking-wider text-gray-400 uppercase">Principal</span>
                    <div class="mt-2 space-y-1">
                        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                            <span>Tablero</span>
                        </a>
                        <a href="#" class="flex items-center justify-between px-3 py-2 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                            <span>Cobranza (Caja)</span>
                            <span class="bg-rose-500 text-white text-xs px-1.5 py-0.5 rounded-full font-bold">4</span>
                        </a>
                    </div>
                </div>

                <!-- CARTERA -->
                <div>
                    <span class="px-3 text-[11px] font-semibold tracking-wider text-gray-400 uppercase">Cartera</span>
                    <div class="mt-2 space-y-1">
                        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">Mis Clientes</a>
                        <a href="#" class="flex items-center justify-between px-3 py-2 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                            <span>Recordatorios</span>
                            <span class="bg-amber-500 text-white text-xs px-1.5 py-0.5 rounded-full font-bold">3</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">Terrenos</a>
                        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">Contratos</a>
                    </div>
                </div>

                <!-- ADMINISTRACIÓN -->
                <div>
                    <span class="px-3 text-[11px] font-semibold tracking-wider text-gray-400 uppercase">Administración</span>
                    <div class="mt-2 space-y-1">
                        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">Reportes</a>
                        <a href="/admin/usuarios" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->is('admin/usuarios*') ? 'bg-[#0f2c38] text-emerald-400 font-semibold border-l-4 border-emerald-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">Usuarios</a>
                        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">Permisos</a>
                        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">Auditoría</a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- USUARIO LOGUEADO -->
        <div class="p-4 border-t border-gray-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-emerald-700 text-white flex items-center justify-center font-bold text-xs">
                    {{ auth()->check() ? strtoupper(substr(auth()->user()->nombre, 0, 2)) : 'AD' }}
                </div>
                <div class="truncate">
                    <p class="text-sm font-medium text-white leading-tight truncate">
                        {{ auth()->check() ? auth()->user()->nombre : 'Alejandra Díaz' }}
                    </p>
                    <p class="text-xs text-gray-400">
                        {{ auth()->check() ? auth()->user()->rol->nombre : 'Administrador' }}
                    </p>
                </div>
            </div>
            @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-rose-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
            @endauth
        </div>
    </aside>

    <!-- CONTENIDO CENTRAL DINÁMICO -->
    <main class="flex-1 overflow-y-auto p-8">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
