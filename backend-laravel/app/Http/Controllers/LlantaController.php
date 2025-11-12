<?php

namespace App\Http\Controllers;

use App\Models\Llanta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LlantaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Soporte de paginación para listar llantas (admin/gestión)
        $perPage = request()->get('per_page', 10);
        $llantas = Llanta::orderBy('id', 'desc')->paginate($perPage);
        return response()->json($llantas, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // DEBUG: registrar contenido recibido para diagnosticar uploads
        Log::info('LlantaController@store - request keys', [$request->keys()]);
        Log::info('LlantaController@store - files', array_keys($request->files->all()));

        $validated = $request->validate([
            'MARCA' => 'required|string',
            'MODELO_LLANTA' => 'nullable|string',
            'MEDIDA_RIN' => 'nullable|string',
            'MEDIDA_LLANTA' => 'nullable|string',
            'PRECIO' => 'required|numeric',
            // IMAGEN puede ser string (URL) o archivo multipart
            'IMAGEN' => 'nullable',
            'CONDICION' => 'in:nueva,reacondicionada',
            'TIPO_VEHICULO' => 'nullable|string',
            'STOCK' => 'required|integer',
        ]);

        // Si se sube un archivo, guardarlo en disco y reemplazar el campo IMAGEN
        if ($request->hasFile('IMAGEN')) {
            $file = $request->file('IMAGEN');
            Log::info('LlantaController@store - received file', ['originalName' => $file->getClientOriginalName(), 'size' => $file->getSize()]);
            $path = $file->store('llantas', 'public');
            // Guardar URL absoluta (ej: http://localhost:8000/storage/llantas/archivo)
            $validated['IMAGEN'] = url(Storage::url($path));
        }

        $llanta = Llanta::create($validated);
        return response()->json($llanta, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $llanta = Llanta::findOrFail($id);
        return response()->json($llanta, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $llanta = Llanta::findOrFail($id);
        // DEBUG: registrar para diagnostico
        Log::info('LlantaController@update - request keys', [$request->keys()]);
        Log::info('LlantaController@update - files', array_keys($request->files->all()));
        $validated = $request->validate([
            'MARCA' => 'sometimes|string',
            'MODELO_LLANTA' => 'nullable|string',
            'MEDIDA_RIN' => 'nullable|string',
            'MEDIDA_LLANTA' => 'nullable|string',
            'PRECIO' => 'sometimes|numeric',
            // IMAGEN puede ser string (URL) o archivo multipart
            'IMAGEN' => 'nullable',
            'CONDICION' => 'in:nueva,reacondicionada',
            'TIPO_VEHICULO' => 'nullable|string',
            'STOCK' => 'sometimes|integer',
        ]);

        if ($request->hasFile('IMAGEN')) {
            // Si ya existía una imagen guardada en storage, eliminarla
            if (!empty($llanta->IMAGEN) && str_starts_with($llanta->IMAGEN, '/storage/')) {
                $publicPath = str_replace('/storage/', '', $llanta->IMAGEN);
                if (Storage::disk('public')->exists($publicPath)) {
                    Storage::disk('public')->delete($publicPath);
                    Log::info('LlantaController@update - deleted previous image', ['path' => $publicPath]);
                }
            }

            $file = $request->file('IMAGEN');
            Log::info('LlantaController@update - received file', ['originalName' => $file->getClientOriginalName(), 'size' => $file->getSize()]);
            $path = $file->store('llantas', 'public');
            // Guardar URL absoluta para que el frontend pueda cargarla directamente
            $validated['IMAGEN'] = url(Storage::url($path));
        }

        $llanta->update($validated);
        return response()->json($llanta, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $llanta = Llanta::findOrFail($id);
        $llanta->delete();
        return response()->json(['message' => 'Llanta eliminada'], 200);
    }
}
