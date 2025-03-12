<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesananPublikasi extends Model
{
    protected $fillable = [
        'judul',
        'tanggal',
        'no_hp',
        'surat_permohonan_acara',
        'dokumen_opsional',
        'bukti_pembayran',
        'poster_acara',
        'catatan',
        'user_id',
        'total_biaya',
        'is_verified',
        'is_paid',
        'invoice',
        'status',
    ];

    public static function pesananSaya()
    {
        $user_id = auth()->user()->id;

        $pesanan = PesananPublikasi::where('user_id', $user_id)
            ->select('id', 'status', 'judul', 'created_at')
            ->get()
            ->map(function ($item) {
                $item->type = 'publikasi';
                return $item;
            });
        return $pesanan;
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
