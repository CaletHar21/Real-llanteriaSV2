<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\DetallePedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    // Obtener todos los pedidos del usuario autenticado
    public function index()
    {
        $pedidos = Pedido::where('usuario_id', auth()->id())->with('detalles.llanta')->get();
        return response()->json($pedidos);
    }

    // Obtener todos los pedidos (solo admin)
    public function all()
    {
        $pedidos = Pedido::with('usuario', 'detalles.llanta')->get();
        return response()->json($pedidos->toArray());
    }

    // Crear nuevo pedido
    public function store(Request $request)
    {
        $validated = $request->validate([
            'total' => 'required|numeric',
            'notas' => 'nullable|string',
            'fecha_entrega_solicitada' => 'nullable|date',
            'detalles' => 'required|array',
            'detalles.*.llanta_id' => 'required|exists:llantas,id',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'required|numeric',
        ]);

        $pedido = Pedido::create([
            'usuario_id' => auth()->id(),
            'total' => $validated['total'],
            'notas' => $validated['notas'] ?? null,
            'fecha_entrega_solicitada' => $validated['fecha_entrega_solicitada'] ?? null,
        ]);

        foreach ($validated['detalles'] as $detalle) {
            DetallePedido::create([
                'pedido_id' => $pedido->id,
                'llanta_id' => $detalle['llanta_id'],
                'cantidad' => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'],
                'subtotal' => $detalle['cantidad'] * $detalle['precio_unitario'],
            ]);
        }

        return response()->json(['mensaje' => 'Pedido creado exitosamente', 'pedido' => $pedido->load('detalles')], 201);
    }

    // Ver detalles de un pedido
    public function show($id)
    {
        $pedido = Pedido::with('usuario', 'detalles.llanta', 'entrega')->findOrFail($id);
        return response()->json($pedido);
    }

    // Actualizar estado del pedido
    public function update(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);
        
        $validated = $request->validate([
            'estado' => 'required|in:pendiente,confirmado,en_preparacion,listo,cancelado',
        ]);

        $pedido->update($validated);
        return response()->json(['mensaje' => 'Pedido actualizado', 'pedido' => $pedido]);
    }

    // Cancelar pedido
    public function destroy($id)
    {
        $pedido = Pedido::findOrFail($id);
        $pedido->update(['estado' => 'cancelado']);
        return response()->json(['mensaje' => 'Pedido cancelado']);
    }
}
