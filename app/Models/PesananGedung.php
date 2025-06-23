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
        'noHP',
        'jumlahPeserta',
        'suratPermohonanAcara',
        'dokumenOpsional',
        'buktiPembayaran',
        'dataPartisipan',
        'dokumenOperator',
        'deskripsiAcara',
        'catatan',
        'alasanPenolakan',
        'hargaGedung',
        'PPN',
        'totalHarga',
        'isConfirmed',
        'isPaid',
        'status',
        'userId',
        'confirmedBy',
        'gedungId',
    ];

    public static function pesananSaya()
    {
        $user_id = auth()->user()->id;

        $pesanan = PesananGedung::where('userId', $user_id)
            ->with('gedung')
            ->select('id', 'status', 'judul', 'created_at')
            ->get()
            ->map(function ($item) {
                $item->type = 'gedung';
                return $item;
            });
        return $pesanan;
    }

    public function user() {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function confirmedBy() {
        return $this->belongsTo(User::class, 'confirmedBy', 'id');
    }

    public function gedungPesanan()
    {
        return $this->hasMany(GedungPesanan::class, 'pesanan_gedung_id');
    }

    public function gedungTersedia()
    {
        return $this->belongsToMany(Gedung::class, 'gedung_pesanans', 'pesanan_gedung_id', 'gedung_id');
    }

    public function gedung() {
        return $this->belongsTo(Gedung::class, 'gedungId', 'id');
    }

    public function opsiTambahan()
    {
        return $this->hasMany(OpsiTambahanPesananGedung::class, 'pesananId');
    }
}
