<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! $user->is_active || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas',
                'errors' => [
                    'email' => ['Las credenciales proporcionadas son incorrectas.'],
                ],
            ], 422);
        }

        $abilities = match ($user->role) {
            'admin' => ['*'],
            'agent' => ['properties:read', 'properties:write'],
            'client' => ['profile:read', 'interests:manage'],
            default => [],
        };

        if ($abilities === []) {
            return response()->json(['message' => 'Esta cuenta no tiene acceso a la API.'], 403);
        }

        $expiresAt = now()->addMinutes((int) config('jwt.ttl'));
        $token = JWTAuth::claims(['abilities' => $abilities])->fromUser($user);

        return response()->json([
            'message' => 'Autenticación exitosa',
            'token' => $token,
            'token_type' => 'Bearer',
            'abilities' => $abilities,
            'expires_at' => $expiresAt->toISOString(),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json([
            'message' => 'Sesión cerrada correctamente',
        ]);
    }
}
