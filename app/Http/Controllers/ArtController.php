<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class ArtController extends Controller
{
    public function index(Request $request) {
        $page = 'Art Gallery \ Art';
        $response = Http::get('https://api.phaserbeary.xyz/api/arts/all');
        $model = json_decode($response->body());

        if ($request->ajax()) {
            return DataTables::of($model)
                ->addColumn('_', function($model) {
                    $html = '';
                    $html .= '<button href="" class="btn btn-outline-primary px-2 me-1 d-inline-flex align-items-center" onclick="view(\'' . $model->id . '\')"><i class="iconoir-eye fs-14"></i></button>';
                    $html .= '<button href="" class="btn btn-outline-info px-2 me-1 d-inline-flex align-items-center" onclick="confirm(\'' . $model->id . '\')"><i class="iconoir-clipboard-check fs-14"></i></button>';
                    $html .= '<button href="" class="btn btn-outline-danger px-2 d-inline-flex align-items-center" onclick="remove(\'' . $model->id . '\')"><i class="iconoir-trash fs-14"></i></button>';
                    return $html;
                })
                ->editColumn('description', function($model) {
                    $html = substr($model->description, 0, 20);
                    return $html;
                })
                ->editColumn('status', function($model) {
                    if ($model->status == 0) {
                        return '<span class="badge rounded-pill bg-warning p-1">Need to Review</span>';
                    } elseif ($model->status == 1) {
                        return '<span class="badge rounded-pill bg-primary p-1">Publised</span>';
                    }
                    return '<span class="badge rounded-pill bg-danger p-1">Rejected</span>';
                })
                ->rawColumns(['_', 'description', 'status'])
                ->make(true);
        }

        return view('art_gallery.art.index', compact('page'));
    }

    public function view($id) {
        $page = 'Art Gallery \ Art \ Detail';
        $response = Http::get('https://api.phaserbeary.xyz/api/arts/single/' . $id);
        $data = json_decode($response->body());
        $model = collect($data->art);

        return view('art_gallery.art.view', compact('page', 'model'));
    }

    public function confirmDialog($id) {
        $response = Http::get('https://api.phaserbeary.xyz/api/arts/single/' . $id);
        $data = json_decode($response->body());
        $model = collect($data->art);

        return view('art_gallery.art.confirm', compact('model'));
    }

    public function confirm(Request $request, $id) {
        $request->validate([
            'status' => 'required|numeric',
            'comment' => 'string|nullable|max:255',
        ]);

        $url = 'https://api.phaserbeary.xyz/api/arts/confirm/' . $id;

        $response = Http::post($url, [
            'comment' => $request->comment,
            'status' => $request->status,
        ]);

        if ($response->successful()) {
            return redirect()->route('art-gallery.arts.index')
                            ->with('success', 'Art updated successfully.');
        } else {
            return back()->withInput()
                        ->with('error', 'Failed to udpate: ' . $response->body());
        }
    }

    public function delete($id) {
        $model = Gedung::where('id', $id)->firstOrFail();

        if ($model->pesanan()->exists()) {
            session()->flash('error', 'Gedung tidak dapat dihapus karena tedapat pesanan yang terkait.');
            return response()->json([
                'success' => false,
                'message' => 'Gedung tidak dapat dihapus karena tedapat pesanan yang terkait.'
            ]);
        }

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
    }
}
