<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use App\Models\PesananGedung;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
                    // if (auth()->user()->can('Gedung Edit')) {
                    //     $html .= '<button href="" class="btn btn-outline-warning px-2 me-1 d-inline-flex align-items-center" onclick="confirm(\'' . $model->id . '\')"><i class="iconoir-edit fs-14"></i></button>';
                    // }
                    // if (auth()->user()->can('Gedung Delete')) {
                    //     $html .= '<button href="" class="btn btn-outline-danger px-2 d-inline-flex align-items-center" onclick="remove(\'' . $model->id . '\')"><i class="iconoir-trash fs-14"></i></button>';
                    // }
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

    public function detail() {
        $page = 'Pesanan \ Gedung \ Detail';

        return view('pesanan.gedung.detail_pesanan', compact('page'));
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'no_hp' => 'required|string|max:255',
                'surat_permohonan_acara' => 'required|file',
                'catatan' => 'nullable|string',
            ]);

            $dokumen = $request->file('surat_permohonan_acara');
            $filename = 'dokumen/gedung/' . time() . '_' . $dokumen->getClientOriginalName();
            $dokumenPath = $dokumen->storeAs('dokumen/gedung', $filename, 'public');

            $model = PesananGedung::create([
                'judul' => $request->judul,
                'tanggal' => $request->tanggal,
                'no_hp' => $request->no_hp,
                'surat_permohonan_acara' => $dokumenPath,
                'catatan' => $request->catatan,
                'user_id' => auth()->user()->id,
            ]);

            $model->save();

            return redirect()->route('home.pesanan-saya')->with('success', 'Data berhasil ditambahkan');
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
                $model->is_verified = 1;
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
        $model = PesananGedung::findOrFail($id);
        $model->is_paid = 1;
        $model->save();
    }

    public function view($id) {
        $page = "Pesanan \ Gedung \ Detail";
        $model = PesananGedung::findOrFail($id);
        $selectedModel = PesananGedung::findOrFail($id);
        $gedungs = Gedung::all();
        $selectedGedung = $selectedModel->gedungPesanan()->pluck('gedung_id')->toArray();

        return view('pesanan.gedung.detail_pesanan', compact('page', 'model', 'gedungs', 'selectedGedung'));
    }

    public function delete($id) {

    }
}
