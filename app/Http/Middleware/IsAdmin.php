<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifique se o usuário está autenticado e se é um administrador
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Se não for um admin, retorna uma resposta de erro
        return response()->json(['error' => 'Unauthorized', 'menssage' => 'Rota de administrador'], 403);
    }
}
