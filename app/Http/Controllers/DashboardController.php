<?php

namespace App\Http\Controllers;

use App\Models\PesananGedung;
use App\Models\PesananPublikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() {
        $page = 'Dashboard';

        $sumPesananGedung = PesananGedung::count();
        $unconfirmedPesananGedung = PesananGedung::where('status', 0)->count();
        $sumPesananPublikasi = PesananPublikasi::count();
        $unconfirmedPesananPublikasi = PesananPublikasi::where('status', 0)->count();

        $months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $monthsEN = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        $pesananGedung = PesananGedung::selectRaw('MONTHNAME(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month');

        $pesananPublikasi = PesananPublikasi::selectRaw('MONTHNAME(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month');

        $gedungData = [];
        $publikasiData = [];
        foreach ($monthsEN as $month) {
            $gedungData[] = $pesananGedung->get($month, 0);
            $publikasiData[] = $pesananPublikasi->get($month, 0);
        }

        $series = [
            [
                'name' => 'Gedung',
                'data' => $gedungData
            ],
            [
                'name' => 'Publikasi',
                'data' => $publikasiData
            ]
        ];

        $dataGedung = PesananGedung::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $dataPublikasi = PesananPublikasi::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $data = collect($dataGedung)
            ->concat($dataPublikasi)
            ->groupBy('status')
            ->map(function ($group) {
                return $group->sum('count');
            })
            ->toArray();

        $chartData = [
            isset($data[0]) ? $data[0] : 0,
            isset($data[1]) ? $data[1] : 0,
            isset($data[2]) ? $data[2] : 0,
            isset($data[3]) ? $data[3] : 0,
            isset($data[4]) ? $data[4] : 0,
        ];

        return view('dashboard.index', compact('page', 'sumPesananGedung', 'unconfirmedPesananGedung', 'sumPesananPublikasi','unconfirmedPesananPublikasi', 'months', 'series', 'chartData'));
    }
}
