<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rol;

class RolController extends Controller
{
    public function index()
    {
        return Rol::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'NOMBRE' => 'required|string|unique:roles,NOMBRE',
        ]);

        $rol = Rol::create([
            'NOMBRE' => strtoupper($request->NOMBRE),
        ]);

        return response()->json($rol, 201);
    }
}
