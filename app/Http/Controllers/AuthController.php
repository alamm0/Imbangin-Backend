<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // --- PROSES REGISTER ---
    public function register(Request $request)
    {
        $email = strtolower(trim((string) $request->input('email')));
        $request->merge(['email' => $email]);

        // 1. Validasi data dari React
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // 2. Simpan user baru ke database MySQL
        $user = User::create([
            'name' => trim((string) $request->name),
            'email' => $email,
            'password' => Hash::make($request->password), // Enkripsi password
        ]);

        // 3. Buat tiket akses (Token)
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Kirim balasan ke React
        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Registrasi berhasil!'
        ], 201);
    }

    // --- PROSES LOGIN ---
    public function login(Request $request)
    {
        $email = strtolower(trim((string) $request->input('email')));
        $request->merge(['email' => $email]);

        // 1. Validasi inputan
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // 2. Cek apakah email ada di database
        $user = User::where('email', $email)->first();

        // 3. Cek kecocokan password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah nih.'
            ], 401);
        }

        // 4. Buat tiket akses baru
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Izinkan masuk
        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Login sukses!'
        ]);
    }
}