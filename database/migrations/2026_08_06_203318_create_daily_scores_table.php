<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date'); // Buat nyatet ini skor tanggal berapa
            $table->integer('score')->default(0); // Total skor gabungan di hari itu
            $table->boolean('is_active')->default(false); // True kalau hari itu lu ada ngerjain minimal 1 jadwal
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_scores');
    }
};