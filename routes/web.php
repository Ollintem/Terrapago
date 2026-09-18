<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\UserPermissions;

/*
|--------------------------------------------------------------------------
| Rutas Públicas y Autenticación
|--------------------------------------------------------------------------
*/

// 1. Redirección de la raíz '/' al Login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Rutas de Laravel UI (Bloqueando registro y reseteo público)
Auth::routes([
    'register' => false,
    'reset'    => false,
    'verify'   => false,
]);

/*
|--------------------------------------------------------------------------
| Rutas Protegidas por Autenticación (Requiere Iniciar Sesión)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {


   // Redirección inteligente post-login: busca dinámicamente el primer módulo disponible
    Route::get('/home', function () {
        $user = auth()->user();

        // 1. Acceso total: si es Super Administrador, su pantalla principal siempre es Usuarios
        if ($user->rol && in_array(strtolower($user->rol->nombre), ['administrador', 'super admin', 'superadministrador'])) {
            return redirect()->route('admin.usuarios.index');
        }

        // 2. Mapa de rutas asociadas a cada clave de módulo
        $rutasPorModulo = [
            'usuarios'  => 'admin.usuarios.index',
            'roles'     => 'admin.roles.index',
            'caja'      => 'caja.index',
            'clientes'  => 'clientes.index',
            'terrenos'  => 'terrenos.index',
            'contratos' => 'contratos.index',
            'auditoria' => 'auditoria.index',
        ];

        // 3. Obtener todas las claves de módulos donde el usuario tiene 'mostrar' => true
        $modulosPermitidos = $user->permisos()
            ->where('mostrar', true)
            ->whereHas('modulo')
            ->with('modulo')
            ->get()
            ->pluck('modulo.clave')
            ->toArray();

        // 4. Recorrer el catálogo en orden de prioridad y redirigir a la primera ruta existente
        foreach ($rutasPorModulo as $clave => $nombreRuta) {
            if (in_array($clave, $modulosPermitidos) && Route::has($nombreRuta)) {
                return redirect()->route($nombreRuta);
            }
        }

        // 5. Si no tiene permisos asignados o sus módulos aún no tienen ruta implementada
        abort(403, 'Tu cuenta no tiene ningún módulo activo asignado. Solicita autorización al Administrador.');
    })->name('home');

    /*
    |--------------------------------------------------------------------------
    | MÓDULOS CON PROTECCIÓN GRANULAR (RBAC)
    |--------------------------------------------------------------------------
    */

    // ==========================================
    // MÓDULO: USUARIOS (Alias: usuarios)
    // ==========================================
    // Listado de personal (mostrar)
    Route::get('/admin/usuarios', UserManagement::class)
        ->name('admin.usuarios.index')
        ->middleware('permiso:usuarios,mostrar');

    // Matriz de permisos por usuario (editar)
    Route::get('/admin/usuarios/{id}/permisos', UserPermissions::class)
        ->name('admin.usuarios.permisos')
        ->middleware('permiso:usuarios,editar');


    // ==========================================
    // MÓDULO: ROLES (Alias: roles) - Estructura CRUD   
    // ==========================================
    Route::get('/admin/roles', App\Livewire\Admin\RoleManagement::class)
        ->name('admin.roles.index')
        ->middleware('permiso:roles,mostrar');

    // ==========================================
    // MÓDULO: CLIENTES (Alias: clientes) - Estructura CRUD
    // ==========================================
    Route::get('/admin/clientes', App\Livewire\Admin\ClientManagement::class)
        ->name('clientes.index')
        ->middleware('permiso:clientes,mostrar');

    // ==========================================
    // MÓDULO: COBRANZA Y CAJA (Alias: caja)
    // ==========================================
    /*
    Route::get('/caja', [CajaController::class, 'index'])
        ->name('caja.index')
        ->middleware('permiso:caja,mostrar');
    Route::post('/caja/cobro', [CajaController::class, 'cobrar'])
        ->name('caja.cobrar')
        ->middleware('permiso:caja,alta');
    */


    // ==========================================
    // MÓDULO: TERRENOS (Alias: terrenos)
    // ==========================================
    Route::get('/admin/terrenos', App\Livewire\Admin\TerrenoManagement::class)
        ->name('terrenos.index')
        ->middleware('permiso:terrenos,mostrar');
    
    // ==========================================
    // MÓDULO: CONTRATOS (Alias: contratos)
    // ==========================================
    Route::get('/admin/contratos', App\Livewire\Admin\ContratoManagement::class)
        ->name('contratos.index')
        ->middleware('permiso:contratos,mostrar');
        
});