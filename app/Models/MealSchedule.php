<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meal_name',
        'time_range',
        'is_done',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}