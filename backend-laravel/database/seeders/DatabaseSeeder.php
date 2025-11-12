<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */


    public function run(): void
    {
        // Ejecutar siempre los datos de prueba
        $this->call([
            DatosPruebaSeeder::class,
            // DemoSeeder::class, // Deshabilitado temporalmente por tabla faltante
        ]);
    }

}
