<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananGedung extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'tanggal',
        'no_hp',
        'surat_permohonan_acara',
        'dokumen_opsional',
        'data_partisipan',
        'catatan',
        'user_id',
        'gedung_id',
        'total_biaya',
        'is_verified',
        'is_paid',
        'invoice',
        'status',
    ];

    public static function pesanan_saya() {
        $user_id = auth()->user()->id;

        return PesananGedung::where('user_id', $user_id)->get();
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
