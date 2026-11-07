<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class EnsureTokenAbilities
{
    public function handle(Request $request, Closure $next, ...$abilities): Response
    {
        $user = $request->user();

        if (! $user) {
            throw new AuthenticationException;
        }

        $tokenAbilities = JWTAuth::parseToken()->getPayload()->get('abilities') ?? [];

        foreach ($abilities as $ability) {
            if (! in_array('*', $tokenAbilities, true) && ! in_array($ability, $tokenAbilities, true)) {
                abort(403, 'El token no tiene los permisos necesarios.');
            }
        }

        return $next($request);
    }
}
