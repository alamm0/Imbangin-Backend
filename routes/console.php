<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


// Reset Aktivitas Harian (Fisik, Mental, Makan)
Schedule::call(function () {
    DB::table('schedules')->update(['is_done' => false]);
    DB::table('meditation_logs')->update(['is_done' => false]);
    DB::table('meal_schedules')->update(['is_done' => false]);
})->dailyAt('23:59');


// Reset Target & Statistik Mingguan
Schedule::call(function () {
    DB::table('monthly_targets')->update(['isDone' => false]);
    
    // TODO: Implementasi reset riwayat statistik mingguan
})->weeklyOn(1, '00:00');