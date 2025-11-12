<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Marcas de carro
        $marcas = ['Toyota', 'Honda', 'Mazda', 'Hyundai', 'Kia'];
        foreach ($marcas as $nombre) {
            DB::table('marcas_carro')->insert([
                'NOMBRE' => $nombre,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Usuarios
        for ($i = 1; $i <= 5; $i++) {
            DB::table('usuarios')->insert([
                'NOMBRES' => "Usuario$i",
                'APELLIDOS' => "Apellido$i",
                'CORREO' => "usuario$i@demo.com",
                'DUI' => "0000000$i-0",
                'TELEFONO' => "7000-000$i",
                'DIRECCION' => "Zona $i",
                'PASSWORD' => Hash::make('123456'),
                'ROL' => 'CLIENTE',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Llantas
        for ($i = 1; $i <= 5; $i++) {
            DB::table('llantas')->insert([
                'MARCA' => "Marca$i",
                'MEDIDA' => "195/65R1$i",
                'TIPO_VEHICULO' => "Tipo$i",
                'ESTADO' => $i % 2 == 0 ? 'USADA' : 'NUEVA',
                'PRECIO' => 100 + ($i * 25),
                'IMAGEN' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Vehículos
        for ($i = 1; $i <= 5; $i++) {
            DB::table('vehiculos')->insert([
                'USUARIO_ID' => $i,
                'MARCA' => $marcas[$i - 1],
                'MODELO' => "Modelo$i",
                'ANIO' => "20$i$i",
                'MEDIDA_RIN' => "1$i",
                'MEDIDA_LLANTA' => "195/65R1$i",
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
