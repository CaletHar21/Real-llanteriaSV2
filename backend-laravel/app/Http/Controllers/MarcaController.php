<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function index()
    {
        return response()->json(Marca::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'NOMBRE' => 'required|string|max:255',
        ]);

        $marca = Marca::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $marca
        ], 201);
    }
}
