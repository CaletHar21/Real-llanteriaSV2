<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtener el origen de la solicitud
        $origin = $request->header('Origin');
        
        // Permitir orígenes locales en desarrollo
        $allowedOrigins = [
            'http://localhost:5173',
            'http://localhost:3000',
            'http://127.0.0.1:5173',
            'http://127.0.0.1:3000',
            'http://0.0.0.0:5173',
            'http://0.0.0.0:3000',
        ];
        
        // En producción, agregar tu dominio
        // $allowedOrigins[] = 'https://tudominio.com';
        
        // Permitir cualquier origen (NO RECOMENDADO PARA PRODUCCIÓN)
        $isOriginAllowed = in_array($origin, $allowedOrigins) || $origin === null;

        // Manejar preflight requests
        if ($request->getMethod() == "OPTIONS") {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', $isOriginAllowed ? ($origin ?? '*') : '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Max-Age', 86400);
        }

        $response = $next($request);

        // Agregar headers CORS a todas las respuestas
        $response->headers->set('Access-Control-Allow-Origin', $isOriginAllowed ? ($origin ?? '*') : '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        return $response;
    }
}
