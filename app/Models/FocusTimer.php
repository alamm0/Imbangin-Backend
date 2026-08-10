<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FocusTimer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'focus_min',
        'rest_min',
        'max_session',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}