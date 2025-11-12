<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Llanta;

class LlantaSeeder extends Seeder
{
    public function run(): void
    {
        Llanta::create([
            'MARCA' => 'Michelin',
            'MODELO_LLANTA' => 'Pilot Sport',
            'MEDIDA_RIN' => '18',
            'MEDIDA_LLANTA' => '225/45R18',
            'PRECIO' => 125.50,
            'IMAGEN' => null,
            'CONDICION' => 'nueva',
            'TIPO_VEHICULO' => 'Auto',
            'STOCK' => 15,
        ]);

        Llanta::create([
            'MARCA' => 'Goodyear',
            'MODELO_LLANTA' => 'EfficientGrip',
            'MEDIDA_RIN' => '16',
            'MEDIDA_LLANTA' => '195/65R16',
            'PRECIO' => 89.99,
            'IMAGEN' => null,
            'CONDICION' => 'nueva',
            'TIPO_VEHICULO' => 'Auto',
            'STOCK' => 20,
        ]);

        Llanta::create([
            'MARCA' => 'Pirelli',
            'MODELO_LLANTA' => 'Scorpion Verde',
            'MEDIDA_RIN' => '17',
            'MEDIDA_LLANTA' => '215/60R17',
            'PRECIO' => 110.00,
            'IMAGEN' => null,
            'CONDICION' => 'nueva',
            'TIPO_VEHICULO' => 'Auto',
            'STOCK' => 12,
        ]);

        Llanta::create([
            'MARCA' => 'Continental',
            'MODELO_LLANTA' => 'PremiumContact 6',
            'MEDIDA_RIN' => '19',
            'MEDIDA_LLANTA' => '245/40R19',
            'PRECIO' => 135.75,
            'IMAGEN' => null,
            'CONDICION' => 'nueva',
            'TIPO_VEHICULO' => 'Auto',
            'STOCK' => 8,
        ]);

        Llanta::create([
            'MARCA' => 'Bridgestone',
            'MODELO_LLANTA' => 'Turanza',
            'MEDIDA_RIN' => '15',
            'MEDIDA_LLANTA' => '185/60R15',
            'PRECIO' => 75.50,
            'IMAGEN' => null,
            'CONDICION' => 'nueva',
            'TIPO_VEHICULO' => 'Auto',
            'STOCK' => 25,
        ]);

        Llanta::create([
            'MARCA' => 'Dunlop',
            'MODELO_LLANTA' => 'SP Sport Maxx',
            'MEDIDA_RIN' => '20',
            'MEDIDA_LLANTA' => '245/35R20',
            'PRECIO' => 155.99,
            'IMAGEN' => null,
            'CONDICION' => 'nueva',
            'TIPO_VEHICULO' => 'Auto',
            'STOCK' => 5,
        ]);

        Llanta::create([
            'MARCA' => 'Firestone',
            'MODELO_LLANTA' => 'Firehawk',
            'MEDIDA_RIN' => '17',
            'MEDIDA_LLANTA' => '205/55R17',
            'PRECIO' => 95.00,
            'IMAGEN' => null,
            'CONDICION' => 'reacondicionada',
            'TIPO_VEHICULO' => 'Auto',
            'STOCK' => 10,
        ]);

        Llanta::create([
            'MARCA' => 'Hankook',
            'MODELO_LLANTA' => 'Kinergy GT',
            'MEDIDA_RIN' => '16',
            'MEDIDA_LLANTA' => '215/60R16',
            'PRECIO' => 85.50,
            'IMAGEN' => null,
            'CONDICION' => 'nueva',
            'TIPO_VEHICULO' => 'Auto',
            'STOCK' => 18,
        ]);
    }
}
