<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MealSchedule;
use Illuminate\Http\Request;

class MealScheduleController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $meals = MealSchedule::where('user_id', $userId)->get();

        // Kalau jadwal masih kosong melompong (baru daftar), otomatis buatin 3 jadwal default
        if ($meals->isEmpty()) {
            $defaultMeals = [
                ['meal_name' => 'Sarapan', 'time_range' => '07:00-09:00'],
                ['meal_name' => 'Makan Siang', 'time_range' => '12:00-14:00'],
                ['meal_name' => 'Makan Malam', 'time_range' => '18:00-20:00'],
            ];

            foreach ($defaultMeals as $meal) {
                MealSchedule::create([
                    'user_id' => $userId,
                    'meal_name' => $meal['meal_name'],
                    'time_range' => $meal['time_range'],
                    'is_done' => false
                ]);
            }
            
            // Tarik ulang data yang baru aja dibuat
            $meals = MealSchedule::where('user_id', $userId)->get();
        }

        return response()->json($meals);
    }

    // Fungsi khusus buat update status ceklis (is_done)
    public function update(Request $request, $id)
    {
        $meal = MealSchedule::where('user_id', $request->user()->id)->findOrFail($id);
        
        $request->validate([
            'is_done' => 'required|boolean'
        ]);

        $meal->update([
            'is_done' => $request->is_done
        ]);

        return response()->json($meal);
    }
}