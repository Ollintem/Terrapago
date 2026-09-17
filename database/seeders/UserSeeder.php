<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\Modulo;
use App\Models\User;
use App\Models\Permiso;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles del Sistema
        $adminRol = Role::updateOrCreate(
            ['nombre' => 'Administrador'],
            ['descripcion' => 'Acceso y control total del sistema']
        );

        $cajeroRol = Role::updateOrCreate(
            ['nombre' => 'Cajero'],
            ['descripcion' => 'Cobranza y emisión de recibos']
        );

        // 2. Crear Super Administrador
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@terrapago.com'],
            [
                'nombre' => 'Super Administrador',
                'password' => Hash::make('password123'),
                'rol_id' => $adminRol->id,
                'estado' => true,
            ]
        );

        // 3. Crear Usuario Cajero de prueba
        $cajero = User::updateOrCreate(
            ['email' => 'cajero@terrapago.com'],
            [
                'nombre' => 'Cajero de Turno',
                'password' => Hash::make('password123'),
                'rol_id' => $cajeroRol->id,
                'estado' => true,
            ]
        );

        // 4. Asignar todos los permisos al Super Administrador
        $todosLosModulos = Modulo::all();
        foreach ($todosLosModulos as $modulo) {
            Permiso::updateOrCreate(
                [
                    'user_id'   => $superAdmin->id,
                    'modulo_id' => $modulo->id,
                ],
                [
                    'mostrar'  => true,
                    'crear'    => true,  // <-- AQUÍ: cambiar 'alta' por 'crear'
                    'editar'   => true,
                    'eliminar' => true,
                ]
            );
        }

        // 5. Asignar permisos iniciales al Cajero
        $moduloCaja = Modulo::where('clave', 'caja')->first();
        if ($moduloCaja) {
            Permiso::updateOrCreate(
                [
                    'user_id'   => $cajero->id,
                    'modulo_id' => $moduloCaja->id,
                ],
                [
                    'mostrar'  => true,
                    'crear'    => true,  // <-- AQUÍ: cambiar 'alta' por 'crear'
                    'editar'   => false,
                    'eliminar' => false,
                ]
            );
        }

        $moduloClientes = Modulo::where('clave', 'clientes')->first();
        if ($moduloClientes) {
            Permiso::updateOrCreate(
                [
                    'user_id'   => $cajero->id,
                    'modulo_id' => $moduloClientes->id,
                ],
                [
                    'mostrar'  => true,
                    'crear'    => false, // <-- AQUÍ: cambiar 'alta' por 'crear'
                    'editar'   => false,
                    'eliminar' => false,
                ]
            );
        }
    }
}