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
        Schema::create('meal_schedules', function (Blueprint $table) {
           $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('meal_name'); // Sarapan, Makan Siang, Makan Malam
            $table->string('time_range'); // 07:00-09:00
            $table->boolean('is_done')->default(false); // Buat fitur ceklis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_schedules');
    }
};
