<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Se ejecutan en orden estricto de dependencias
        $this->call([
            ModuloSeeder::class,
            UserSeeder::class,
        ]);
    }
}