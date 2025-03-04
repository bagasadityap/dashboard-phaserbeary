<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PesananPublikasiController extends Controller
{
    public function index() {
        $page = 'Pesanan \ Publikasi';

        return view('pesanan.gedung.index', compact('page'));
    }
}
