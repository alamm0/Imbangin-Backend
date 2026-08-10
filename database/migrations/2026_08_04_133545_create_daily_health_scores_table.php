<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_health_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Menyambungkan skor ini ke akun user yang login
            $table->date('record_date'); // Tanggal skor dicatat (karena ini skor harian)
            $table->integer('total_score')->default(0); // Skor besar (contoh: 20)
            $table->integer('sleep_score')->default(0); // Poin tidur dari UI
            $table->integer('food_score')->default(0);  // Poin makan dari UI
            $table->integer('focus_score')->default(0); // Poin fokus dari UI
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_health_scores');
    }
};
