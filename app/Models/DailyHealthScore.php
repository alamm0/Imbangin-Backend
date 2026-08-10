<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyHealthScore extends Model
{
    use HasFactory;

    // Baris ini memberi tahu Laravel: "Izinkan semua kolom di tabel ini untuk diisi data"
    protected $guarded = []; 
}