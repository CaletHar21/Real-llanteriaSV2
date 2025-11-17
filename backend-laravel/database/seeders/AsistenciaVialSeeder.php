<?php

namespace Database\Seeders;

use App\Models\AsistenciaVial;
use App\Models\Usuario;
use App\Models\Marca;
use Illuminate\Database\Seeder;

class AsistenciaVialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = Usuario::where('rol', 'usuario')->get();
        $mecanicos = Usuario::where('rol', 'mecanico')->get();
        $marcas = Marca::all();

        if ($usuarios->isEmpty() || $marcas->isEmpty()) {
            return;
        }

        $tipos_problema = ['pinchazo', 'bateria_descargada', 'motor_no_enciende', 'problemas_frenos', 'suspension_danada', 'perdida_aceite'];
        $estados = ['solicitada', 'asignada', 'en_atencion', 'resuelta', 'cancelada'];
        $ciudades = ['San Salvador', 'Santa Ana', 'San Miguel', 'La Libertad', 'Cuscatlán'];

        for ($i = 0; $i < 7; $i++) {
            $estado = $estados[array_rand($estados)];
            $tiene_mecanico = in_array($estado, ['asignada', 'en_atencion', 'resuelta']);

            AsistenciaVial::create([
                'usuario_id' => $usuarios->random()->id,
                'marca' => $marcas->random()->NOMBRE,
                'modelo' => $this->generateModelo(),
                'placa' => strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6)),
                'tipo_problema' => $tipos_problema[array_rand($tipos_problema)],
                'descripcion' => $this->generateDescripcion(),
                'estado' => $estado,
                'ubicacion' => $this->generateAddress(),
                'latitud' => 13 + rand(0, 99) / 100,
                'longitud' => -89 - rand(0, 99) / 100,
                'mecanico_id' => $tiene_mecanico && $mecanicos->isNotEmpty() ? $mecanicos->random()->id : null,
                'solucion_aplicada' => in_array($estado, ['resuelta']) ? $this->generateSolucion() : null,
                'fecha_resolucion' => in_array($estado, ['resuelta']) ? now()->subDays(rand(0, 2)) : null,
            ]);
        }
    }

    private function generateModelo(): string
    {
        $modelos = ['Corolla', 'Civic', 'Sentra', 'Amarok', 'Hilux', 'Accent', 'Elantra', 'Santa Fe'];
        return $modelos[array_rand($modelos)];
    }

    private function generateDescripcion(): string
    {
        $descripciones = [
            'El vehículo presenta un pinchazo en la llanta delantera izquierda',
            'La batería no responde, el auto no enciende',
            'El motor no arranca, hace ruidos extraños',
            'Los frenos hacen ruido al frenar',
            'La suspensión está muy baja, posible daño',
            'Hay pérdida de aceite del motor',
        ];
        return $descripciones[array_rand($descripciones)];
    }

    private function generateAddress(): string
    {
        $calles = ['Calle', 'Avenida', 'Pasaje'];
        $nombres = ['Principal', 'Comercial', 'Segunda', 'Tercera'];
        $numeros = rand(100, 9999);
        
        return $calles[array_rand($calles)] . ' ' . $nombres[array_rand($nombres)] . ', #' . $numeros;
    }

    private function generateSolucion(): string
    {
        $soluciones = [
            'Se reparó y selló la llanta con parche',
            'Se reemplazó la batería por una nueva',
            'Se realizó mantenimiento completo del motor',
            'Se ajustaron los frenos y se lubricaron',
            'Se repararon los amortiguadores',
            'Se cambió el aceite del motor',
        ];
        return $soluciones[array_rand($soluciones)];
    }
}
