<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Modulo;

class ModuloSeeder extends Seeder
{
    public function run(): void
    {
        $modulos = [
            ['nombre' => 'Usuarios', 'clave' => 'usuarios'],
            ['nombre' => 'Roles', 'clave' => 'roles'],
            ['nombre' => 'Caja / Cobranza', 'clave' => 'caja'],
            ['nombre' => 'Clientes', 'clave' => 'clientes'],
            ['nombre' => 'Terrenos / Lotes', 'clave' => 'terrenos'],
            ['nombre' => 'Contratos', 'clave' => 'contratos'],
            ['nombre' => 'Auditoría', 'clave' => 'auditoria'],
        ];

        foreach ($modulos as $mod) {
            Modulo::updateOrCreate(
                ['clave' => $mod['clave']],
                ['nombre' => $mod['nombre']]
            );
        }
    }
}