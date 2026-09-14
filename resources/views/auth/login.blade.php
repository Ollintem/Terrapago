@extends('layouts.app')

@section('content')
<div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-xl border border-slate-100 my-auto">
    <!-- Encabezado y Logo -->
    <div class="text-center mb-8">
        <div class="inline-flex h-12 w-12 bg-emerald-500 rounded-xl items-center justify-center text-white font-bold text-xl shadow-lg shadow-emerald-500/30 mb-3">
            TP
        </div>
        <h2 class="text-2xl font-bold text-slate-800">Iniciar Sesión</h2>
        <p class="text-xs text-slate-400 mt-1 uppercase tracking-wider font-semibold">TerraPago &bull; Sistema de Cobro</p>
    </div>

    <!-- Formulario de Acceso -->
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Campo Correo Electrónico -->
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">
                Correo Electrónico
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition @error('email') border-rose-500 ring-rose-500 @enderror">
            
            @error('email')
                <p class="text-rose-600 text-xs mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Campo Contraseña -->
        <div>
            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">
                Contraseña
            </label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition @error('password') border-rose-500 ring-rose-500 @enderror"
                placeholder="••••••••">

            @error('password')
                <p class="text-rose-600 text-xs mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Botón de Ingreso -->
        <button type="submit" 
            class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-sm font-semibold rounded-lg shadow-md shadow-emerald-600/20 transition duration-150 cursor-pointer">
            Acceder al Sistema
        </button>
    </form>

    <div class="mt-8 text-center border-t border-slate-100 pt-4">
        <p class="text-[11px] text-slate-400">
            Acceso restringido únicamente a personal autorizado.
        </p>
    </div>
</div>
@endsection