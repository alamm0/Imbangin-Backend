<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FocusTimer;
use Illuminate\Http\Request;

class FocusTimerController extends Controller
{
    public function index(Request $request)
    {
        $timer = FocusTimer::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['focus_min' => 30, 'rest_min' => 5, 'max_session' => 7]
        );

        return response()->json($timer);
    }

    public function store(Request $request)
    {
        $request->validate([
            'focus_min' => 'required|integer|min:1',
            'rest_min' => 'required|integer|min:1',
            'max_session' => 'required|integer|min:1',
        ]);

        $timer = FocusTimer::updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'focus_min' => $request->focus_min,
                'rest_min' => $request->rest_min,
                'max_session' => $request->max_session,
            ]
        );

        return response()->json($timer);
    }
}