<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use App\Models\OpsiTambahanPesananGedung;
use App\Models\PesananGedung;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
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
                    if (auth()->user()->can('Gedung Read')) {
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

    public function store(Request $request) {
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'jumlahPeserta' => 'required|integer',
                'noHP' => 'required|string|max:255',
                'suratPermohonanAcara' => 'required|file',
                'catatan' => 'nullable|string',
            ]);

            $dokumen = $request->file('suratPermohonanAcara');
            $filename = time() . '_' . Str::uuid() . '_' . $dokumen->getClientOriginalName();
            $dokumenPath = $dokumen->storeAs('dokumen/publikasi', $filename, 'public');

            $model = PesananGedung::create([
                'judul' => $request->judul,
                'tanggal' => $request->tanggal,
                'jumlahPeserta' => $request->jumlahPeserta,
                'noHP' => $request->noHP,
                'suratPermohonanAcara' => $dokumenPath,
                'catatan' => $request->catatan,
                'userId' => auth()->user()->id,
            ]);

            return redirect()->route('home.detail-pesanan-gedung', ['id' => $model->id])->with('success', 'Pesanan berhasil ditambahkan');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan.');
        }
    }

    public function inputGedung(Request $request, $id)
    {
        try {
            $request->validate([
                'gedungs' => 'required|array',
                'gedungs.*' => 'exists:gedungs,id'
            ]);

            $pesanan = PesananGedung::findOrFail($id);
            $pesanan->gedungTersedia()->sync($request->gedungs);

            return redirect()->route('pesanan.gedung.view', ['id' => $id])->with('success', 'Data berhasil disimpan.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function confirm(Request $request, $id) {
        try {
            $model = PesananGedung::findOrFail($id);
            if ($request->status == 'konfirmasi') {
                $model->status = 1;
                $model->isConfirmed = 1;
            } else {
                $model->status = 4;
            }
            $model->save();
            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diubah'
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
                'message' => 'Terjadi kesalahan saat konfirmasi data.'
            ]);
        }
    }

    public function confirmPayment($id) {
        try {
            $model = PesananGedung::findOrFail($id);
            $model->isPaid = 1;
            $model->status = 3;
            $model->save();
            session()->flash('success', 'Status pesanan berhasil diubah.');
            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diubah'
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
                'message' => 'Terjadi kesalahan saat konfirmasi data.'
            ]);
        }
    }

    public function tambahOptional($id) {
        $model = PesananGedung::findOrFail($id);
        $models = OpsiTambahanPesananGedung::where('pesananId', $model->id)->get();

        return view('pesanan.gedung.tambah_opsional_pesanan', compact('model', 'models'));
    }

    public function storeOptional(Request $request, $id) {
        try {
            $request->validate([
                'nama' => 'required|array',
                'biaya' => 'required|array'
            ]);

            $pesanan = PesananGedung::findOrFail($id);
            $oldOption = OpsiTambahanPesananGedung::where('pesananId', $pesanan->id)->delete();

            for ($i=0; $i < count($request->nama); $i++) {
                OpsiTambahanPesananGedung::create([
                    'nama' => $request->nama[$i],
                    'biaya' => $request->biaya[$i],
                    'pesananId' => $pesanan->id,
                ]);
            }

            $opsiTambahanTotal = OpsiTambahanPesananGedung::where('pesananId', $pesanan->id)->sum('biaya');
            $biayaGedung = $pesanan->biayaGedung ?? 0;
            $totalBiaya = $biayaGedung + $opsiTambahanTotal;
            $pesanan->update([
                'PPN' => $totalBiaya * 10/100,
                'totalBiaya' => $totalBiaya + ($totalBiaya * 10/100)
            ]);

            return redirect()->back()->with('success', 'Data berhasil disimpan.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
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

    public function downloadExcel() {
        
    }

    public function downloadInvoice($id)  {
        try {
            $model = PesananGedung::findOrFail($id);
            $tambahanOpsional = OpsiTambahanPesananGedung::where('pesananId', $id)->get();

            $html = View::make('template.invoice_gedung', compact('model', 'tambahanOpsional'))->render();

            $filename = 'INVOICE_' . $model->id . '-BCE-I-DPKA-II-' . $model->created_at->format('Y') . '.pdf';
            $path = storage_path("app/public/$filename");

            Browsershot::html($html)
                ->noSandbox()
                ->format('A4')
                ->margins(10, 10, 10, 10)
                ->savePdf($path);

            return response()->download($path)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh invoice.');
        }
    }
}
