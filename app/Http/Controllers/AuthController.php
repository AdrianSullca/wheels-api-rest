<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;

use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'phone_number' => ['required', 'string', 'max:9', 'unique:' . User::class],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->string('password')),
                'phone_number' => $request->phone_number,
                'profile_picture_path' => 'images/default_profile.avif',
                'admin' => false,
                'enabled' => true
            ]);

            event(new Registered($user));

            return response()->json([
                'message' => 'Registration successful. Please confirm your email.',
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred during registration',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
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

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json('Logged out successfully');
    }
}
