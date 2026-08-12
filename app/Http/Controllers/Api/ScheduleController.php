<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(Schedule::where('user_id', $request->user()->id)->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity_name' => 'required|string',
            'start_time' => 'required|string',
        ]);

        $schedule = Schedule::create([
            'user_id' => $request->user()->id,
            'activity_name' => $request->activity_name,
            'start_time' => $request->start_time,
            'schedule_date' => $request->schedule_date ?? date('Y-m-d'),
            'is_done' => false
        ]);

        return response()->json($schedule, 201);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::where('user_id', $request->user()->id)->findOrFail($id);
        
        $request->validate([
            'activity_name' => 'sometimes|required|string',
            'start_time' => 'sometimes|required|string',
            'is_done' => 'sometimes|required|boolean',
        ]);

        $schedule->update($request->all());

        return response()->json($schedule);
    }

    public function destroy(Request $request, $id)
    {
        $schedule = Schedule::where('user_id', $request->user()->id)->findOrFail($id);
        $schedule->delete();

        return response()->json(['message' => 'Jadwal dihapus']);
    }
}