<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\LlantaController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\AsistenciaVialController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;

// Health check endpoint para Railway
Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        return response()->json([
            'status' => 'ok',
            'database' => 'connected',
            'timestamp' => now()->toISOString(),
            'service' => 'llanteria-backend'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'database' => 'disconnected',
            'error' => $e->getMessage(),
            'timestamp' => now()->toISOString(),
            'service' => 'llanteria-backend'
        ], 503);
    }
});

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Modelos de carro
Route::get('/modelos', [ModeloController::class, 'index']); // pública para el registro

// Marcas de carro
Route::get('/marcas', [MarcaController::class, 'index']);
Route::post('/marcas', [MarcaController::class, 'store']);

// Llantas - índice público (solo lectura)
Route::get('/llantas', [LlantaController::class, 'index']);
Route::get('/llantas/{id}', [LlantaController::class, 'show']);

// Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/perfil', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Vehículos
    Route::get('/vehiculos', [VehiculoController::class, 'index']);
    Route::post('/vehiculos', [VehiculoController::class, 'store']);

 

    // Roles
    Route::get('/roles', [RolController::class, 'index']);
    Route::post('/roles', [RolController::class, 'store']);

    // Llantas - modificación protegida (solo admin)
    Route::post('/llantas', [LlantaController::class, 'store']);
    Route::patch('/llantas/{id}', [LlantaController::class, 'update']);
    Route::delete('/llantas/{id}', [LlantaController::class, 'destroy']);

    // Cotizaciones
    Route::post('/cotizaciones', [CotizacionController::class, 'store']);
    Route::get('/cotizaciones', [CotizacionController::class, 'index']); // mis cotizaciones
    Route::get('/cotizaciones/admin/todas', [CotizacionController::class, 'all']); // solo admin
    Route::patch('/cotizaciones/{id}', [CotizacionController::class, 'update']);
    Route::delete('/cotizaciones/{id}', [CotizacionController::class, 'destroy']);

    // Pedidos
    Route::post('/pedidos', [PedidoController::class, 'store']);
    Route::get('/pedidos', [PedidoController::class, 'index']); // mis pedidos
    Route::get('/pedidos/admin/todos', [PedidoController::class, 'all']); // solo admin
    Route::get('/pedidos/{id}', [PedidoController::class, 'show']);
    Route::patch('/pedidos/{id}', [PedidoController::class, 'update']);
    Route::delete('/pedidos/{id}', [PedidoController::class, 'destroy']);

    // Entregas
    Route::get('/entregas', [EntregaController::class, 'index']); // mis entregas (conductor)
    Route::get('/entregas/admin/todas', [EntregaController::class, 'all']); // solo admin
    Route::post('/entregas', [EntregaController::class, 'store']); // solo admin
    Route::patch('/entregas/{id}', [EntregaController::class, 'update']);

    // Asistencia Vial
    Route::post('/asistencia-vial', [AsistenciaVialController::class, 'store']);
    Route::get('/asistencia-vial', [AsistenciaVialController::class, 'index']); // mis asistencias
    Route::get('/asistencia-vial/admin/todas', [AsistenciaVialController::class, 'all']); // solo admin
    Route::post('/asistencia-vial/{id}/asignar', [AsistenciaVialController::class, 'asignar']); // solo admin
    Route::patch('/asistencia-vial/{id}', [AsistenciaVialController::class, 'update']);

    // Clientes
    Route::get('/clientes', [ClienteController::class, 'index']); // solo admin
    Route::get('/clientes/{id}', [ClienteController::class, 'show']);
    Route::patch('/clientes/{id}', [ClienteController::class, 'update']);
    Route::delete('/clientes/{id}', [ClienteController::class, 'destroy']);

    // Usuarios (todos)
    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::post('/usuarios', [UsuarioController::class, 'store']);
    Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);
    Route::patch('/usuarios/{id}', [UsuarioController::class, 'update']);
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);
});

// Rutas de diagnóstico
Route::get('/ping', function () {
    return response()->json(['status' => 'pong']);
});

Route::get('/status', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['api' => 'activa', 'db' => 'conectada']);
    } catch (\Exception $e) {
        return response()->json(['api' => 'activa', 'db' => 'fallida']);
    }
});



