<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyHealthScore;
use App\Models\User;

class DailyHealthScoreController extends Controller
{
    public function index()
    {
        $dataSkor = DailyHealthScore::all();

        return response()->json([
            'status' => 'sukses',
            'pesan' => 'Data skor kesehatan berhasil diambil dari MySQL!',
            'data' => $dataSkor
        ]);
    }
}