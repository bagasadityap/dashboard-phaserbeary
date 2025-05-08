<?php

namespace App\Http\Controllers;

use App\Models\OpsiTambahanPesananPublikasi;
use App\Models\PesananGedung;
use App\Models\PesananPublikasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

        return view('pesanan.publikasi.index', compact('page'));
    }

    public function detail() {
        $page = 'Pesanan \ Publikasi \ Detail';

        return view('pesanan.gedung.detail_pesanan', compact('page'));
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'noHP' => 'required|string|max:255',
                'suratPermohonanAcara' => 'required|file',
                'catatan' => 'nullable|string',
            ]);

            $dokumen = $request->file('suratPermohonanAcara');
            $filename = time() . '_' . Str::uuid() . '_' . $dokumen->getClientOriginalName();
            $dokumenPath = $dokumen->storeAs('dokumen/gedung', $filename, 'public');

            $model = PesananPublikasi::create([
                'judul' => $request->judul,
                'tanggal' => $request->tanggal,
                'noHP' => $request->noHP,
                'suratPermohonanAcara' => $dokumenPath,
                'catatan' => $request->catatan,
                'userId' => auth()->user()->id,
            ]);

            return redirect()->route('home.detail-pesanan-publikasi', ['id' => $model->id])->with('success', 'Pesanan berhasil ditambahkan');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan.');
        }
    }

    public function confirm(Request $request, $id) {
        try {
            $model = PesananPublikasi::findOrFail($id);
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
            $model = PesananPublikasi::findOrFail($id);
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

    public function tambahBiayaPesanan($id) {
        $model = PesananPublikasi::findOrFail($id);
        $models = OpsiTambahanPesananPublikasi::where('pesananId', $model->id)->get();

        return view('pesanan.publikasi.tambah_opsional_pesanan', compact('model', 'models'));
    }

    public function storeBiayaPesanan(Request $request, $id) {
        try {
            $request->validate([
                'biayaPublikasi' => 'required|int',
                'nama' => 'array',
                'biaya' => 'array'
            ]);

            $pesanan = PesananPublikasi::findOrFail($id);
            $pesanan->biayaPublikasi = $request->biayaPublikasi;
            $pesanan->save();

            $oldOption = OpsiTambahanPesananPublikasi::where('pesananId', $pesanan->id)->delete();

            if ($request->nama && $request->biaya) {
                for ($i=0; $i < count($request->nama); $i++) {
                    OpsiTambahanPesananPublikasi::create([
                        'nama' => $request->nama[$i],
                        'biaya' => $request->biaya[$i],
                        'pesananId' => $pesanan->id,
                    ]);
                }
            }

            $opsiTambahanTotal = OpsiTambahanPesananPublikasi::where('pesananId', $pesanan->id)->sum('biaya');
            $totalBiaya = $pesanan->biayaPublikasi + $opsiTambahanTotal;
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
        $page = "Pesanan \ Publikasi \ Detail";
        $model = PesananPublikasi::findOrFail($id);
        $opsiTambahan = OpsiTambahanPesananPublikasi::where('pesananId', $id)->get();

        return view('pesanan.publikasi.detail_pesanan', compact('page', 'model', 'opsiTambahan'));
    }
}
