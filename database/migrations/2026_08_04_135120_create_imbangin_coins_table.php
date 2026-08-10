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
        Schema::create('imbangin_coins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('amount'); // Jumlah koin (bisa plus/minus)
            $table->string('description'); // Keterangan (misal: "Hadiah tidur 8 jam")
            $table->enum('type', ['earn', 'spend']); // Status dapat atau pakai koin
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imbangin_coins');
    }
};
