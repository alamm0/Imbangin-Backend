<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Schedule;
use App\Models\MeditationLog;
use App\Models\DailyScore;
use App\Models\SleepTracker;
use App\Models\MealSchedule;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Mengambil semua data metrik untuk Dashboard React
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $today = Carbon::today()->toDateString();

        // Ambil Data Skor Fisik & Mental via Helper
        $fisik = $this->hitungSkorFisik($userId);
        $mental = $this->hitungSkorMental($userId);

        // Kalkulasi Grand Total Harian
        $totalSkor = ($fisik['skor'] + $mental['skor']) / 2;
        $isActive = ($fisik['selesai'] > 0 || $mental['selesai'] > 0);

        // Simpan ke Rapor Harian
        DailyScore::updateOrCreate(
            ['user_id' => $userId, 'date' => $today],
            ['score' => round($totalSkor), 'is_active' => $isActive]
        );

        // Ambil Statistik Mingguan & Lencana
        $statistik = $this->hitungStatistikMingguan($userId);
        $lencana = $this->kalkulasiLencana($userId, $totalSkor);

        // Kembalikan Response Standar
        return response()->json([
            'status' => 'sukses',
            'pesan' => 'Data dashboard berhasil dimuat.',
            'data' => [
                'total_skor' => round($totalSkor),
                'skor_fisik' => round($fisik['skor']),
                'skor_mental' => round($mental['skor']),
                'bobot_per_jadwal' => round($fisik['bobot'], 1),
                'bobot_per_mental' => round($mental['bobot'], 1),
                'target' => 100,
                'rata_rata_mingguan' => $statistik['rata_rata'],
                'hari_aktif' => $statistik['hari_aktif'],
                
                // Data Lencana
                'total_hari_aktif' => $lencana['total_hari_aktif'],
                'total_lencana' => $lencana['total_lencana'],
                'badges' => $lencana['badges']
            ]
        ], 200);
    }

    // PRIVATE HELPER FUNCTIONS

    private function hitungSkorFisik(int $userId): array
    {
        $total = Schedule::where('user_id', $userId)->count();
        $selesai = Schedule::where('user_id', $userId)->where('is_done', true)->count();
        $bobot = $total > 0 ? 100 / $total : 0;

        return [
            'skor' => $selesai * $bobot, 
            'bobot' => $bobot, 
            'selesai' => $selesai
        ];
    }

    private function hitungSkorMental(int $userId): array
    {
        $total = MeditationLog::where('user_id', $userId)->count();
        $selesai = MeditationLog::where('user_id', $userId)->where('is_done', true)->count();
        $bobot = $total > 0 ? 100 / $total : 0;

        return [
            'skor' => $selesai * $bobot, 
            'bobot' => $bobot, 
            'selesai' => $selesai
        ];
    }

    private function hitungStatistikMingguan(int $userId): array
    {
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateString();

        $weeklyScores = DailyScore::where('user_id', $userId)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->get();

        return [
            'rata_rata' => $weeklyScores->count() > 0 ? round($weeklyScores->avg('score')) : 0,
            'hari_aktif' => $weeklyScores->where('is_active', true)->count()
        ];
    }

    private function kalkulasiLencana(int $userId, float $totalSkor): array
    {
        $totalHariAktif = DailyScore::where('user_id', $userId)->where('is_active', true)->count();
        
        $badgeAktif30 = $totalHariAktif >= 30;
        $badgeTidur = SleepTracker::where('user_id', $userId)->where('quality', 'like', '%Ideal%')->exists();
        
        $totalMakan = MealSchedule::where('user_id', $userId)->count();
        $selesaiMakan = MealSchedule::where('user_id', $userId)->where('is_done', true)->count();
        $badgeMakan = ($totalMakan > 0 && $totalMakan === $selesaiMakan);
        
        $badgeDisiplin = $totalSkor >= 100;

        $totalLencana = (int)$badgeAktif30 + (int)$badgeTidur + (int)$badgeMakan + (int)$badgeDisiplin;

        return [
            'total_hari_aktif' => $totalHariAktif,
            'total_lencana' => $totalLencana,
            'badges' => [
                'aktif_30' => $badgeAktif30,
                'tidur' => $badgeTidur,
                'makan' => $badgeMakan,
                'disiplin' => $badgeDisiplin
            ]
        ];
    }
}