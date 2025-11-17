<?php

namespace Database\Seeders;

use App\Models\Pedido;
use App\Models\Usuario;
use App\Models\Llanta;
use Illuminate\Database\Seeder;

class PedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = Usuario::where('rol', 'usuario')->get();
        $llantas = Llanta::all();

        if ($usuarios->isEmpty() || $llantas->isEmpty()) {
            return;
        }

        $estados = ['pendiente', 'confirmado', 'enviando', 'entregado', 'cancelado'];

        for ($i = 0; $i < 8; $i++) {
            $pedido = Pedido::create([
                'usuario_id' => $usuarios->random()->id,
                'estado' => $estados[array_rand($estados)],
                'total' => rand(50, 500) + rand(0, 99) / 100,
                'notas' => $i % 3 === 0 ? 'Entregar sin horario específico' : null,
                'fecha_entrega_solicitada' => now()->addDays(rand(1, 7))->format('Y-m-d'),
            ]);

            // Crear detalles del pedido (2-4 llantas por pedido)
            $cantidad_items = rand(2, 4);
            $total = 0;

            for ($j = 0; $j < $cantidad_items; $j++) {
                $llanta = $llantas->random();
                $cantidad = rand(1, 4);
                $precio_unitario = $llanta->PRECIO ?? 100; // valor por defecto si está vacío
                $subtotal = $cantidad * $precio_unitario;
                $total += $subtotal;

                $pedido->detalles()->create([
                    'llanta_id' => $llanta->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio_unitario,
                    'subtotal' => $subtotal,
                ]);
            }

            // Actualizar el total del pedido
            $pedido->update(['total' => $total]);
        }
    }
}
