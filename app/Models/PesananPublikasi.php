<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesananPublikasi extends Model
{
    protected $fillable = [
        'judul',
        'tanggal',
        'noHP',
        'suratPermohonanAcara',
        'dokumenOpsional',
        'buktiPembayran',
        'posterAcara',
        'catatan',
        'hargaGedung',
        'PPN',
        'totalHarga',
        'isConfirmed',
        'isPaid',
        'invoice',
        'status',
        'userId',
    ];

    public static function pesananSaya()
    {
        $user_id = auth()->user()->id;

        $pesanan = PesananPublikasi::where('userId', $user_id)
            ->select('id', 'status', 'judul', 'created_at')
            ->get()
            ->map(function ($item) {
                $item->type = 'publikasi';
                return $item;
            });
        return $pesanan;
    }

    public function user() {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function opsiTambahan()
    {
        return $this->hasMany(OpsiTambahanPesananPublikasi::class, 'pesananId');
    }
}
