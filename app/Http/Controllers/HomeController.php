<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use App\Models\PesananGedung;
use App\Models\PesananPublikasi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{
    public function index() {
        return view('home.index');
    }

    public function pesananSaya() {
        $models = PesananGedung::pesananSaya();
        // $pesanan_saya = PesananGedung::all()->merge(PesananPublikasi::all());

        return view('home.pesanan_saya', compact('models'));
    }

    public function detail_pesanan($id) {
        $model = PesananGedung::findOrFail($id);

        return view('home.detail_pesanan', compact('model'));
    }

    public function pemesanan_gedung() {
        return view('home.pemesanan_gedung');
    }

    public function pilihGedung(Request $request, $id) {
        $page = '';
        $model = Gedung::pilih($id);

        if ($request->ajax()) {
            return DataTables::of($model)
                ->addColumn('_', function($model) {
                    $html = '';
                    $html .= '<button href="" class="btn btn-outline-primary px-2 me-1 d-inline-flex align-items-center" onclick="view(\'' . $model->id . '\')"><i class="iconoir-eye fs-14 me-1"></i>Detail</button>';
                    $html .= '<button href="" class="btn btn-outline-success px-2 me-1 d-inline-flex align-items-center" onclick="pilih(\'' . $model->id . '\')"><i class="iconoir-check fs-14 me-1"></i>Pilih</button>';
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

    public function pemesanan_publikasi() {
        return view('home.pemesanan_publikasi');
    }
}
