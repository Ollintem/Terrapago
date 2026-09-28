<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TerraPago - Sistema de Cobro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body 
    x-data="{ 
        // Si no existe valor previo en localStorage, arranca cerrado (false)
        sidebarAbierto: localStorage.getItem('sidebarAbierto') === 'true',
        toggleSidebar() {
            this.sidebarAbierto = !this.sidebarAbierto;
            localStorage.setItem('sidebarAbierto', this.sidebarAbierto);
        }
    }" 
    class="bg-slate-50 text-slate-800 antialiased flex h-screen overflow-hidden">

    @auth
        @php
            $esAdmin = auth()->user()->rol && in_array(strtolower(auth()->user()->rol->nombre), ['administrador', 'super admin', 'superadministrador']);
            
            $tieneAcceso = function($claveModulo) use ($esAdmin) {
                if ($esAdmin) return true;
                return auth()->user()->permisos()
                    ->whereHas('modulo', fn($q) => $q->where('clave', $claveModulo))
                    ->where('mostrar', true)
                    ->exists();
            };
        @endphp

        <!-- BARRA LATERAL (SIDEBAR) CON COLAPSO TOTAL -->
        <aside 
            :class="sidebarAbierto ? 'w-64' : 'w-0'"
            class="bg-[#0f172a] text-slate-300 flex flex-col justify-between flex-shrink-0 transition-all duration-300 ease-in-out overflow-hidden z-30">

            <div class="w-64 flex flex-col justify-between h-full">
                <div>
                    <!-- Encabezado y Marca -->
                    <div class="p-6 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-emerald-500/30 flex-shrink-0">
                                TP
                            </div>
                            <div class="truncate">
                                <h1 class="text-white font-bold tracking-wide">TerraPago</h1>
                                <p class="text-[11px] text-slate-400 font-medium tracking-wider uppercase">Sistema de Cobro</p>
                            </div>
                        </div>

                        <!-- BOTÓN HAMBURGUESA DENTRO DEL SIDEBAR (OCULTAR) -->
                        <button 
                            type="button"
                            @click="toggleSidebar()"
                            title="Ocultar menú lateral"
                            class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 cursor-pointer transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
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
                                    <a href="{{ route('clientes.index') }}" 
                                       class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('clientes.*') ? 'bg-emerald-600/10 text-emerald-400 font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-white transition' }}">
                                        <span>Mis Clientes</span>
                                    </a>
                                @endif

                                @if($tieneAcceso('terrenos'))
                                    <a href="{{ route('terrenos.index') }}" 
                                       class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('terrenos.*') ? 'bg-emerald-600/10 text-emerald-400 font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-white transition' }}">
                                        <span>Terrenos</span>
                                    </a>
                                @endif

                                @if($tieneAcceso('contratos'))
                                    <a href="{{ route('contratos.index') }}" 
                                       class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('contratos.*') ? 'bg-emerald-600/10 text-emerald-400 font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-white transition' }}">
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
                                    <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.roles.*') ? 'bg-emerald-600/10 text-emerald-400 font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-white transition' }}">
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
                        <div class="h-9 w-9 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs uppercase flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->nombre ?? auth()->user()->name ?? 'SA', 0, 2)) }}
                        </div>
                        <div class="text-xs truncate">
                            <p class="text-white font-semibold leading-tight truncate">{{ auth()->user()->nombre ?? auth()->user()->name ?? 'Usuario' }}</p>
                            <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()->rol->nombre ?? 'Administrador' }}</p>
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
            </div>
        </aside>
    @endauth

    <!-- ÁREA DE CONTENIDO PRINCIPAL Y BOTÓN DE APERTURA -->
    <main class="flex-1 flex flex-col overflow-y-auto min-w-0">
        
        @auth
            <!-- ENCABEZADO SUPERIOR PARA MOSTRAR EL BOTÓN CUANDO EL SIDEBAR ESTÁ OCULTO -->
            <div x-show="!sidebarAbierto" class="p-4 pb-0 flex items-center">
                <button 
                    type="button"
                    @click="toggleSidebar()"
                    title="Mostrar menú lateral"
                    class="p-2 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:text-emerald-600 shadow-sm cursor-pointer transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        @endauth

        <div class="{{ auth()->check() ? 'p-8 pt-4' : 'p-0 flex items-center justify-center min-h-screen bg-slate-100' }}">
            @if (isset($slot))
                {{ $slot }}
            @else
                @yield('content')
            @endif
        </div>
    </main>

    @livewireScripts
</body>
</html>