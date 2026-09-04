<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if (!$token = Auth::guard('api')->attempt($credentials)) {

            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah.'
            ],401);

        }

        return response()->json([
            'success' => true,
            'token' => $token
        ]);
        
    }
    public function me()
{
    $user = Auth::guard('api')->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated'
        ], 401);
    }


    return response()->json([
        'success' => true,
        'data' => [
            'id' => $user->id,
            'fullname' => $user->fullname,
            'username' => $user->username,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'status_code' => $user->status_code,
        ]
    ]);
}
}