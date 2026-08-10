<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $userMessage = $request->message;
        $userName = $request->user()->name ?? 'Pengguna';
        $systemPrompt = "Kamu adalah IMBANGIN AI, asisten kesehatan pribadi yang suportif, pintar, dan asik. Jawablah dengan ramah, informatif, gunakan bahasa yang membumi, dan jangan terlalu panjang. Pengguna bernama {$userName}.";

        // Ambil kunci Groq dari .env
        $apiKey = env('GROQ_API_KEY');

        try {
            // Nembak API Groq (Model Llama 3)
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->withoutVerifying()->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.1-8b-instant', // Otak Llama 3 yang super cepat & gratis
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage],
                ],
            ]);

            // Kalau sukses dibalas
            if ($response->successful()) {
                $reply = $response->json('choices.0.message.content');
                
                // Simpan ke database biar history chat jalan
                try {
                    if (class_exists(\Illuminate\Support\Facades\Schema::class) && \Illuminate\Support\Facades\Schema::hasTable('ai_chats')) {
                        AiChat::create([
                            'user_id' => $request->user()->id ?? 1,
                            'user_message' => $userMessage,
                            'ai_response' => $reply,
                        ]);
                    }
                } catch (\Exception $e) {}

                return response()->json(['reply' => $reply]);
            }

            // Kalau gagal, kasih tau errornya apa
            return response()->json([
                'error' => 'Gagal dari API Groq',
                'message' => $response->json()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Sistem Backend Crash',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}