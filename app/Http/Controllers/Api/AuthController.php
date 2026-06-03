<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi input dari Flutter
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 2. Cek apakah email dan password cocok di database
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kredensial tidak valid. Coba lagi!'
            ], 401);
        }

        // 3. (Opsional tapi keren) Cek apakah dia benar-benar Pelanggan (Role 2)
        if ($user->role != 2) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya akun Pelanggan yang bisa login di aplikasi mobile!'
            ], 403);
        }

        // 4. Jika sukses, buatkan Token Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login Berhasil!',
            'data' => $user,
            'token' => $token
        ], 200);
    }
}
