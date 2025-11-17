<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class RepartidoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "🚚 Insertando repartidores de prueba...\n";
        
        $repartidores = [
            [
                'NOMBRES' => 'Juan Pérez',
                'APELLIDOS' => 'López',
                'CORREO' => 'juan.perez@llanteria.com',
                'TELEFONO' => '7772-1234',
                'DIRECCION' => 'Calle Principal, San Salvador',
                'DUI' => '01234567-8',
                'ROL' => 'repartidor'
            ],
            [
                'NOMBRES' => 'Carlos Mendoza',
                'APELLIDOS' => 'García',
                'CORREO' => 'carlos.mendoza@llanteria.com',
                'TELEFONO' => '7773-5678',
                'DIRECCION' => 'Avenida 5, Santa Ana',
                'DUI' => '02345678-9',
                'ROL' => 'repartidor'
            ],
            [
                'NOMBRES' => 'Miguel Rodríguez',
                'APELLIDOS' => 'Sánchez',
                'CORREO' => 'miguel.rodriguez@llanteria.com',
                'TELEFONO' => '7774-9012',
                'DIRECCION' => 'Calle 3, San Miguel',
                'DUI' => '03456789-0',
                'ROL' => 'repartidor'
            ],
        ];

        foreach ($repartidores as $repartidor) {
            Usuario::updateOrCreate(
                ['CORREO' => $repartidor['CORREO']],
                [
                    'NOMBRES' => $repartidor['NOMBRES'],
                    'APELLIDOS' => $repartidor['APELLIDOS'],
                    'CORREO' => $repartidor['CORREO'],
                    'TELEFONO' => $repartidor['TELEFONO'],
                    'DIRECCION' => $repartidor['DIRECCION'],
                    'DUI' => $repartidor['DUI'],
                    'PASSWORD' => Hash::make('123456789'),
                    'ROL' => $repartidor['ROL']
                ]
            );
            echo "  ✅ Repartidor creado: {$repartidor['NOMBRES']}\n";
        }
    }
}
