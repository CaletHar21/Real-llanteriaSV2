<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Usuario;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Llanta;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatosPruebaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuarios
        $this->crearUsuarios();
        
        // Crear marcas
        $marcas = $this->crearMarcas();
        
        // Crear modelos
        $this->crearModelos($marcas);
        
        // Crear llantas
        $this->crearLlantas();
        
        echo "✅ Datos de prueba insertados correctamente\n";
    }
    
    private function crearUsuarios()
    {
        echo "📝 Insertando usuarios...\n";
        
        // Usuario Admin de prueba
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Administrador',
                'email' => 'admin@test.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        Usuario::updateOrCreate(
            ['CORREO' => 'admin@test.com'],
            [
                'NOMBRES' => 'Admin',
                'APELLIDOS' => 'Sistema',
                'CORREO' => 'admin@test.com',
                'DUI' => '00000000-0',
                'TELEFONO' => '0000-0000',
                'DIRECCION' => 'Sistema',
                'PASSWORD' => Hash::make('admin123'),
                'ROL' => 'ADMIN'
            ]
        );
        
        // Usuario Caleth en tabla users (Laravel estándar)
        User::updateOrCreate(
            ['email' => 'caleth.torrez17@itca.edu.sv'],
            [
                'name' => 'Caleth Abimael Torrez Ramirez',
                'email' => 'caleth.torrez17@itca.edu.sv',
                'password' => Hash::make('123456789'),
                'email_verified_at' => now(),
            ]
        );
        
        // Usuario Cointra en tabla users (Laravel estándar)
        User::updateOrCreate(
            ['email' => 'cointra@llanteria.com'],
            [
                'name' => 'Cointra',
                'email' => 'cointra@llanteria.com',
                'password' => Hash::make('123456789'),
                'email_verified_at' => now(),
            ]
        );

        // Usuario Cliente en tabla users (Laravel estándar)
        User::updateOrCreate(
            ['email' => 'cliente@llanteria.com'],
            [
                'name' => 'Cliente Prueba',
                'email' => 'cliente@llanteria.com',
                'password' => Hash::make('123456789'),
                'email_verified_at' => now(),
            ]
        );

        // Usuario Caleth en tabla usuarios (personalizada)
        Usuario::updateOrCreate(
            ['CORREO' => 'caleth.torrez17@itca.edu.sv'],
            [
                'NOMBRES' => 'Caleth Abimael',
                'APELLIDOS' => 'Torrez Ramirez',
                'CORREO' => 'caleth.torrez17@itca.edu.sv',
                'DUI' => '12345678-9',
                'TELEFONO' => '1234566789',
                'DIRECCION' => 'San Salvador, El Salvador',
                'PASSWORD' => Hash::make('123456789'),
                'ROL' => 'ADMIN'
            ]
        );

        // Usuario Cointra en tabla usuarios (personalizada)
        Usuario::updateOrCreate(
            ['CORREO' => 'cointra@llanteria.com'],
            [
                'NOMBRES' => 'Ana María',
                'APELLIDOS' => 'Cointra',
                'CORREO' => 'cointra@llanteria.com',
                'DUI' => '98765432-1',
                'TELEFONO' => '1234566789',
                'DIRECCION' => 'Santa Ana, El Salvador',
                'PASSWORD' => Hash::make('123456789'),
                'ROL' => 'AGENTE'
            ]
        );

        // Usuario de prueba CLIENTE
        Usuario::updateOrCreate(
            ['CORREO' => 'cliente@llanteria.com'],
            [
                'NOMBRES' => 'Juan Carlos',
                'APELLIDOS' => 'Pérez López',
                'CORREO' => 'cliente@llanteria.com',
                'DUI' => '11223344-5',
                'TELEFONO' => '7890123456',
                'DIRECCION' => 'Soyapango, San Salvador',
                'PASSWORD' => Hash::make('123456789'),
                'ROL' => 'CLIENTE'
            ]
        );

        // Usuario Jorge Enrique Alfaro
        Usuario::updateOrCreate(
            ['CORREO' => 'jalfaro@itca.edu.sv'],
            [
                'NOMBRES' => 'Jorge Enrique',
                'APELLIDOS' => 'Alfaro',
                'CORREO' => 'jalfaro@itca.edu.sv',
                'DUI' => '12345678-0',
                'TELEFONO' => '7777-8888',
                'DIRECCION' => 'San Salvador, El Salvador',
                'PASSWORD' => Hash::make('123456789'),
                'ROL' => 'ADMIN'
            ]
        );
        
        echo "✅ Usuarios creados en ambas tablas: users y usuarios\n";
    }
    
    private function crearMarcas()
    {
        echo "📝 Insertando marcas de vehículos...\n";
        
        $marcasData = [
            'Toyota',
            'Honda',
            'Ford',
            'Chevrolet',
            'Nissan',
            'Hyundai',
            'Mazda',
            'Volkswagen',
            'Kia',
            'Mitsubishi'
        ];
        
        $marcas = [];
        foreach ($marcasData as $nombreMarca) {
            $marca = Marca::updateOrCreate(
                ['NOMBRE' => $nombreMarca],
                ['NOMBRE' => $nombreMarca]
            );
            $marcas[$nombreMarca] = $marca;
        }
        
        echo "✅ Marcas de vehículos creadas: " . implode(', ', $marcasData) . "\n";
        return $marcas;
    }
    
    private function crearModelos($marcas)
    {
        echo "📝 Insertando modelos de vehículos...\n";
        
        $modelosData = [
            'Toyota' => ['Corolla', 'Camry', 'RAV4', 'Hilux', 'Yaris'],
            'Honda' => ['Civic', 'Accord', 'CR-V', 'Fit', 'Pilot'],
            'Ford' => ['Fiesta', 'Focus', 'Fusion', 'Escape', 'F-150'],
            'Chevrolet' => ['Spark', 'Aveo', 'Cruze', 'Equinox', 'Silverado'],
            'Nissan' => ['Sentra', 'Altima', 'Versa', 'X-Trail', 'Frontier'],
            'Hyundai' => ['Accent', 'Elantra', 'Tucson', 'Santa Fe', 'Kona'],
            'Mazda' => ['Mazda2', 'Mazda3', 'CX-3', 'CX-5', 'CX-9'],
            'Volkswagen' => ['Polo', 'Jetta', 'Golf', 'Tiguan', 'Amarok'],
            'Kia' => ['Rio', 'Forte', 'Sportage', 'Sorento', 'Soul'],
            'Mitsubishi' => ['Mirage', 'Lancer', 'Outlander', 'Montero', 'L200']
        ];
        
        foreach ($modelosData as $marcaNombre => $modelos) {
            if (isset($marcas[$marcaNombre])) {
                foreach ($modelos as $modeloNombre) {
                    Modelo::updateOrCreate(
                        [
                            'MARCA_ID' => $marcas[$marcaNombre]->id,
                            'MODELO' => $modeloNombre
                        ],
                        [
                            'MARCA_ID' => $marcas[$marcaNombre]->id,
                            'MODELO' => $modeloNombre
                        ]
                    );
                }
            }
        }
        
        echo "✅ Modelos de vehículos creados para todas las marcas\n";
    }
    
    private function crearLlantas()
    {
        echo "📝 Insertando llantas de prueba...\n";
        
        $llantasData = [
            [
                'MARCA' => 'Bridgestone',
                'MODELO_LLANTA' => 'Turanza T005',
                'MEDIDA_RIN' => '16',
                'MEDIDA_LLANTA' => '205/55R16',
                'PRECIO' => 175.50,
                'IMAGEN' => 'https://images.unsplash.com/photo-1544375665-2777e3b4ed1e?w=800&h=600&fit=crop&crop=center',
                'CONDICION' => 'nueva',
                'TIPO_VEHICULO' => 'Compacto',
                'STOCK' => 22
            ],
            [
                'MARCA' => 'Michelin',
                'MODELO_LLANTA' => 'Pilot Sport 4S',
                'MEDIDA_RIN' => '18',
                'MEDIDA_LLANTA' => '225/45R18',
                'PRECIO' => 285.99,
                'IMAGEN' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=600&fit=crop&crop=center',
                'CONDICION' => 'nueva',
                'TIPO_VEHICULO' => 'Sedán',
                'STOCK' => 15
            ],
            [
                'MARCA' => 'Michelin',
                'MODELO_LLANTA' => 'Energy Saver',
                'MEDIDA_RIN' => '15',
                'MEDIDA_LLANTA' => '195/65R15',
                'PRECIO' => 145.50,
                'IMAGEN' => 'https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?w=800&h=600&fit=crop&crop=center',
                'CONDICION' => 'nueva',
                'TIPO_VEHICULO' => 'Económico',
                'STOCK' => 25
            ],
            [
                'MARCA' => 'Michelin',
                'MODELO_LLANTA' => 'X-Ice Snow',
                'MEDIDA_RIN' => '16',
                'MEDIDA_LLANTA' => '205/55R16',
                'PRECIO' => 195.75,
                'IMAGEN' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop&crop=center',
                'CONDICION' => 'nueva',
                'TIPO_VEHICULO' => 'SUV',
                'STOCK' => 18
            ],
            [
                'MARCA' => 'Bridgestone',
                'MODELO_LLANTA' => 'Potenza Sport',
                'MEDIDA_RIN' => '19',
                'MEDIDA_LLANTA' => '245/35R19',
                'PRECIO' => 325.99,
                'IMAGEN' => 'https://images.unsplash.com/photo-1563298723-dcfebaa392e3?w=800&h=600&fit=crop&crop=center',
                'CONDICION' => 'nueva',
                'TIPO_VEHICULO' => 'Deportivo',
                'STOCK' => 12
            ],
            [
                'MARCA' => 'Bridgestone',
                'MODELO_LLANTA' => 'Ecopia EP422',
                'MEDIDA_RIN' => '14',
                'MEDIDA_LLANTA' => '185/65R14',
                'PRECIO' => 125.25,
                'IMAGEN' => 'https://images.unsplash.com/photo-1617469165786-8007eda4bf50?w=800&h=600&fit=crop&crop=center',
                'CONDICION' => 'nueva',
                'TIPO_VEHICULO' => 'Ciudad',
                'STOCK' => 30
            ],
            [
                'MARCA' => 'Pirelli',
                'MODELO_LLANTA' => 'P Zero',
                'MEDIDA_RIN' => '20',
                'MEDIDA_LLANTA' => '245/40R20',
                'PRECIO' => 385.75,
                'IMAGEN' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop&crop=center',
                'CONDICION' => 'nueva',
                'TIPO_VEHICULO' => 'Deportivo',
                'STOCK' => 8
            ],
            [
                'MARCA' => 'Pirelli',
                'MODELO_LLANTA' => 'Cinturato P7',
                'MEDIDA_RIN' => '17',
                'MEDIDA_LLANTA' => '215/50R17',
                'PRECIO' => 225.50,
                'IMAGEN' => 'https://images.unsplash.com/photo-1593941707882-a5bac6861d75?w=800&h=600&fit=crop&crop=center',
                'CONDICION' => 'reacondicionada',
                'TIPO_VEHICULO' => 'Familiar',
                'STOCK' => 14
            ],
            [
                'MARCA' => 'Goodyear',
                'MODELO_LLANTA' => 'Assurance Weather',
                'MEDIDA_RIN' => '16',
                'MEDIDA_LLANTA' => '205/60R16',
                'PRECIO' => 185.75,
                'IMAGEN' => 'https://images.unsplash.com/photo-1615906357749-f4c6ca50c7a3?w=800&h=600&fit=crop&crop=center',
                'CONDICION' => 'nueva',
                'TIPO_VEHICULO' => 'Crossover',
                'STOCK' => 20
            ],
            [
                'MARCA' => 'Continental',
                'MODELO_LLANTA' => 'ContiSportContact 6',
                'MEDIDA_RIN' => '19',
                'MEDIDA_LLANTA' => '255/35R19',
                'PRECIO' => 345.50,
                'IMAGEN' => 'https://images.unsplash.com/photo-1605816421107-45ed1dfca1ba?w=800&h=600&fit=crop&crop=center',
                'CONDICION' => 'nueva',
                'TIPO_VEHICULO' => 'Deportivo',
                'STOCK' => 10
            ],
            [
                'MARCA' => 'Continental',
                'MODELO_LLANTA' => 'PremiumContact 6',
                'MEDIDA_RIN' => '17',
                'MEDIDA_LLANTA' => '225/45R17',
                'PRECIO' => 215.25,
                'IMAGEN' => 'https://images.unsplash.com/photo-1558317374-067fb5f30001?w=800&h=600&fit=crop&crop=center',
                'CONDICION' => 'nueva',
                'TIPO_VEHICULO' => 'Ejecutivo',
                'STOCK' => 18
            ],
            [
                'MARCA' => 'Yokohama',
                'MODELO_LLANTA' => 'Advan Sport V105',
                'MEDIDA_RIN' => '18',
                'MEDIDA_LLANTA' => '225/40R18',
                'PRECIO' => 275.99,
                'IMAGEN' => 'https://images.unsplash.com/photo-1544264516-1bb3c4fe5c06?w=800&h=600&fit=crop&crop=center',
                'CONDICION' => 'reacondicionada',
                'TIPO_VEHICULO' => 'Coupé',
                'STOCK' => 12
            ],
            [
                'MARCA' => 'Toyo',
                'MODELO_LLANTA' => 'Proxes Sport',
                'MEDIDA_RIN' => '17',
                'MEDIDA_LLANTA' => '215/45R17',
                'PRECIO' => 195.50,
                'IMAGEN' => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=800&h=600&fit=crop&crop=center',
                'CONDICION' => 'nueva',
                'TIPO_VEHICULO' => 'Compacto',
                'STOCK' => 24
            ],
            [
                'MARCA' => 'Firestone',
                'MODELO_LLANTA' => 'Destination HP',
                'MEDIDA_RIN' => '16',
                'MEDIDA_LLANTA' => '215/65R16',
                'PRECIO' => 155.75,
                'IMAGEN' => 'https://images.unsplash.com/photo-1558648559-2e50beab5dee?w=800&h=600&fit=crop&crop=center',
                'CONDICION' => 'nueva',
                'TIPO_VEHICULO' => 'SUV',
                'STOCK' => 28
            ],
            [
                'MARCA' => 'Goodyear',
                'MODELO_LLANTA' => 'Eagle F1 Sport',
                'MEDIDA_RIN' => '18',
                'MEDIDA_LLANTA' => '235/40R18',
                'PRECIO' => 265.99,
                'IMAGEN' => 'https://images.unsplash.com/photo-1619781142167-c4b21fc09b18?w=800&h=600&fit=crop&crop=center',
                'CONDICION' => 'nueva',
                'TIPO_VEHICULO' => 'Hatchback',
                'STOCK' => 16
            ]
        ];
        
        foreach ($llantasData as $llantaData) {
            Llanta::updateOrCreate(
                [
                    'MARCA' => $llantaData['MARCA'],
                    'MODELO_LLANTA' => $llantaData['MODELO_LLANTA'],
                    'MEDIDA_LLANTA' => $llantaData['MEDIDA_LLANTA']
                ],
                $llantaData
            );
        }
        
        echo "✅ 15 llantas con imágenes funcionales creadas\n";
    }
}
