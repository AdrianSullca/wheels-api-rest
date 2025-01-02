<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        // Verifica que el usuario exista y la contraseña sea correcta
        if ($user && Hash::check($request->password, $user->password)) {
            if (!$user->hasVerifiedEmail()) {
                return response()->json(['message' => 'Please verify your email address before logging in.'], 403);
            }
            // Verifica si el usuario ya tiene un token activo
            $existingToken = $user->tokens()->where('name', 'access_token')->first();

            if ($existingToken) {
                // Si existe un token, devuélvelo
                $token = $existingToken->plainTextToken;
            } else {
                // Si no existe, crea uno nuevo
                $token = $user->createToken('access_token')->plainTextToken;
            }

            return response()->json([
                'token' => $token,
                'user' => $user,
                'message' => 'Login successful',
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
