<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SleepTracker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'sleep_time', 'wake_time', 'duration_hours', 'quality'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}