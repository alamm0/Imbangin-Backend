<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Registrasi pengguna baru
    public function register(Request $request): JsonResponse
    {
        $request->merge([
            'email' => strtolower(trim((string) $request->email))
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => trim((string) $request->name),
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => 'sukses',
            'pesan' => 'Registrasi berhasil!',
            'data' => [
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken
            ]
        ], 201);
    }

    // Autentikasi pengguna dan berikan token akses
    public function login(Request $request): JsonResponse
    {
        $request->merge([
            'email' => strtolower(trim((string) $request->email))
        ]);

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'gagal',
                'pesan' => 'Kredensial tidak valid.'
            ], 401);
        }

        return response()->json([
            'status' => 'sukses',
            'pesan' => 'Login berhasil!',
            'data' => [
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken
            ]
        ], 200);
    }
}