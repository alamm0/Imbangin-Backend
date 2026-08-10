<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'score',
        'is_active',
    ];
}