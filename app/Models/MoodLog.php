<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodLog extends Model
{
    use HasFactory;

    // Baris ini memberi tahu Laravel: "Izinkan semua kolom di tabel ini untuk diisi data"
    protected $guarded = []; 
}
