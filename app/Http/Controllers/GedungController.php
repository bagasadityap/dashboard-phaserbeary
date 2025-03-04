<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class GedungController extends Controller
{
    public function index(Request $request) {
        $page = 'Gedung';
        $model = Gedung::query();

        if ($request->ajax()) {
            return DataTables::of($model)
                ->addColumn('_', function($model) {
                    $html = '';
                    if (auth()->user()->can('Gedung Read')) {
                        $html .= '<button href="" class="btn btn-outline-primary px-2 me-1 d-inline-flex align-items-center" onclick="view(\'' . $model->id . '\')"><i class="iconoir-eye fs-14"></i></button>';
                    }
                    if (auth()->user()->can('Gedung Edit')) {
                        $html .= '<button href="" class="btn btn-outline-warning px-2 me-1 d-inline-flex align-items-center" onclick="edit(\'' . $model->id . '\')"><i class="iconoir-edit fs-14"></i></button>';
                    }
                    if (auth()->user()->can('Gedung Delete')) {
                        $html .= '<button href="" class="btn btn-outline-danger px-2 d-inline-flex align-items-center" onclick="remove(\'' . $model->id . '\')"><i class="iconoir-trash fs-14"></i></button>';
                    }
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

        return view('gedung.index', compact('page'));
    }


    public function view($id) {
        $page = 'Gedung \ Detail';
        $model = Gedung::findOrFail($id);

        return view('gedung.view', compact('page', 'model'));
    }
    
    public function view_360($id) {
        $model = Gedung::findOrFail($id);

        return view('gedung.view_360', compact('model'));
    }

    public function create() {
        return view('gedung.create');
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'lokasi' => 'required|string|max:255',
                'kapasitas' => 'required|integer',
                'harga' => 'required|integer',
                'gambar' => 'required|file',
                'gambar_vr' => 'required|file',
            ]);

            $gambarPath = $request->file('gambar')->store('gedung', 'public');
            $gambarVrPath = $request->file('gambar_vr')->store('gedung', 'public');

            $model = Gedung::create([
                'nama' => $request->nama,
                'lokasi' => $request->lokasi,
                'kapasitas' => $request->kapasitas,
                'harga' => $request->harga,
                'gambar' => $gambarPath,
                'gambar_vr' => $gambarVrPath,
            ]);

            $model->save();

            return redirect()->back()->with('success', 'Data berhasil ditambahkan');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan.');
        }
    }

    public function edit($id) {
        $model = Gedung::where('id', $id)->firstOrFail();

        return view('gedung.update', compact('model'));
    }

    public function update(Request $request, $id) {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'lokasi' => 'required|string|max:255',
                'kapasitas' => 'required|integer',
                'harga' => 'required|integer',
                'gambar' => 'nullable|file',
                'gambar_vr' => 'nullable|file',
            ]);

            $model = Gedung::where('id', $id)->firstOrFail();
            $model->nama = $request->nama;
            $model->lokasi = $request->lokasi;
            $model->kapasitas = $request->kapasitas;
            $model->harga = $request->harga;
            if ($request->hasFile('gambar')) {
                Storage::disk('public')->delete($model->gambar);
                $gambarPath = $request->file('gambar')->store('gedung', 'public');
                $model->update(['gambar' => $gambarPath]);
            }
            if ($request->hasFile('gambar_vr')) {
                Storage::disk('public')->delete($model->gambar_vr);
                $gambarPath = $request->file('gambar_vr')->store('gedung', 'public');
                $model->update(['gambar_vr' => $gambarPath]);
            }
            $model->save();

            return redirect()->back()->with('success', 'Data berhasil diperbarui');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan.');
        }
    }

    public function delete($id) {
        try {
            $model = Gedung::where('id', $id)->firstOrFail();

            if ($model->gambar && Storage::disk('public')->exists($model->gambar)) {
                Storage::disk('public')->delete($model->gambar);
            }
            if ($model->gambar_vr && Storage::disk('public')->exists($model->gambar_vr)) {
                Storage::disk('public')->delete($model->gambar_vr);
            }

            $model->delete();
            session()->flash('success', 'Data berhasil dihapus.');
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
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
                'message' => 'Terjadi kesalahan saat menghapus data.'
            ]);
        }
    }
}
