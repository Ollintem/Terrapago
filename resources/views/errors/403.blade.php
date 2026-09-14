<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Restringido - TerraPago</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4 antialiased text-slate-200">
    <div class="max-w-md w-full bg-slate-800 border border-slate-700 rounded-2xl p-8 text-center shadow-2xl space-y-6">
        <div class="inline-flex h-16 w-16 bg-rose-500/10 text-rose-500 rounded-2xl items-center justify-center text-3xl font-bold border border-rose-500/20">
            ⚠️
        </div>
        
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight">403</h1>
            <h2 class="text-lg font-bold text-slate-100 mt-1">Acceso no autorizado</h2>
            <p class="text-sm text-slate-400 mt-2">
                {{ $exception->getMessage() ?: 'No tienes autorización para acceder a esta sección del sistema.' }}
            </p>
        </div>

        <div class="p-3 bg-slate-900/60 rounded-xl border border-slate-700/50 text-xs text-slate-400 text-left">
            <p><strong class="text-slate-300">Usuario:</strong> {{ auth()->user()->nombre ?? 'Invitado' }}</p>
            <p><strong class="text-slate-300">Rol:</strong> {{ auth()->user()->rol->nombre ?? 'Sin Rol' }}</p>
        </div>

        <div class="flex flex-col gap-2 pt-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-2.5 px-4 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white font-semibold text-sm rounded-xl transition shadow-lg shadow-rose-600/20 cursor-pointer">
                    Cerrar Sesión e Ir al Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>