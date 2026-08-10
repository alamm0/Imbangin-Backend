<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// 1. RESET JADWAL HARIAN (Fisik, Mental, Makan) 
// Jalan otomatis setiap hari jam 23:59 malam
Schedule::call(function () {
    DB::table('schedules')->update(['is_done' => false]);
    DB::table('meditation_logs')->update(['is_done' => false]);
    DB::table('meal_schedules')->update(['is_done' => false]);
})->dailyAt('23:59');

// 2. RESET TARGET & STATISTIK MINGGUAN
// Jalan otomatis setiap hari Senin (1) jam 00:00 pagi
Schedule::call(function () {
    // Reset status centang target mingguan
    DB::table('monthly_targets')->update(['isDone' => false]);
    
    // Nanti logika ngereset riwayat "Hari Aktif" dan "Rata-rata Mingguan" 
    // ditaruh di sini setelah kita bikin tabel riwayatnya.
})->weeklyOn(1, '00:00');