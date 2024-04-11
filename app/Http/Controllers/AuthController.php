<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login (LoginRequest $request)
    {
        $credentials = \request(['phonenumber', 'password']);

        info($credentials);

        if (!Auth::attempt($credentials))
        {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = $request->user()->load('operator');
        $token = $user->createToken('Personal Access Token');
        $token = $token->plainTextToken;

        return response()->json([
            'name' => $user->operator->operatorName,
            'token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);
    }
}
