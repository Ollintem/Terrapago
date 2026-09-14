<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Valida permisos granulares (RBAC)
     * Parámetros esperados: $modulo (ej. 'usuarios', 'caja') y $accion (ej. 'mostrar', 'alta', 'editar', 'eliminar')
     */
    public function handle(Request $request, Closure $next, string $modulo, string $accion): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Si es Super Administrador, tiene acceso total a todo
        if ($user->rol && in_array(strtolower($user->rol->nombre), ['administrador', 'super admin', 'superadministrador'])) {
            return $next($request);
        }

        // Consultar si el usuario tiene activo ese permiso en la base de datos
        $tienePermiso = $user->permisos()
            ->whereHas('modulo', function ($q) use ($modulo) {
                $q->where('clave', $modulo);
            })
            ->where($accion, true)
            ->exists();

        if (!$tienePermiso) {
            abort(403, 'No tienes autorización para realizar esta acción en el módulo: ' . ucfirst($modulo));
        }

        return $next($request);
    }
}