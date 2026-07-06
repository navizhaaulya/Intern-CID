<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        try {
            $credentials = $request->validate([
                'email'    => ['required', 'email'],
                'password' => ['required', 'string'],
            ]);

            if (!$token = Auth::guard('api')->attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah.',
                ], 401);
            }

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil.',
                'data'    => [
                    'access_token' => $token,
                    'token_type'   => 'bearer',
                    'expires_in'   => Auth::guard('api')->factory()->getTTL() * 60,
                    'user'         => Auth::guard('api')->user(),
                ],
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        }
    }

    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    public function me(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => Auth::guard('api')->user(),
        ]);
    }

    public function refresh(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'access_token' => Auth::guard('api')->refresh(),
                'token_type'   => 'bearer',
            ],
        ]);
    }
}