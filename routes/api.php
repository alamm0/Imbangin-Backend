<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\DailyHealthScoreController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\SleepTrackerController;
use App\Http\Controllers\Api\FocusTimerController;
use App\Http\Controllers\Api\MoodLogController;
use App\Http\Controllers\Api\MeditationLogController;
use App\Http\Controllers\Api\MonthlyTargetController;
use App\Http\Controllers\Api\MealScheduleController;
use App\Http\Controllers\Api\ChatController;

// Rute Publik (Tidak Membutuhkan Token Login)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rute Terproteksi (Wajib Membawa Token Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    
    // Dashboard & Target Utama
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::apiResource('monthly-targets', MonthlyTargetController::class);
    
    // Modul Kesehatan Fisik
    Route::apiResource('schedules', ScheduleController::class);
    Route::apiResource('meal-schedules', MealScheduleController::class);
    Route::apiResource('sleep-trackers', SleepTrackerController::class);
    
    // Modul Kesehatan Mental & Fokus
    Route::apiResource('meditation-logs', MeditationLogController::class);
    Route::apiResource('mood-logs', MoodLogController::class);
    Route::apiResource('focus-timers', FocusTimerController::class);

    // Integrasi IMBANGIN AI
    Route::post('/chat', [ChatController::class, 'sendMessage'])->name('chat.send');
});

// Rute Lainnya
Route::get('/skor-kesehatan', [DailyHealthScoreController::class, 'index']);