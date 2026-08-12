<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MonthlyTarget;
use Illuminate\Http\Request;

class MonthlyTargetController extends Controller
{
    // Mengambil semua target mingguan milik user yang sedang login
    public function index(Request $request)
    {
        $targets = MonthlyTarget::where('user_id', $request->user()->id)->get();
        $targets->transform(function ($target) {
            $target->isDone = $target->is_done; 
            return $target;
        });

        return response()->json($targets);
    }

    // Menyimpan target mingguan baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $status = false;
        if ($request->has('isDone')) {
            $status = $request->boolean('isDone');
        } elseif ($request->has('is_done')) {
            $status = $request->boolean('is_done');
        }

        $target = MonthlyTarget::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'is_done' => $status,
        ]);

        $target->isDone = $target->is_done;

        return response()->json($target, 201);
    }

    // Mengupdate target mingguan (Ganti nama atau Toggle Ceklis)
    public function update(Request $request, $id)
    {
        $target = MonthlyTarget::where('id', $id)
                    ->where('user_id', $request->user()->id)
                    ->first();

        if (!$target) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        if ($request->has('isDone')) {
            $target->is_done = $request->boolean('isDone');
        } elseif ($request->has('is_done')) {
            $target->is_done = $request->boolean('is_done');
        }

        if ($request->has('name')) {
            $target->name = $request->name;
        }

        $target->save();
        $target->isDone = $target->is_done;

        return response()->json($target);
    }

    // Menghapus target mingguan
    public function destroy(Request $request, $id)
    {
        $target = MonthlyTarget::where('id', $id)
                    ->where('user_id', $request->user()->id)
                    ->first();

        if (!$target) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $target->delete();

        return response()->json(['message' => 'Target berhasil dihapus']);
    }
}