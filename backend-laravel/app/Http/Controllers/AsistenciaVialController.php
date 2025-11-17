<?php

namespace App\Http\Controllers;

use App\Models\AsistenciaVial;
use Illuminate\Http\Request;

class AsistenciaVialController extends Controller
{
    // Obtener asistencias del usuario autenticado
    public function index()
    {
        $asistencias = AsistenciaVial::where('usuario_id', auth()->id())->get();
        return response()->json($asistencias->toArray());
    }

    // Obtener todas las asistencias (admin/mécanico)
    public function all()
    {
        $asistencias = AsistenciaVial::with('cliente', 'mecanico')->get();
        return response()->json($asistencias->toArray());
    }

    // Crear solicitud de asistencia vial
    public function store(Request $request)
    {
        $validated = $request->validate([
            'marca_vehiculo' => 'required|string',
            'modelo_vehiculo' => 'required|string',
            'placa' => 'required|string',
            'tipo_problema' => 'required|in:pinchazo,cambio_llanta,reparacion,remolque,otro',
            'descripcion' => 'required|string',
            'ubicacion' => 'required|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
        ]);

        $asistencia = AsistenciaVial::create([
            'usuario_id' => auth()->id(),
            ...$validated
        ]);

        return response()->json(['mensaje' => 'Solicitud creada', 'asistencia' => $asistencia], 201);
    }

    // Asignar mecánico a asistencia
    public function asignar(Request $request, $id)
    {
        $asistencia = AsistenciaVial::findOrFail($id);
        
        $validated = $request->validate([
            'mecanico_id' => 'required|exists:usuarios,id',
        ]);

        $asistencia->update([
            'mecanico_id' => $validated['mecanico_id'],
            'estado' => 'en_camino'
        ]);

        return response()->json(['mensaje' => 'Mecánico asignado', 'asistencia' => $asistencia]);
    }

    // Actualizar estado de asistencia
    public function update(Request $request, $id)
    {
        $asistencia = AsistenciaVial::findOrFail($id);
        
        $validated = $request->validate([
            'estado' => 'in:solicitado,en_camino,en_atencion,resuelto,cancelado',
            'solucion_aplicada' => 'nullable|string',
        ]);

        if ($validated['estado'] == 'resuelto') {
            $validated['fecha_resolucion'] = now();
        }

        $asistencia->update($validated);
        return response()->json(['mensaje' => 'Asistencia actualizada', 'asistencia' => $asistencia]);
    }
}
