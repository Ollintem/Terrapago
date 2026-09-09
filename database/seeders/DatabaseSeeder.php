<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Modulo;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $adminRol = Role::firstOrCreate(['nombre' => 'Administrador'], ['descripcion' => 'Acceso total']);
        Role::firstOrCreate(['nombre' => 'Cajero'], ['descripcion' => 'Cobranza y recibos']);

        // 2. Módulos que aparecerán en las filas de la matriz
        $modulos = [
            ['nombre' => 'Caja / Cobranza', 'clave' => 'caja'],
            ['nombre' => 'Clientes', 'clave' => 'clientes'],
            ['nombre' => 'Terrenos / Lotes', 'clave' => 'terrenos'],
            ['nombre' => 'Contratos', 'clave' => 'contratos'],
            ['nombre' => 'Auditoría', 'clave' => 'auditoria'],
        ];

        foreach ($modulos as $mod) {
            Modulo::firstOrCreate(['clave' => $mod['clave']], $mod);
        }

        // 3. Usuario Administrador base para poder probar
        User::firstOrCreate(
            ['email' => 'admin@terrapago.com'],
            [
                'nombre' => 'Administrador General',
                'password' => bcrypt('password123'),
                'rol_id' => $adminRol->id,
                'estado' => true,
            ]
        );
    }
}