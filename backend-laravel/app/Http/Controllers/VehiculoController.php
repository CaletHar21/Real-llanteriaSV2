<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Vehiculo;

class VehiculoController extends Controller
{
    // Listar vehículos del usuario autenticado
    public function index(Request $request)
    {
        $usuario = $request->user();

        $vehiculos = Vehiculo::with(['marca', 'modelo.marca'])
            ->where('USUARIO_ID', $usuario->id)
            ->get();

        return response()->json([
            'mensaje' => '✅ Vehículos del usuario',
            'vehiculos' => $vehiculos
        ]);
    }

    // Registrar nuevo vehículo
    public function store(Request $request)
    {
        $request->validate([
            'MARCA_ID' => 'required|exists:marcas,id',
            'MODELO_ID' => [
                'required',
                Rule::exists('modelos', 'id')->where(function ($query) use ($request) {
                    $query->where('MARCA_ID', $request->MARCA_ID);
                }),
            ],
            'ANIO' => 'required|digits:4|integer',
            'MEDIDA_RIN' => 'required|string',
            'MEDIDA_LLANTA' => 'required|string',
            'MARCA_LLANTA' => 'required|string',
        ]);

        $vehiculo = Vehiculo::create([
            'USUARIO_ID' => $request->user()->id,
            'MARCA_ID' => $request->MARCA_ID,
            'MODELO_ID' => $request->MODELO_ID,
            'ANIO' => $request->ANIO,
            'MEDIDA_RIN' => $request->MEDIDA_RIN,
            'MEDIDA_LLANTA' => $request->MEDIDA_LLANTA,
            'MARCA_LLANTA' => strtoupper($request->MARCA_LLANTA),
        ]);

        return response()->json([
            'mensaje' => '✅ Vehículo registrado',
            'vehiculo' => $vehiculo
        ], 201);
    }
}
