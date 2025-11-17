<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Obtener solo usuarios con rol 'usuario' o 'CLIENTE'
            $clientes = Usuario::whereIn('ROL', ['usuario', 'CLIENTE'])
                ->paginate(15);
            
            return response()->json($clientes->toArray());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $cliente = Usuario::findOrFail($id);
            return response()->json($cliente);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $cliente = Usuario::findOrFail($id);
            $cliente->update($request->all());
            return response()->json(['mensaje' => 'Cliente actualizado', 'cliente' => $cliente]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $cliente = Usuario::findOrFail($id);
            $cliente->delete();
            return response()->json(['mensaje' => 'Cliente eliminado']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
