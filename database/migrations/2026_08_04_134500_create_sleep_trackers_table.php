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
        Schema::create('sleep_trackers', function (Blueprint $table) {
         $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('sleep_time')->default('22:00');
            $table->string('wake_time')->default('05:00');
            $table->integer('duration_hours')->default(7);
            $table->string('quality')->default('Ideal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sleep_trackers');
    }
};
