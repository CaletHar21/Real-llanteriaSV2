<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;

class ModeloController extends Controller
{
    /**
     * Devuelve todos los modelos con su marca asociada
     */
    public function index()
    {
        return response()->json(
            Modelo::with('marca')->get(),
            200
        );
    }

    /**
     * Guarda un nuevo modelo (opcional si querés permitirlo)
     */
    public function store(Request $request)
    {
        $request->validate([
            'MODELO' => 'required|string|max:255',
            'MARCA_ID' => 'required|exists:marcas,id',
        ]);

        $modelo = Modelo::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $modelo
        ], 201);
    }
}
