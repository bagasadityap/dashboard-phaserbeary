<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
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
        // Mengaktifkan query log
        \DB::enableQueryLog();

        $pesananGedung = PesananGedung::pesananSaya();
        $pesananPublikasi = PesananPublikasi::pesananSaya();

        $models = collect($pesananGedung)->concat($pesananPublikasi)->sortByDesc('created_at');

        // Mendapatkan semua query yang dieksekusi
        $queries = \DB::getQueryLog();

        // Mengembalikan data ke view
        return view('home.pesanan_saya', compact('models', 'queries'));
    }

    public function detailPesananGedung($id) {
        $model = PesananGedung::findOrFail($id);

        return view('home.detail_pesanan_gedung', compact('model'));
    }

    public function detailPesananPublikasi($id) {
        $model = PesananPublikasi::findOrFail($id);

        return view('home.detail_pesanan_publikasi', compact('model'));
    }

    public function pemesanan_gedung() {
        return view('home.pemesanan_gedung');
    }

    public function pemesanan_publikasi() {
        return view('home.pemesanan_publikasi');
    }

    public function tambahDokumen(Request $request, $id) {
        $type = $request->query('type');
        if ($type == 'gedung') {
            $model = PesananGedung::findOrFail($id);
            return view('home.tambah_dokumen', compact('model', 'type'));
        } else {
            $model = PesananPublikasi::findOrFail($id);
            return view('home.tambah_dokumen', compact('model', 'type'));
        }
    }

    public function storeDokumen(Request $request, $id)
    {
        try {
            if ($request->type == 'gedung') {
                $request->validate([
                    'type' => 'required',
                    'surat_permohonan_acara' => 'nullable|file|mimes:pdf',
                    'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,heic',
                    'dokumen_opsional' => 'nullable|file|mimes:pdf',
                    'data_partisipan' => 'nullable|file|mimes:xls,xlsx',
                ]);

                $fileFields = [
                    'surat_permohonan_acara',
                    'bukti_pembayaran',
                    'dokumen_opsional',
                    'data_partisipan',
                ];

                $model = PesananGedung::findOrFail($id);

                foreach ($fileFields as $field) {
                    if ($request->hasFile($field)) {
                        if ($model->$field && file_exists(storage_path('app/public/dokumen/gedung/' . $model->$field))) {
                            Storage::disk('public')->delete($model->$field);
                        }

                        $file = $request->file($field);
                        $filename = time() . '_' . Str::uuid() . '_' . $file->getClientOriginalName();
                        $filePath = $file->storeAs('dokumen/gedung', $filename, 'public');
                        $model->$field = $filePath;
                    }
                }
                $model->save();
            } else {
                $request->validate([
                    'type' => 'required',
                    'surat_permohonan_acara' => 'nullable|file|mimes:pdf',
                    'poster_acara' => 'nullable|file',
                    'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,heic',
                    'dokumen' => 'nullable|file|mimes:pdf',
                ]);

                $fileFields = [
                    'surat_permohonan_acara',
                    'poster_acara',
                    'bukti_pembayaran',
                    'dokumen_opsional',
                ];
                $model = PesananPublikasi::findOrFail($id);

                foreach ($fileFields as $field) {
                    if ($request->hasFile($field)) {
                        if ($model->$field && file_exists(storage_path('app/public/dokumen/publikasi/' . $model->$field))) {
                            Storage::disk('public')->delete($model->$field);
                        }

                        $file = $request->file($field);
                        $filename = time() . '_' . Str::uuid() . '_' . $file->getClientOriginalName();
                        $filePath = $file->storeAs('dokumen/publikasi', $filename, 'public');
                        $model->$field = $filePath;
                    }
                }
                $model->save();
            }



            return redirect()->back()->with('success', 'Dokumen berhasil diperbarui.');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui dokumen.');
        } catch (\Exception $e) {
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
        try {
            $model = PesananGedung::findOrFail($id2);
            $gedung = Gedung::findOrFail($id);
            $model->gedung_id = $gedung->id;
            $model->total_biaya = $gedung->harga + ($gedung->harga * 10/100);
            $model->status = 2;
            $model->save();
            session()->flash('success', 'Gedung berhasil dipilih.');
            return response()->json([
                'success' => true,
                'message' => 'Gedung berhasil dipilih.'
            ]);
        } catch (ValidationException $e) {
            session()->flash('error', 'Terjadi kesalahan.');
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal'
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan.');
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memilih gedung.'
            ]);
        }
    }
}
