<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyHealthScore;
use App\Models\User;

class DailyHealthScoreController extends Controller
{
    // Fungsi ini untuk MENGAMBIL data dari database
    public function index()
    {
        $dataSkor = DailyHealthScore::all();

        return response()->json([
            'status' => 'sukses',
            'pesan' => 'Data skor kesehatan berhasil diambil dari MySQL!',
            'data' => $dataSkor
        ]);
    }

    // Fungsi ini untuk MEMBUAT data dummy otomatis
    public function buatDummy()
    {
        // 1. Bikin akun dummy dulu sebagai pemilik data
        $user = User::firstOrCreate(
            ['email' => 'alam@imbangin.com'],
            ['name' => 'Alam', 'password' => bcrypt('password123')]
        );

        // 2. Masukkan data skor sesuai desain UI-mu ke MySQL
        $skorBaru = DailyHealthScore::create([
            'user_id' => $user->id,
            'record_date' => now()->toDateString(),
            'total_score' => 20,
            'sleep_score' => 15,
            'food_score' => 25,
            'focus_score' => 80
        ]);

        return response()->json([
            'status' => 'sukses',
            'pesan' => 'Data dummy berhasil disuntikkan ke database!',
            'data' => $skorBaru
        ]);
    }
}