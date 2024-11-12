<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CustomAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Verifica si el usuario está autenticado usando `id_usuario`
        if (!Auth::check() || !Auth::user()->id_usuario) {
            // Redirecciona o devuelve una respuesta si no está autenticado
            return redirect()->route('login')->with('error', 'Por favor, inicia sesión');
        }

        return $next($request);
    }
}
