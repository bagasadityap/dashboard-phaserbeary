<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        return view('home.index');
    }

    public function pesanan_saya() {
        return view('home.pesanan_saya');
    }
    
    public function detail_pesanan() {
        return view('home.detail_pesanan');
    }
    
    public function pemesanan_gedung() {
        return view('home.pemesanan_gedung');
    }
    
    public function pemesanan_publikasi() {
        return view('home.pemesanan_publikasi');
    }
}
