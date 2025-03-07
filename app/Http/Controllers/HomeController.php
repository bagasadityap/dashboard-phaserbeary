<?php

namespace App\Http\Controllers;

use App\Models\PesananGedung;
use App\Models\PesananPublikasi;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        return view('home.index');
    }

    public function pesanan_saya() {
        $models = PesananGedung::pesanan_saya();
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

    public function pemesanan_publikasi() {
        return view('home.pemesanan_publikasi');
    }
}
