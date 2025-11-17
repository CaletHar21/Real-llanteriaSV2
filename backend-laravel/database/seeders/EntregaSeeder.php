<?php

namespace Database\Seeders;

use App\Models\Entrega;
use App\Models\Pedido;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class EntregaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pedidos = Pedido::all();
        $conductores = Usuario::where('rol', 'conductor')->get();

        if ($pedidos->isEmpty()) {
            return;
        }

        $estados = ['asignada', 'en_transito', 'entregada', 'fallida'];
        $ciudades = ['San Salvador', 'Santa Ana', 'San Miguel', 'La Libertad', 'Cuscatlán', 'Chalatenango'];

        foreach ($pedidos->take(6) as $pedido) {
            Entrega::create([
                'pedido_id' => $pedido->id,
                'usuario_id' => $conductores->isNotEmpty() ? $conductores->random()->id : null,
                'estado' => $estados[array_rand($estados)],
                'direccion' => $this->generateAddress(),
                'ciudad' => $ciudades[array_rand($ciudades)],
                'telefono' => $this->generatePhone(),
                'latitud' => 13 + rand(0, 99) / 100,
                'longitud' => -89 - rand(0, 99) / 100,
                'observaciones' => rand(0, 2) === 0 ? 'Cuidado con cristal - producto frágil' : null,
                'fecha_entrega_realizada' => in_array($estados[array_rand($estados)], ['entregada', 'fallida']) 
                    ? now()->subDays(rand(0, 3)) 
                    : null,
            ]);
        }
    }

    private function generateAddress(): string
    {
        $calles = ['Calle', 'Avenida', 'Pasaje'];
        $nombres = ['Principal', 'Comercial', 'Segunda', 'Tercera', 'La Paz', 'El Carmen'];
        $numeros = rand(100, 9999);
        
        return $calles[array_rand($calles)] . ' ' . $nombres[array_rand($nombres)] . ', #' . $numeros;
    }

    private function generatePhone(): string
    {
        return '2' . rand(1000000, 9999999);
    }
}
