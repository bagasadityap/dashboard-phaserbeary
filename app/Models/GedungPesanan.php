<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GedungPesanan extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'pesanan_gedung_id',
        'gedung_id',
    ];
}
