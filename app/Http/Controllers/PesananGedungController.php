<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PesananGedungController extends Controller
{
    public function index() {
        $page = 'Pesanan \ Gedung';

        return view('pesanan.gedung.index', compact('page'));
    }
}
