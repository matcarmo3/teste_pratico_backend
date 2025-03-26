<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Você precisa está logado!'], 401);
        }
        // Se o usuário for admin, ele pode acessar qualquer rota
        if ($user->role === 'admin') {
            return $next($request);
        }
        if (!in_array($user->role, $roles)) {
            return response()->json(['message' => 'Sem permissão'], 403);
        }
        return $next($request);
    }
}
