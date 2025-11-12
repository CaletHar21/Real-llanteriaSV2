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



