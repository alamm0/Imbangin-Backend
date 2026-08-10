<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DailyHealthScoreController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\SleepTrackerController;
use App\Http\Controllers\Api\FocusTimerController;
use App\Http\Controllers\Api\ImbanginCoinController;
use App\Http\Controllers\Api\MoodLogController;
use App\Http\Controllers\Api\MeditationLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\MonthlyTargetController;
use App\Http\Controllers\Api\MealScheduleController;
use App\Http\Controllers\Api\ChatController; // <-- Gw tambahin impor buat ChatController yang bener

// --- RUTE PUBLIK (TIDAK BUTUH LOGIN) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- RUTE YANG WAJIB MEMBAWA TOKEN LOGIN (SANCTUM) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::apiResource('monthly-targets', MonthlyTargetController::class);
    
    // Fitur CRUD otomatis (GET, POST, PUT, DELETE) untuk semua modul
    Route::apiResource('schedules', ScheduleController::class);
    Route::apiResource('sleep-trackers', SleepTrackerController::class);
    Route::apiResource('focus-timers', FocusTimerController::class);
    Route::apiResource('imbangin-coins', ImbanginCoinController::class);
    Route::apiResource('mood-logs', MoodLogController::class);
    Route::apiResource('meditation-logs', MeditationLogController::class);
    Route::apiResource('meal-schedules', MealScheduleController::class);

    // Rute tunggal untuk IMBANGIN AI
    Route::post('/chat', [ChatController::class, 'sendMessage'])->name('chat.send');
});

// Rute Skor Kesehatan (Opsional / Dummy)
Route::get('/skor-kesehatan', [DailyHealthScoreController::class, 'index']);
Route::get('/skor-kesehatan/dummy', [DailyHealthScoreController::class, 'buatDummy']);