<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $this->validateCredentials($request);

        if (Auth::attempt($credentials) === false) {
            return response()->json('Unauthorized', 401);
        }

        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('token', [], now()->addDays(1));

        return response()->json($token->plainTextToken);
    }

    public function validateCredentials($request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        return $validated;
    }
}
