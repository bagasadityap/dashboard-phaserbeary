<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use App\Models\OpsiTambahanPesananGedung;
use App\Models\PesananGedung;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Mpdf\Mpdf;
use Spatie\Browsershot\Browsershot;
use Yajra\DataTables\Facades\DataTables;

class PesananGedungController extends Controller
{
    public function index(Request $request) {
        $page = 'Pesanan \ Gedung';
        $model = PesananGedung::with('user');

        if ($request->ajax()) {
            return DataTables::of($model)
                ->addColumn('_', function($model) {
                    $html = '';
                    if (auth()->user()->can('Pesanan Gedung Read')) {
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

        return view('pesanan.gedung.index', compact('page'));
    }

    public function view($id) {
        $page = "Pesanan \ Gedung \ Detail";
        $model = PesananGedung::findOrFail($id);
        $selectedModel = PesananGedung::findOrFail($id);
        $gedungs = Gedung::all();
        $selectedGedung = $selectedModel->gedungPesanan()->pluck('gedung_id')->toArray();
        $opsiTambahan = OpsiTambahanPesananGedung::where('pesananId', $id)->get();

        return view('pesanan.gedung.detail_pesanan', compact('page', 'model', 'gedungs', 'selectedGedung', 'opsiTambahan'));
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'jumlahPeserta' => 'required|integer',
                'noHP' => 'required|string|max:255',
                'suratPermohonanAcara' => 'required|file',
                'catatan' => 'nullable|string',
                'deskripsiAcara' => 'nullable|string',
            ]);

            $dokumen = $request->file('suratPermohonanAcara');
            $filename = time() . '_' . Str::uuid() . '_' . $dokumen->getClientOriginalName();
            $dokumenPath = $dokumen->storeAs('dokumen/gedung', $filename, 'public');

            $model = PesananGedung::create([
                'judul' => $request->judul,
                'tanggal' => $request->tanggal,
                'jumlahPeserta' => $request->jumlahPeserta,
                'noHP' => $request->noHP,
                'suratPermohonanAcara' => $dokumenPath,
                'catatan' => $request->catatan,
                'deskripsiAcara' => $request->deskripsiAcara,
                'userId' => auth()->user()->id,
            ]);

            return redirect()->route('home.detail-pesanan-gedung', ['id' => $model->id])->with('success', 'Pesanan berhasil ditambahkan.');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan.');
        }
    }

    public function inputGedung(Request $request, $id)
    {
        $pesanan = PesananGedung::findOrFail($id);
        $pesanan->gedungTersedia()->sync($request->gedungs);

        return redirect()->route('pesanan.gedung.view', ['id' => $id])->with('success', 'Data berhasil disimpan.');
    }

    public function confirm(Request $request, $id) {
        try {
            $model = PesananGedung::findOrFail($id);
            if ($request->status == 'konfirmasi') {
                $model->status = 1;
                $model->isConfirmed = 1;
                $model->confirmedBy = auth()->user()->id;
            } else {
                $model->status = 4;
                $model->alasanPenolakan = $request->alasanPenolakan;
            }
            $model->save();
            session()->flash('success', 'Status pesanan berhasil diubah.');
            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diubah.'
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
            $model = PesananGedung::findOrFail($id);
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
        $model = PesananGedung::findOrFail($id);
        return view('pesanan.gedung.tambah_dokumen', compact('model'));
    }

    public function storeDokumen(Request $request, $id)
    {
        $model = PesananGedung::findOrFail($id);
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
                $filePath = $fileInput->storeAs('dokumen/gedung', $filename, 'public');
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
    }

    public function tambahOpsional($id) {
        $model = PesananGedung::findOrFail($id);
        $models = OpsiTambahanPesananGedung::where('pesananId', $model->id)->get();

        return view('pesanan.gedung.tambah_opsional_pesanan', compact('model', 'models'));
    }

    public function storeOpsional(Request $request, $id) {
        try {
            $request->validate([
                'nama' => 'array',
                'nama.*' => 'string',
                'harga' => 'array',
                'harga.*' => 'numeric|min:0',
            ]);

            $pesanan = PesananGedung::findOrFail($id);
            OpsiTambahanPesananGedung::where('pesananId', $pesanan->id)->delete();

            if ($request->nama && $request->harga) {
                for ($i=0; $i < count($request->nama); $i++) {
                    OpsiTambahanPesananGedung::create([
                        'nama' => $request->nama[$i],
                        'harga' => $request->harga[$i],
                        'pesananId' => $pesanan->id,
                    ]);
                }
            }

            $opsiTambahanTotal = OpsiTambahanPesananGedung::where('pesananId', $pesanan->id)->sum('harga');
            $hargaGedung = $pesanan->hargaGedung ?? 0;
            $totalHarga = $hargaGedung + $opsiTambahanTotal;
            $pesanan->update([
                'PPN' => $totalHarga * 10/100,
                'totalHarga' => $totalHarga + ($totalHarga * 10/100)
            ]);

            return redirect()->back()->with('success', 'Data berhasil disimpan.');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan.');
        }
    }

    public function downloadInvoice($id)
    {
        try {
            $model = PesananGedung::findOrFail($id);
            $tambahanOpsional = OpsiTambahanPesananGedung::where('pesananId', $id)->get();
            $confirmedBy = $model->confirmedBy()->first()->name;

            $html = View::make('template.invoice_gedung', compact('model', 'tambahanOpsional', 'confirmedBy'))->render();

            $mpdf = new Mpdf([
                'format' => 'A4',
                'margin_top' => 10,
                'margin_right' => 10,
                'margin_bottom' => 10,
                'margin_left' => 10,
            ]);

            $mpdf->WriteHTML($html);

            $filename = 'INVOICE_' . $model->id . '-BCE-I-DPKA-II-' . $model->created_at->format('Y') . '.pdf';
            $path = storage_path("app/public/$filename");

            $mpdf->Output($path, \Mpdf\Output\Destination::FILE);

            return response()->download($path)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e);
        }
    }
}
