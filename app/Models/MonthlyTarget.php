<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyTarget extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi
    protected $fillable = [
        'user_id',
        'name',
        'is_done',
    ];

    // Relasi ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}