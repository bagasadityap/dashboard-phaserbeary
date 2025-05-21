<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gedung extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'lokasi',
        'kapasitas',
        'harga',
        'deskripsi',
        'gambar',
        'gambarVR',
        'fasilitas',
    ];

    public static function pilih($id) {
        return Gedung::whereHas('pesanan', function ($query) use ($id) {
            $query->where('pesanan_gedung_id', $id);
        });
    }

    public function pesanan()
    {
        return $this->belongsToMany(PesananGedung::class, 'gedung_pesanans', 'gedung_id', 'pesanan_gedung_id');
    }
}
