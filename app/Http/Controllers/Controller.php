<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

abstract class Controller
{
    public function authenticateUser(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['message' => 'No token provided'], 401);
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return response()->json(['message' => 'Authentication required. Please log in.'], 401);
        }

        $user = $accessToken->tokenable;

        return $user;
    }
}
