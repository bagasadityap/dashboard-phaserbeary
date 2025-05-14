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
        $model = Gedung::findOrFail($id)->gambarVR;

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
                'kapasitas' => 'required|numeric',
                'harga' => 'required|numeric',
                'deskripsi' => 'required|string',
                'gambar' => 'required',
                'gambar.*' => 'file|mimes:jpg,jpeg,png|max:2048',
                'gambarVR' => 'required|file|mimes:jpg,jpeg,png',
            ]);

            $gambarPaths = [];
            foreach ($request->file('gambar') as $file) {
                $gambarPaths[] = $file->store('gedung', 'public');
            }
            $gambarVrPath = $request->file('gambarVR')->store('gedung', 'public');

            $model = Gedung::create([
                'nama' => $request->nama,
                'lokasi' => $request->lokasi,
                'kapasitas' => $request->kapasitas,
                'harga' => $request->harga,
                'deskripsi' => $request->deskripsi,
                'gambar' => json_encode($gambarPaths),
                'gambarVR' => $gambarVrPath,
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
                'kapasitas' => 'required|numeric',
                'harga' => 'required|numeric',
                'deskripsi' => 'required|string',
                'gambar' => 'nullable',
                'gambar.*' => 'file|mimes:jpg,jpeg,png|max:2048',
                'gambarVR' => 'nullable|file|mimes:jpg,jpeg,png',
            ]);

            $model = Gedung::findOrFail($id);

            $existingImages = $model->gambar ? json_decode($model->gambar, true) : [];

            if ($request->deleted_images) {
                $deletedImages = json_decode($request->deleted_images, true);
                foreach ($deletedImages as $image) {
                    Storage::disk('public')->delete($image);
                }
                $existingImages = array_values(array_diff($existingImages, $deletedImages));
            }

            if ($request->hasFile('gambar')) {
                foreach ($request->file('gambar') as $file) {
                    $existingImages[] = $file->store('gedung', 'public');
                }
            }

            $gambarVrPath = $model->gambarVR;
            if  ($request->hasFile('gambarVR')) {
                if ($gambarVrPath) {
                    Storage::disk('public')->delete($gambarVrPath);
                }
                $gambarVrPath = $request->file('gambarVR')->store('dokumen/gedung', 'public');
            }

            $model->nama = $request->nama;
            $model->lokasi = $request->lokasi;
            $model->kapasitas = $request->kapasitas;
            $model->harga = $request->harga;
            $model->deskripsi = $request->deskripsi;
            $model->gambar = json_encode($existingImages);
            $model->gambarVR = $gambarVrPath;
            $model->save();

            return redirect()->back()->with('success', 'Data berhasil diperbarui');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan.' . $e);
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
            if ($model->gambarVR && Storage::disk('public')->exists($model->gambarVR)) {
                Storage::disk('public')->delete($model->gambarVR);
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
