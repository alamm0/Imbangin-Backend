<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SleepTracker;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class SleepTrackerController extends Controller
{
    // Ambil data tidur terakhir user
    public function index(Request $request): JsonResponse
    {
        $log = SleepTracker::where('user_id', $request->user()->id)->first();

        return response()->json([
            'status' => 'sukses',
            'pesan' => $log ? 'Data tidur ditemukan.' : 'Belum ada riwayat tidur.',
            'data' => $log
        ], 200);
    }

    // Simpan atau update durasi tidur harian
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'sleep_time' => 'required|date_format:H:i',
            'wake_time' => 'required|date_format:H:i',
        ]);

        $sleepTime = Carbon::createFromFormat('H:i', $request->sleep_time);
        $wakeTime = Carbon::createFromFormat('H:i', $request->wake_time);

        // Tambah 1 hari jika waktu bangun melewati tengah malam
        if ($wakeTime->lessThan($sleepTime)) {
            $wakeTime->addDay();
        }

        $durationInHours = $sleepTime->diffInHours($wakeTime);
        $quality = $this->hitungKualitasTidur($durationInHours);

        $log = SleepTracker::updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'sleep_time' => $request->sleep_time,
                'wake_time' => $request->wake_time,
                'duration_hours' => $durationInHours,
                'quality' => $quality
            ]
        );

        return response()->json([
            'status' => 'sukses',
            'pesan' => 'Data pelacakan tidur berhasil disimpan!',
            'data' => $log
        ], 201);
    }

    // Penentuan kategori kualitas tidur
    private function hitungKualitasTidur(int $hours): string
    {
        if ($hours < 6) return 'Kurang';
        if ($hours >= 7 && $hours <= 8) return 'Ideal';
        if ($hours > 8) return 'Berlebih';
        
        return 'Biasa';
    }
}