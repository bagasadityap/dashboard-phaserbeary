<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpsiTambahanPesananGedung extends Model
{
    protected $fillable = [
        'nama',
        'harga',
        'pesananId',
    ];

    public function pesanan()
    {
        return $this->belongsTo(PesananGedung::class, 'pesanan_gedungs', 'id', 'pesananId');
    }
}
