<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cotizacion;
use App\Models\Llanta;
use App\Mail\CotizacionPDF;
use Illuminate\Support\Facades\Mail;

class CotizacionController extends Controller
{
    // Crear cotización (cliente)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'LLANTA_ID' => 'required|exists:llantas,id',
            'CANTIDAD' => 'required|integer|min:1',
        ]);

        $llanta = Llanta::findOrFail($validated['LLANTA_ID']);
        $usuario = $request->user();

        $cotizacion = Cotizacion::create([
            'USUARIO_ID' => $usuario->id,
            'LLANTA_ID' => $validated['LLANTA_ID'],
            'CANTIDAD' => $validated['CANTIDAD'],
            'PRECIO_UNITARIO' => $llanta->PRECIO,
            'SUBTOTAL' => $llanta->PRECIO * $validated['CANTIDAD'],
            'ESTADO' => 'PENDIENTE',
        ]);

        // Enviar correo con PDF
        try {
            Mail::to($usuario->email)->send(new CotizacionPDF($cotizacion));
        } catch (\Exception $e) {
            \Log::error('Error enviando cotización PDF: ' . $e->getMessage());
        }

        return response()->json([
            'mensaje' => '✅ Cotización creada exitosamente. Se envió un correo con el PDF',
            'cotizacion' => $cotizacion,
        ], 201);
    }

    // Ver mis cotizaciones (cliente)
    public function index(Request $request)
    {
        $usuario = $request->user();
        $cotizaciones = Cotizacion::where('USUARIO_ID', $usuario->id)
            ->with(['llanta'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($cotizaciones);
    }

    // Admin: ver todas las cotizaciones
    public function all()
    {
        $cotizaciones = Cotizacion::with(['usuario', 'llanta'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($cotizaciones);
    }

    // Admin: marcar como contactado
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'ESTADO' => 'required|in:PENDIENTE,CONTACTADO,CANCELADA',
            'NOTAS' => 'nullable|string',
        ]);

        $cotizacion = Cotizacion::findOrFail($id);
        $cotizacion->update($validated);

        return response()->json([
            'mensaje' => '✅ Cotización actualizada',
            'cotizacion' => $cotizacion,
        ]);
    }

    // Eliminar cotización
    public function destroy($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        $cotizacion->delete();

        return response()->json([
            'mensaje' => '✅ Cotización eliminada',
        ]);
    }
}
