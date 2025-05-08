<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpsiTambahanPesananPublikasi extends Model
{
    protected $fillable = [
        'nama',
        'biaya',
        'pesananId',
    ];

    public function pesanan()
    {
        return $this->belongsTo(PesananGedung::class, 'pesanan_publikasis', 'id', 'pesananId');
    }
}
