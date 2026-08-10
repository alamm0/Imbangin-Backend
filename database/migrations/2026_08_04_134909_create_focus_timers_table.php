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
        Schema::create('focus_timers', function (Blueprint $table) {
            $table->id();
           $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('focus_min')->default(30);
            $table->integer('rest_min')->default(5);
            $table->integer('max_session')->default(7);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('focus_timers');
    }
};
