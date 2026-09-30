<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\UserPermissions;
use App\Livewire\Admin\CobranzaManagement;
use App\Http\Controllers\ReciboPagoController;
use App\Http\Controllers\ContratoPdfController;
/*
|--------------------------------------------------------------------------
| Rutas Públicas y Autenticación
|--------------------------------------------------------------------------
*/
Route::get('/contratos/{id}/pdf', [ContratoPdfController::class, 'generar'])
    ->name('contratos.pdf')
    ->middleware('permiso:contratos,mostrar');
// Redirección de la raíz '/' al Login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de Laravel UI
Auth::routes([
    'register' => false,
    'reset'    => false,
    'verify'   => false,
]);


/*
|--------------------------------------------------------------------------
| Rutas Protegidas por Autenticación
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Redirección después del Login
    |--------------------------------------------------------------------------
    */

    Route::get('/home', function () {

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Super Administrador
        |--------------------------------------------------------------------------
        */

        if (
            $user->rol &&
            in_array(
                strtolower($user->rol->nombre),
                [
                    'administrador',
                    'super admin',
                    'superadministrador'
                ]
            )
        ) {
            return redirect()->route('admin.usuarios.index');
        }

        /*
        |--------------------------------------------------------------------------
        | Mapa de módulos y sus rutas
        |--------------------------------------------------------------------------
        */

        $rutasPorModulo = [
            'usuarios'  => 'admin.usuarios.index',
            'roles'     => 'admin.roles.index',
            'caja'      => 'caja.index',
            'clientes'  => 'clientes.index',
            'terrenos'  => 'terrenos.index',
            'contratos' => 'contratos.index',
            'auditoria' => 'auditoria.index',
        ];

        /*
        |--------------------------------------------------------------------------
        | Módulos permitidos
        |--------------------------------------------------------------------------
        */

        $modulosPermitidos = $user->permisos()
            ->where('mostrar', true)
            ->whereHas('modulo')
            ->with('modulo')
            ->get()
            ->pluck('modulo.clave')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Buscar el primer módulo disponible
        |--------------------------------------------------------------------------
        */

        foreach ($rutasPorModulo as $clave => $nombreRuta) {

            if (
                in_array($clave, $modulosPermitidos) &&
                Route::has($nombreRuta)
            ) {
                return redirect()->route($nombreRuta);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sin módulos disponibles
        |--------------------------------------------------------------------------
        */

        abort(
            403,
            'Tu cuenta no tiene ningún módulo activo asignado. Solicita autorización al Administrador.'
        );

    })->name('home');


    /*
    |--------------------------------------------------------------------------
    | MÓDULO: USUARIOS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/usuarios',
        UserManagement::class
    )
        ->name('admin.usuarios.index')
        ->middleware('permiso:usuarios,mostrar');

    Route::get(
        '/admin/usuarios/{id}/permisos',
        UserPermissions::class
    )
        ->name('admin.usuarios.permisos')
        ->middleware('permiso:usuarios,editar');


    /*
    |--------------------------------------------------------------------------
    | MÓDULO: ROLES
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/roles',
        App\Livewire\Admin\RoleManagement::class
    )
        ->name('admin.roles.index')
        ->middleware('permiso:roles,mostrar');


    /*
    |--------------------------------------------------------------------------
    | MÓDULO: CLIENTES
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/clientes',
        App\Livewire\Admin\ClientManagement::class
    )
        ->name('clientes.index')
        ->middleware('permiso:clientes,mostrar');


    /*
    |--------------------------------------------------------------------------
    | MÓDULO: COBRANZA Y CAJA
    |--------------------------------------------------------------------------
    */

    Route::livewire(
        '/caja',
        CobranzaManagement::class
    )
        ->name('caja.index')
        ->middleware('permiso:caja,mostrar');


    /*
    |--------------------------------------------------------------------------
    | Recibos de pago
    |--------------------------------------------------------------------------
    */

    // Generar recibo PDF
    Route::get(
        '/caja/recibo/{operacionFolio}',
        [ReciboPagoController::class, 'generar']
    )
        ->name('caja.recibo')
        ->middleware('permiso:caja,mostrar');


    // Ver recibo en pantalla
    Route::get(
        '/caja/recibo/{operacionFolio}/ver',
        [ReciboPagoController::class, 'ver']
    )
        ->name('caja.recibo.ver')
        ->middleware('permiso:caja,mostrar');


    /*
    |--------------------------------------------------------------------------
    | MÓDULO: TERRENOS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/terrenos',
        App\Livewire\Admin\TerrenoManagement::class
    )
        ->name('terrenos.index')
        ->middleware('permiso:terrenos,mostrar');


    /*
    |--------------------------------------------------------------------------
    | MÓDULO: CONTRATOS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/contratos',
        App\Livewire\Admin\ContratoManagement::class
    )
        ->name('contratos.index')
        ->middleware('permiso:contratos,mostrar');

});