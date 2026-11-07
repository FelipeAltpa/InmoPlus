<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && ! $request->user()->is_active) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'La cuenta está desactivada.'], 403);
            }

            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors(['email' => 'La cuenta está desactivada.']);
        }

        if ($request->is('api/*') && $request->user() && $request->bearerToken()) {
            $tokenVersion = JWTAuth::parseToken()->getPayload()->get('token_version');

            if ((int) $tokenVersion !== (int) $request->user()->token_version) {
                return response()->json(['message' => 'El token ya no es válido. Inicia sesión nuevamente.'], 401);
            }
        }

        return $next($request);
    }
}
