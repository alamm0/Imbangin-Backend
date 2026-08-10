<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\MeditationLog;
use App\Models\DailyScore; // <-- Wajib dipanggil
use Carbon\Carbon; // <-- Buat ngatur tanggal

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $today = Carbon::today()->toDateString(); // Ambil tanggal hari ini

        // 1. SKOR FISIK
        $total_fisik = Schedule::where('user_id', $userId)->count();
        $selesai_fisik = Schedule::where('user_id', $userId)->where('is_done', true)->count();
        
        $bobot_fisik = 0;
        if ($total_fisik > 0) {
            $bobot_fisik = 100 / $total_fisik;
        }
        $skor_fisik = $selesai_fisik * $bobot_fisik;

        // 2. SKOR MENTAL
        $total_mental = MeditationLog::where('user_id', $userId)->count();
        $selesai_mental = MeditationLog::where('user_id', $userId)->where('is_done', true)->count();
        
        $bobot_mental = 0;
        if ($total_mental > 0) {
            $bobot_mental = 100 / $total_mental;
        }
        $skor_mental = $selesai_mental * $bobot_mental;

        // 3. HITUNG GRAND TOTAL HARI INI
        $total_skor = ($skor_fisik + $skor_mental) / 2;
        
        // Cek hari ini aktif atau nggak (minimal nyelesaiin 1 jadwal)
        $is_active = ($selesai_fisik > 0 || $selesai_mental > 0);

        // 4. OTOMATIS SIMPAN KE RAPOR HARIAN
        DailyScore::updateOrCreate(
            ['user_id' => $userId, 'date' => $today], // Cari data hari ini
            ['score' => round($total_skor), 'is_active' => $is_active] // Update atau bikin baru
        );

        // 5. HITUNG STATISTIK MINGGUAN (Senin - Minggu)
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateString();

        $weekly_scores = DailyScore::where('user_id', $userId)
                            ->whereBetween('date', [$startOfWeek, $endOfWeek])
                            ->get();

        // Hitung Rata-rata Skor (Hanya ngitung hari yang udah ada datanya biar adil)
        $rata_rata_mingguan = $weekly_scores->count() > 0 ? round($weekly_scores->avg('score')) : 0;
        
        // Hitung total Hari Aktif di minggu ini
        $hari_aktif = $weekly_scores->where('is_active', true)->count();

        // ... (Kode sebelumnya yang ngitung Rata-rata Mingguan) ...

        // --- 6. LOGIKA LENCANA (ON-THE-FLY) & HARI AKTIF TOTAL ---
        
        // A. Hitung Total Semua Hari Aktif (Bukan cuma minggu ini, tapi sejak awal daftar)
        $total_hari_aktif = DailyScore::where('user_id', $userId)->where('is_active', true)->count();

        // B. Cek Syarat 4 Lencana
        // 1. Lencana Api: Minimal udah aktif 30 hari
        $badge_aktif_30 = $total_hari_aktif >= 30;
        
        // 2. Lencana Tidur: Punya minimal 1 rekor tidur dengan kualitas Ideal
        $badge_tidur = \App\Models\SleepTracker::where('user_id', $userId)->where('quality', 'like', '%Ideal%')->exists();
        
        // 3. Lencana Makan: Udah ada jadwal makan, dan SEMUA jadwal makan hari ini ter-ceklis
        $total_makan = \App\Models\MealSchedule::where('user_id', $userId)->count();
        $selesai_makan = \App\Models\MealSchedule::where('user_id', $userId)->where('is_done', true)->count();
        $badge_makan = ($total_makan > 0 && $total_makan == $selesai_makan);
        
        // 4. Lencana Raja Disiplin: Skor harian (Fisik + Mental) hari ini tembus 100
        $badge_disiplin = $total_skor >= 100;

        // C. Hitung total lencana yang berhasil kebuka
        $total_lencana = (int)$badge_aktif_30 + (int)$badge_tidur + (int)$badge_makan + (int)$badge_disiplin;

        return response()->json([
            'total_skor' => round($total_skor),
            'skor_fisik' => round($skor_fisik),
            'skor_mental' => round($skor_mental),
            'bobot_per_jadwal' => round($bobot_fisik, 1), 
            'bobot_per_mental' => round($bobot_mental, 1), 
            'target' => 100,
            'rata_rata_mingguan' => $rata_rata_mingguan,
            'hari_aktif' => $hari_aktif,
            
            // --- KIRIM DATA LENCANA KE REACT ---
            'total_hari_aktif' => $total_hari_aktif,
            'total_lencana' => $total_lencana,
            'badges' => [
                'aktif_30' => $badge_aktif_30,
                'tidur' => $badge_tidur,
                'makan' => $badge_makan,
                'disiplin' => $badge_disiplin
            ]
        ]);
    }
}