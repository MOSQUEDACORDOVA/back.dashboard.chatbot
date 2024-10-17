<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            // Obtener el tipo de usuario
            $user = Auth::user();
            
            // Verificar si el usuario tiene 'type' 1
            if ($user->type === 1) {
                return $next($request); // Permitir acceso
            }
        }

        // Si no es 'type' 1, retornar una respuesta de error
        return response()->json(['error' => 'No tienes permiso para realizar esta acción.'], 403);
    }
}
