<?php

namespace App\Http\Controllers\Api; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MeditationLog;

class MeditationLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = MeditationLog::where('user_id', $request->user()->id)->get();
        return response()->json($logs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity_name' => 'required|string',
            'time' => 'required|string',
        ]);

        $log = MeditationLog::create([
            'user_id' => $request->user()->id,
            'activity_name' => $request->activity_name,
            'time' => $request->time,
            'is_done' => false 
        ]);

        return response()->json($log, 201);
    }

    public function update(Request $request, $id)
    {
        $log = MeditationLog::where('id', $id)->where('user_id', $request->user()->id)->first();

        if (!$log) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        if ($request->exists('is_done')) {
            $log->is_done = $request->boolean('is_done');
        }

        if ($request->has('activity_name')) {
            $log->activity_name = $request->activity_name;
        }
        if ($request->has('time')) {
            $log->time = $request->time;
        }

        $log->save();

        return response()->json($log);
    }

    public function destroy(Request $request, $id)
    {
        $log = MeditationLog::where('id', $id)->where('user_id', $request->user()->id)->first();
        if ($log) {
            $log->delete();
            return response()->json(['message' => 'Dihapus']);
        }
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }
}