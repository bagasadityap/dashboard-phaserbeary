<?php

namespace App\Http\Controllers;

use App\Models\OpsiTambahanPesananPublikasi;
use App\Models\PesananPublikasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class PesananPublikasiController extends Controller
{
    public function index(Request $request) {
        $page = 'Pesanan \ Publikasi';
        $model = PesananPublikasi::with('user');

        if ($request->ajax()) {
            return DataTables::of($model)
                ->addColumn('_', function($model) {
                    $html = '';
                    if (auth()->user()->can('Pesanan Publikasi Acara Read')) {
                        $html .= '<button href="" class="btn btn-outline-primary px-2 me-1 d-inline-flex align-items-center" onclick="view(\'' . $model->id . '\')"><i class="iconoir-eye fs-14"></i></button>';
                    }
                    return $html;
                })
                ->editColumn('created_at', function($model) {
                    return Carbon::parse($model->created_at)->translatedFormat('d F Y');
                })
                ->editColumn('tanggal', function($model) {
                    return Carbon::parse($model->tanggal)->translatedFormat('d F Y');
                })
                ->rawColumns(['_'])
                ->make(true);
        }

        return view('pesanan.publikasi.index', compact('page'));
    }

    public function view($id) {
        $page = "Pesanan \ Publikasi \ Detail";
        $model = PesananPublikasi::findOrFail($id);
        $opsiTambahan = OpsiTambahanPesananPublikasi::where('pesananId', $id)->get();

        return view('pesanan.publikasi.detail_pesanan', compact('page', 'model', 'opsiTambahan'));
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'noHP' => 'required|string|max:255',
                'suratPermohonanAcara' => 'required|file',
                'deskripsiAcara' => 'required|string',
                'catatan' => 'nullable|string',
            ]);

            $dokumen = $request->file('suratPermohonanAcara');
            $filename = time() . '_' . Str::uuid() . '_' . $dokumen->getClientOriginalName();
            $dokumenPath = $dokumen->storeAs('dokumen/publikasi', $filename, 'public');

            $model = PesananPublikasi::create([
                'judul' => $request->judul,
                'tanggal' => $request->tanggal,
                'noHP' => $request->noHP,
                'suratPermohonanAcara' => $dokumenPath,
                'deskripsiAcara' => $request->deskripsiAcara,
                'catatan' => $request->catatan,
                'userId' => auth()->user()->id,
            ]);

            return redirect()->route('home.detail-pesanan-publikasi', ['id' => $model->id])->with('success', 'Pesanan berhasil ditambahkan');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan.');
        }
    }

    public function confirm(Request $request, $id) {
        try {
            $model = PesananPublikasi::findOrFail($id);
            if ($request->status == 'konfirmasi') {
                $model->status = 1;
                $model->isConfirmed = 1;
                $model->confirmedBy = auth()->user()->id;
            } else {
                $model->status = 4;
                $model->alasanPenolakan = $request->alasanPenolakan;
            }
            $model->save();
            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diubah'
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan.');
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat konfirmasi data.'
            ]);
        }
    }

    public function confirmPayment(Request $request, $id) {
        try {
            $model = PesananPublikasi::findOrFail($id);
            if ($request->status == 'konfirmasi') {
                $model->isPaid = 1;
                $model->status = 3;
            } else {
                $model->status = 5;
                $model->alasanPenolakan = $request->alasanPenolakan;
            }
            $model->save();
            session()->flash('success', 'Status pesanan berhasil diubah.');
            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diubah'
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan.');
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat konfirmasi data.'
            ]);
        }
    }

    public function tambahDokumen(Request $request, $id) {
        $model = PesananPublikasi::findOrFail($id);
        return view('pesanan.publikasi.tambah_dokumen', compact('model'));
    }

    public function storeDokumen(Request $request, $id)
    {
        try {
            $request->validate([
                'nama' => 'array',
                'file' => 'array',
                'file.*' => 'file',
            ]);

            $model = PesananPublikasi::findOrFail($id);
            $dokumenOperator = $model->dokumenOperator;
            $oldDokumen = json_decode($dokumenOperator, true) ?? [];
            $files = is_array($request->file('file')) ? $request->file('file') : [];
            $namaBaru = is_array($request->nama) ? $request->nama : [];

            foreach ($oldDokumen as $dokumen) {
                if (!isset($dokumen['nama'], $dokumen['file'])) continue;

                $namaLama = $dokumen['nama'];
                $fileLama = $dokumen['file'];

                $index = array_search($namaLama, $namaBaru);
                $fileBaru = $files[$index] ?? null;

                if ($index === false || $fileBaru) {
                    if (Storage::disk('public')->exists($fileLama)) {
                        Storage::disk('public')->delete($fileLama);
                    }
                }
            }

            $newDokumen = [];

            for ($i = 0; $i < count($namaBaru); $i++) {
                $nama = $request->nama[$i];
                $fileInput = $request->file('file')[$i] ?? null;
                $existing = collect($oldDokumen)->firstWhere('nama', $nama);

                if ($fileInput) {
                    $filename = time() . '_' . Str::uuid() . '_' . $fileInput->getClientOriginalName();
                    $filePath = $fileInput->storeAs('dokumen/publikasi', $filename, 'public');
                    $newDokumen[] = [
                        'nama' => $nama,
                        'file' => $filePath,
                    ];
                } elseif ($existing) {
                    $newDokumen[] = [
                        'nama' => $existing['nama'],
                        'file' => $existing['file'],
                    ];
                }
            }

            $model->dokumenOperator = $newDokumen;
            $model->save();

            return redirect()->back()->with('success', 'Dokumen berhasil diperbarui.');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui dokumen.');
        }
    }

    public function tambahHargaPesanan($id) {
        $model = PesananPublikasi::findOrFail($id);
        $models = OpsiTambahanPesananPublikasi::where('pesananId', $model->id)->get();

        return view('pesanan.publikasi.tambah_opsional_pesanan', compact('model', 'models'));
    }

    public function storeHargaPesanan(Request $request, $id) {
        try {
            $request->validate([
                'hargaPublikasi' => 'required|int',
                'nama' => 'array',
                'harga' => 'array'
            ]);

            $pesanan = PesananPublikasi::findOrFail($id);
            $pesanan->hargaPublikasi = $request->hargaPublikasi;
            $pesanan->save();

            OpsiTambahanPesananPublikasi::where('pesananId', $pesanan->id)->delete();

            if ($request->nama && $request->harga) {
                for ($i=0; $i < count($request->nama); $i++) {
                    OpsiTambahanPesananPublikasi::create([
                        'nama' => $request->nama[$i],
                        'harga' => $request->harga[$i],
                        'pesananId' => $pesanan->id,
                    ]);
                }
            }

            $opsiTambahanTotal = OpsiTambahanPesananPublikasi::where('pesananId', $pesanan->id)->sum('harga');
            $totalHarga = $pesanan->hargaPublikasi + $opsiTambahanTotal;
            $pesanan->update([
                'PPN' => $totalHarga * 10/100,
                'totalHarga' => $totalHarga + ($totalHarga * 10/100)
            ]);

            return redirect()->back()->with('success', 'Data berhasil disimpan.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function downloadInvoice($id)  {
        $model = PesananPublikasi::findOrFail($id);
        $tambahanOpsional = OpsiTambahanPesananPublikasi::where('pesananId', $id)->get();
        $confirmedBy = $model->confirmedBy()->first()->name;

        $pdf = Pdf::loadView('template.invoice_publikasi', compact('model', 'tambahanOpsional', 'confirmedBy'));

        $filename = 'INVOICE_' . $model->id . '-BCE-I-DPKA-II-' . $model->created_at->format('Y') . '.pdf';

        return $pdf->download($filename);
    }
}
