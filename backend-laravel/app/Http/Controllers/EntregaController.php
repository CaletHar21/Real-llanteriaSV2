<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use Illuminate\Http\Request;

class EntregaController extends Controller
{
    // Obtener entregas del conductor autenticado
    public function index()
    {
        $entregas = Entrega::where('usuario_id', auth()->id())->with('pedido')->get();
        return response()->json($entregas->toArray());
    }

    // Obtener todas las entregas (admin)
    public function all()
    {
        try {
            $entregas = Entrega::with('usuario', 'pedido')->get();
            \Log::info('📦 Entregas obtenidas:', ['count' => $entregas->count()]);
            return response()->json($entregas->toArray());
        } catch (\Exception $e) {
            \Log::error('❌ Error en EntregaController@all:', ['error' => $e->getMessage()]);
            return response()->json(['data' => []], 200); // Retornar array vacío si hay error
        }
    }

    // Asignar entrega a conductor
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'usuario_id' => 'required|exists:usuarios,id',
            'direccion' => 'required|string',
            'ciudad' => 'required|string',
            'telefono' => 'required|string',
        ]);

        $entrega = Entrega::create($validated);
        return response()->json(['mensaje' => 'Entrega creada', 'entrega' => $entrega], 201);
    }

    // Actualizar estado de entrega
    public function update(Request $request, $id)
    {
        $entrega = Entrega::findOrFail($id);
        
        $validated = $request->validate([
            'estado' => 'in:pendiente,en_camino,entregado,fallido,asignada,en_transito,entregada,fallida',
            'usuario_id' => 'nullable|exists:usuarios,id',
            'observaciones' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
        ]);

        if (isset($validated['estado']) && $validated['estado'] == 'entregada') {
            $validated['fecha_entrega_realizada'] = now();
        }

        $entrega->update($validated);
        
        // Cargar la relación usuario para devolver datos completos
        $entrega = $entrega->fresh()->load('usuario', 'pedido');
        
        return response()->json(['mensaje' => 'Entrega actualizada', 'entrega' => $entrega]);
    }
}
