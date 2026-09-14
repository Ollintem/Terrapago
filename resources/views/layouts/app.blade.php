<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TerraPago - Sistema de Cobro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex h-screen overflow-hidden">

    @auth
        @php
            $esAdmin = auth()->user()->rol && in_array(strtolower(auth()->user()->rol->nombre), ['administrador', 'super admin', 'superadministrador']);
            
            // Función auxiliar inline para verificar permisos sin duplicar código
            $tieneAcceso = function($claveModulo) use ($esAdmin) {
                if ($esAdmin) return true;
                return auth()->user()->permisos()
                    ->whereHas('modulo', fn($q) => $q->where('clave', $claveModulo))
                    ->where('mostrar', true)
                    ->exists();
            };
        @endphp

        <!-- SIDEBAR: Solo se muestra si el usuario está autenticado -->
        <aside class="w-64 bg-[#0f172a] text-slate-300 flex flex-col justify-between flex-shrink-0">
            <div>
                <!-- Header Marca -->
                <div class="p-6 flex items-center gap-3">
                    <div class="h-10 w-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-emerald-500/30">
                        TP
                    </div>
                    <div>
                        <h1 class="text-white font-bold tracking-wide">TerraPago</h1>
                        <p class="text-[11px] text-slate-400 font-medium tracking-wider uppercase">Sistema de Cobro</p>
                    </div>
                </div>

                <!-- Navegación -->
                <nav class="px-4 space-y-6 text-sm">
                    <!-- SECCIÓN PRINCIPAL -->
                    <div>
                        <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Principal</p>
                        
                        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">
                            <span>Tablero</span>
                        </a>

                        @if($tieneAcceso('caja'))
                            <a href="#" class="flex items-center justify-between px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">
                                <span>Cobranza (Caja)</span>
                                <span class="bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">4</span>
                            </a>
                        @endif
                    </div>

                    <!-- SECCIÓN CARTERA -->
                    @if($tieneAcceso('clientes') || $tieneAcceso('terrenos') || $tieneAcceso('contratos'))
                        <div>
                            <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Cartera</p>
                            
                            @if($tieneAcceso('clientes'))
                                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">
                                    <span>Mis Clientes</span>
                                </a>
                            @endif

                            @if($tieneAcceso('terrenos'))
                                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">
                                    <span>Terrenos</span>
                                </a>
                            @endif

                            @if($tieneAcceso('contratos'))
                                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">
                                    <span>Contratos</span>
                                </a>
                            @endif
                        </div>
                    @endif

                    <!-- SECCIÓN ADMINISTRACIÓN -->
                    @if($tieneAcceso('usuarios') || $tieneAcceso('roles') || $tieneAcceso('auditoria'))
                        <div>
                            <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Administración</p>
                            
                            @if($tieneAcceso('usuarios'))
                                <a href="{{ route('admin.usuarios.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.usuarios.*') ? 'bg-emerald-600/10 text-emerald-400 font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-white transition' }}">
                                    <span>Usuarios</span>
                                </a>
                            @endif

                            @if($tieneAcceso('roles'))
                                <a href="{{ route('admin.roles.index') }}"
                                    class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.roles.*') ? 'bg-emerald-600/10 text-emerald-400 font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-white transition' }}">
                                    <span>Roles</span>
                                </a>
                            @endif

                            @if($tieneAcceso('auditoria'))
                                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">
                                    <span>Auditoría</span>
                                </a>
                            @endif
                        </div>
                    @endif
                </nav>
            </div>

            <!-- Sesión del usuario -->
            <div class="p-4 border-t border-slate-800 bg-[#090d16] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs uppercase">
                        {{ substr(auth()->user()->nombre ?? 'SA', 0, 2) }}
                    </div>
                    <div class="text-xs">
                        <p class="text-white font-semibold leading-tight">{{ auth()->user()->nombre ?? 'Usuario' }}</p>
                        <p class="text-[11px] text-slate-400">{{ auth()->user()->rol->nombre ?? 'Administrador' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Cerrar sesión" class="text-slate-400 hover:text-rose-400 transition p-1.5 rounded-lg hover:bg-slate-800 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </aside>
    @endauth

    {{-- CONTENIDO CENTRAL: Soporta tanto Livewire ($slot) como Blade clásico (@yield) --}}
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