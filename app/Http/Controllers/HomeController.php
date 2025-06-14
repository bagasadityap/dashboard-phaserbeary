<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use App\Models\OpsiTambahanPesananGedung;
use App\Models\OpsiTambahanPesananPublikasi;
use App\Models\PesananGedung;
use App\Models\PesananPublikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{
    public function index() {
        return view('home.index');
    }

    public function pesananSaya()
    {
        $pesananGedung = PesananGedung::pesananSaya();
        $pesananPublikasi = PesananPublikasi::pesananSaya();

        $models = collect($pesananGedung)->concat($pesananPublikasi)->sortByDesc('created_at');

        return view('home.pesanan_saya', compact('models'));
    }

    public function detailPesananGedung($id) {
        $model = PesananGedung::findOrFail($id);
        $opsiTambahan = OpsiTambahanPesananGedung::where('pesananId', $id)->get();

        return view('home.detail_pesanan_gedung', compact('model', 'opsiTambahan'));
    }

    public function detailPesananPublikasi($id) {
        $model = PesananPublikasi::findOrFail($id);
        $opsiTambahan = OpsiTambahanPesananPublikasi::where('pesananId', $id)->get();

        return view('home.detail_pesanan_publikasi', compact('model', 'opsiTambahan'));
    }

    public function pemesanan_gedung() {
        return view('home.pemesanan_gedung');
    }

    public function pemesanan_publikasi() {
        return view('home.pemesanan_publikasi');
    }

    public function tambahDokumenGedung(Request $request, $id) {
        $model = PesananGedung::findOrFail($id);
        return view('home.tambah_dokumen_gedung', compact('model'));
    }

    public function tambahDokumenPublikasi(Request $request, $id) {
        $model = PesananGedung::findOrFail($id);
        return view('home.tambah_dokumen_publikasi', compact('model'));
    }

    public function storeDokumenGedung(Request $request, $id)
    {
        try {
            $request->validate([
                'suratPermohonanAcara' => 'nullable|file|mimes:pdf',
                'buktiPembayaran' => 'nullable|file|mimes:jpg,jpeg,png,heic',
                'dokumenOpsional' => 'nullable|file|mimes:pdf',
                'dataPartisipan' => 'nullable|file|mimes:xls,xlsx',
            ]);

            $fileFields = [
                'suratPermohonanAcara',
                'buktiPembayaran',
                'dokumenOpsional',
                'dataPartisipan',
            ];

            $model = PesananGedung::findOrFail($id);

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    if ($model->$field && Storage::disk('public')->exists($model->$field)) {
                        Storage::disk('public')->delete($model->$field);
                    }

                    $file = $request->file($field);
                    $filename = time() . '_' . Str::uuid() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('dokumen/gedung', $filename, 'public');
                    $model->$field = $filePath;
                }
            }

            $model->save();

            return redirect()->back()->with('success', 'Dokumen berhasil diperbarui.');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui dokumen.');
        }
    }

    public function storeDokumenPublikasi(Request $request, $id)
    {
        try {
            $request->validate([
                'suratPermohonanAcara' => 'nullable|file|mimes:pdf',
                'posterAcara' => 'nullable|string|max:255',
                'buktiPembayaran' => 'nullable|file|mimes:jpg,jpeg,png,heic',
                'dokumen' => 'nullable|file|mimes:pdf',
            ]);

            $fileFields = [
                'suratPermohonanAcara',
                'buktiPembayaran',
                'dokumenOpsional',
            ];
            $model = PesananPublikasi::findOrFail($id);
            if ($request->posterAcara) {
                $model->posterAcara = $request->posterAcara;
            }

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    if ($model->$field && Storage::disk('public')->exists($model->$field)) {
                        Storage::disk('public')->delete($model->$field);
                    }

                    $file = $request->file($field);
                    $filename = time() . '_' . Str::uuid() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('dokumen/publikasi', $filename, 'public');
                    $model->$field = $filePath;
                }
            }
            $model->save();

            return redirect()->back()->with('success', 'Dokumen berhasil diperbarui.');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui dokumen.');
        }
    }

    public function pilihGedung(Request $request, $id) {
        $page = '';
        $model = Gedung::pilih($id);

        if ($request->ajax()) {
            return DataTables::of($model)
                ->addColumn('_', function($model) use ($id) {
                    $html = '';
                    $html .= '<button href="" class="btn btn-outline-primary px-2 me-1 d-inline-flex align-items-center" onclick="view(\'' . $model->id . '\')"><i class="iconoir-eye fs-14 me-1"></i>Detail</button>';
                    $html .= '<button href="" class="btn btn-outline-success px-2 me-1 d-inline-flex align-items-center" onclick="pilih(\'' . $model->id . '\', \'' . $id . '\')"><i class="iconoir-check fs-14 me-1"></i>Pilih</button>';
                    return $html;
                })
                ->editColumn('harga', function($model) {
                    $harga = number_format($model->harga, 0, ',', '.');
                    $html = 'Rp. ' . $harga;
                    return $html;
                })
                ->rawColumns(['_'])
                ->make(true);
        }

        return view('home.pilih_gedung',compact('page'));
    }

    public function pilih($id, $id2) {
        $model = PesananGedung::findOrFail($id2);
        $gedung = Gedung::findOrFail($id);
        $opsiTambahanTotal = OpsiTambahanPesananGedung::where('pesananId', $model->id)->sum('harga');
        $totalHarga = $gedung->harga + $opsiTambahanTotal;
        $model->update([
            'gedungId' => $gedung->id,
            'hargaGedung' => $gedung->harga,
            'status' => 2,
            'PPN' => $totalHarga * 10/100,
            'totalHarga' => $totalHarga + ($totalHarga * 10/100)
        ]);
        session()->flash('success', 'Gedung berhasil dipilih.');
    }
}
