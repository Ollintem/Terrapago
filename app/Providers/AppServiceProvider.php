<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(Login::class, function ($event) {
            $user = $event->user;

            // 1. Validar si está inactivo
            if (!$user->activo) {
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();
                abort(403, 'Tu cuenta se encuentra inactiva. Contacta al Administrador.');
            }

            // 2. Registrar la marca de tiempo de último acceso
            $user->update([
                'ultimo_acceso' => now(),
            ]);
        });
    }
}