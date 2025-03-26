<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['message' => 'Token não fornecido.'], 401);
        }
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Token inválido ou expirado.'], 401);
        }
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        return $next($request);
    }
}
