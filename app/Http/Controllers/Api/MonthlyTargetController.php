<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MonthlyTarget;
use Illuminate\Http\Request;

class MonthlyTargetController extends Controller
{
    // Mengambil semua target bulanan milik user yang sedang login
    public function index(Request $request)
    {
        $targets = MonthlyTarget::where('user_id', $request->user()->id)->get();
        
        // Transformasi is_done dari database jadi isDone buat dibaca React
        $targets->transform(function ($target) {
            $target->isDone = $target->is_done; 
            return $target;
        });

        return response()->json($targets);
    }

    // Menyimpan target bulanan baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Tangkap isDone dari React, atau is_done kalau pakai Postman
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

        // Selaraskan balikan datanya buat React
        $target->isDone = $target->is_done;

        return response()->json($target, 201);
    }

    // Mengupdate target bulanan (Ganti nama atau Toggle Ceklis)
    public function update(Request $request, $id)
    {
        $target = MonthlyTarget::where('id', $id)
                    ->where('user_id', $request->user()->id)
                    ->first();

        if (!$target) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // TERJEMAHANNYA DI SINI:
        // Apapun yang dikirim React (isDone), arahin masuknya ke kolom is_done
        if ($request->has('isDone')) {
            $target->is_done = $request->boolean('isDone');
        } elseif ($request->has('is_done')) {
            $target->is_done = $request->boolean('is_done');
        }

        // Update nama target kalau lagi diedit
        if ($request->has('name')) {
            $target->name = $request->name;
        }

        $target->save();

        // Selaraskan balikan datanya buat React
        $target->isDone = $target->is_done;

        return response()->json($target);
    }

    // Menghapus target bulanan
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