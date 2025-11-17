<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles base si no existen
        $roles = [
            'ADMIN',
            'usuario',
            'conductor',
            'mecanico',
            'repartidor',
            'asistencia_vial'
        ];

        foreach ($roles as $nombre) {
            Rol::firstOrCreate(
                ['NOMBRE' => $nombre]
            );
        }
        
        echo "✅ Roles creados correctamente\n";
    }
}
