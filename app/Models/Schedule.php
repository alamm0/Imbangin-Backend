<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_name',
        'start_time',
        'schedule_date',
        'is_done', // Jangan lupa tambahin ini
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}