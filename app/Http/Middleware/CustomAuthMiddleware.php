<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se a rota atual é a de login
        if ($request->routeIs('login') || $request->routeIs('login.post')) {
            return $next($request);
        }

        // Se não estiver autenticado, redireciona para a página de login
        if (!session('authenticated')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
