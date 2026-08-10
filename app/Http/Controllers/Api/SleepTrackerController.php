<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SleepTracker;
use Illuminate\Http\Request;

class SleepTrackerController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data tidur terakhir user
        $log = SleepTracker::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['sleep_time' => '22:00', 'wake_time' => '05:00', 'duration_hours' => 7, 'quality' => 'Ideal']
        );
        return response()->json($log);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sleep_time' => 'required|string',
            'wake_time' => 'required|string',
        ]);

        // LOGIKA PERHITUNGAN TIDUR DIPINDAH KE LARAVEL
        $start = explode(':', $request->sleep_time);
        $end = explode(':', $request->wake_time);
        
        $startInMins = ($start[0] * 60) + $start[1];
        $endInMins = ($end[0] * 60) + $end[1];
        
        if ($endInMins <= $startInMins) {
            $endInMins += 24 * 60; // Tambah 24 jam kalau tidurnya lewatin tengah malam
        }
        
        $hours = floor(($endInMins - $startInMins) / 60);
        
        $quality = 'Biasa';
        if ($hours < 6) $quality = 'Kurang';
        else if ($hours >= 7 && $hours <= 8) $quality = 'Ideal';
        else if ($hours > 8) $quality = 'Berlebih';

        // Simpan / Update ke database
        $log = SleepTracker::updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'sleep_time' => $request->sleep_time,
                'wake_time' => $request->wake_time,
                'duration_hours' => $hours,
                'quality' => $quality
            ]
        );

        return response()->json($log);
    }
}