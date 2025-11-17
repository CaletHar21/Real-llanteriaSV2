<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->bootstrap();

use App\Models\Usuario;
use App\Models\Entrega;

$juan = Usuario::where('CORREO', 'juan.perez@llanteria.com')->first();

if ($juan) {
    echo "=== Usuario ===\n";
    echo "Nombre: " . $juan->NOMBRES . "\n";
    echo "ID: " . $juan->id . "\n";
    echo "ROL: " . $juan->ROL . "\n";
    echo "Email: " . $juan->CORREO . "\n\n";

    echo "=== Entregas Asignadas ===\n";
    $entregas = Entrega::where('usuario_id', $juan->id)->with('pedido')->get();
    echo "Total: " . $entregas->count() . "\n";

    if ($entregas->count() > 0) {
        foreach ($entregas as $e) {
            echo "Entrega ID: " . $e->id . ", Pedido: #" . $e->pedido_id . ", Estado: " . $e->ESTADO_ENTREGA . "\n";
        }
    } else {
        echo "Sin entregas asignadas\n";
    }
} else {
    echo "Usuario no encontrado\n";
}
