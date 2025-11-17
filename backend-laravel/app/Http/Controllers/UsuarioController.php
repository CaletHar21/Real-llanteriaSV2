<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Usuario::query();
            
            // Filtrar por rol si se proporciona
            if ($request->has('rol')) {
                $query->where('ROL', $request->rol);
                // Si pide por rol específico, retornar sin paginación
                $usuarios = $query->get();
                \Log::info('🔍 Usuarios por rol:', ['rol' => $request->rol, 'count' => $usuarios->count()]);
                return response()->json($usuarios->toArray());
            }
            
            // Si no pide rol, paginar
            $usuarios = $query->paginate(15);
            \Log::info('🔍 Usuarios obtenidos:', ['count' => $usuarios->count(), 'total' => $usuarios->total()]);
            \Log::info('📊 Primer usuario JSON:', json_decode(json_encode($usuarios->first()), true));
            
            // Asegurar que los datos se serialicen correctamente
            return response()->json($usuarios->toArray());
        } catch (\Exception $e) {
            \Log::error('❌ Error en UsuarioController@index:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $usuario = Usuario::findOrFail($id);
            return response()->json($usuario);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $usuario = Usuario::findOrFail($id);
            $usuario->update($request->all());
            return response()->json(['mensaje' => 'Usuario actualizado', 'usuario' => $usuario]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $usuario = Usuario::findOrFail($id);
            $usuario->delete();
            return response()->json(['mensaje' => 'Usuario eliminado']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $usuario = Usuario::create($request->all());
            return response()->json(['mensaje' => 'Usuario creado', 'usuario' => $usuario], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
