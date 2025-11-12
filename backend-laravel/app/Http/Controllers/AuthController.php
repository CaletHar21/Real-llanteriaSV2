<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Usuario;
use App\Models\Vehiculo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        \Log::info('🔥 Entrando al método register');

        try {
            $fields = $request->validate([
                'NOMBRES' => 'required|string',
                'APELLIDOS' => 'required|string',
                'CORREO' => 'required|string|email|unique:usuarios,CORREO',
                'DUI' => 'required|string|unique:usuarios,DUI',
                'TELEFONO' => 'required|string',
                'DIRECCION' => 'required|string',
                'PASSWORD' => 'required|string|confirmed',
                'MARCA_ID' => 'nullable|exists:marcas,id',
                'MODELO_ID' => [
                    'nullable',
                    Rule::exists('modelos', 'id')->where(function ($query) use ($request) {
                        $query->where('MARCA_ID', $request->MARCA_ID);
                    }),
                ],
                'ANIO' => 'nullable|digits:4|integer',
                'MEDIDA_RIN' => 'nullable|string',
                'MEDIDA_LLANTA' => 'nullable|string',
                'MARCA_LLANTA' => 'nullable|string',
            ]);

            \Log::info('✅ Validación OK');

            $usuario = Usuario::create([
                'NOMBRES' => strtoupper($fields['NOMBRES']),
                'APELLIDOS' => strtoupper($fields['APELLIDOS']),
                'CORREO' => strtolower($fields['CORREO']),
                'DUI' => $fields['DUI'],
                'TELEFONO' => $fields['TELEFONO'],
                'DIRECCION' => strtoupper($fields['DIRECCION']),
                'PASSWORD' => Hash::make($fields['PASSWORD']),
                'ROL' => 'CLIENTE',
            ]);

            \Log::info('✅ Usuario creado: ' . $usuario->id);

            if ($request->filled(['MARCA_ID', 'MODELO_ID', 'ANIO', 'MEDIDA_RIN', 'MEDIDA_LLANTA', 'MARCA_LLANTA'])) {
                Vehiculo::create([
                    'USUARIO_ID' => $usuario->id,
                    'MARCA_ID' => $request->MARCA_ID,
                    'MODELO_ID' => $request->MODELO_ID,
                    'ANIO' => $request->ANIO,
                    'MEDIDA_RIN' => $request->MEDIDA_RIN,
                    'MEDIDA_LLANTA' => $request->MEDIDA_LLANTA,
                    'MARCA_LLANTA' => strtoupper($request->MARCA_LLANTA),
                ]);

                \Log::info('✅ Vehículo creado');
            }

            $token = $usuario->createToken('llanteriaToken')->plainTextToken;

            return response()->json([
                'mensaje' => '✅ Registro exitoso',
                'usuario' => $usuario,
                'token' => $token
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('❌ Error de validación', $e->errors());
            return response()->json([
                'mensaje' => '❌ Error de validación',
                'errores' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('❌ Error general: ' . $e->getMessage());
            return response()->json([
                'mensaje' => '❌ Error general',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'CORREO' => 'required|string|email',
            'PASSWORD' => 'required|string',
        ]);

        $usuario = Usuario::where('CORREO', strtolower($fields['CORREO']))->first();

        if (!$usuario || !Hash::check($fields['PASSWORD'], $usuario->PASSWORD)) {
            return response()->json(['mensaje' => '❌ Credenciales inválidas'], 401);
        }

        $usuario->load(['vehiculos.marca', 'vehiculos.modelo']);

        $token = $usuario->createToken('llanteriaToken')->plainTextToken;

        return response()->json([
            'mensaje' => '✅ Inicio de sesión exitoso',
            'usuario' => $usuario,
            'token' => $token
        ], 200);
    }

    public function profile(Request $request)
    {
        $usuario = $request->user();
        $usuario->load('vehiculos');
        return response()->json([
            'mensaje' => '✅ Perfil autenticado',
            'usuario' => $usuario,
            'vehiculos' => $usuario->vehiculos
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['mensaje' => '✅ Sesión cerrada']);
    }
}
