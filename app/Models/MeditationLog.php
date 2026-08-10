<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeditationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_name',
        'time',
        'points',
        'is_done', // Jangan lupa tambahin ini
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}