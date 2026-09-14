<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica si el usuario está autenticado y si su rol es 'Administrador' o 'Super Admin'
        if ($request->user() && $request->user()->rol && in_array(strtolower($request->user()->rol->nombre), ['administrador', 'super admin', 'superadmin'])) {
            return $next($request);
        }

        // Si no tiene el rol, corta el acceso con un error 403 Forbidden
        abort(403, 'Acceso denegado: Se requieren permisos de Super Administrador.');
    }
}