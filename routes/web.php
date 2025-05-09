<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PesananGedungController;
use App\Http\Controllers\PesananPublikasiController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect('/auth/index');
    }

    $user = Auth::user();

    if ($user->hasRole('Customer')) {
        return redirect('/home');
    }

    return redirect('/dashboard');
});

Route::controller(HomeController::class)->name('home.')->group( function () {
    Route::get('/home', 'index')->name('index');
    Route::get('/pesanan-saya', 'pesananSaya')->name('pesanan-saya');
    Route::get('/detail-pesanan-gedung/{id?}', 'detailPesananGedung')->name('detail-pesanan-gedung');
    Route::get('/detail-pesanan-publikasi/{id?}', 'detailPesananPublikasi')->name('detail-pesanan-publikasi');
    Route::get('/tambah-dokumen/{id?}', 'tambahDokumen')->name('tambah-dokumen');
    Route::post('/store-dokumen/{id?}', 'storeDokumen')->name('store-dokumen');
    Route::get('/pilih-gedung/{id?}', 'pilihGedung')->name('pilih-gedung');
    Route::get('/pilih/{id?}/{id2?}', 'pilih')->name('pilih');
    Route::get('/pemesanan-gedung', 'pemesanan_gedung')->name('pemesanan-gedung');
    Route::get('/pemesanan-publikasi', 'pemesanan_publikasi')->name('pemesanan-publikasi');
});

Route::controller(AuthController::class)->prefix('auth')->name('auth.')->group(function () {
    Route::get('/index', 'index')->name('index');
    Route::get('/login', 'login')->name('login');
    Route::post('logout', 'logout')->name('logout');
});

Route::controller(DashboardController::class)->prefix('dashboard')->name('dashboard.')->group(function () {
   Route::get('/', 'index')->name('index');
});

Route::prefix('pesanan')->name('pesanan.')->group(function () {
    Route::controller(PesananGedungController::class)->prefix('gedung')->name('gedung.')->group(function () {
        Route::post('/store', 'store')->name('store');
        Route::post('/input-gedung/{id?}', 'inputGedung')->name('inputGedung');
        Route::post('/confirm/{id?}', 'confirm')->name('confirm');
        Route::get('/confirm-payment/{id?}', 'confirmPayment')->name('confirm-payment');
        Route::get('/add-optional/{id?}', 'tambahOptional')->name('add-optional');
        Route::post('/store-optional/{id?}', 'storeOptional')->name('store-optional');
        Route::get('/view/{id?}', 'view')->name('view');
        Route::get('/download-excel/{id?}', 'downloadExcel')->name('download-excel');
        Route::get('/download-invoice/{id?}', 'downloadInvoice')->name('download-invoice');
    });

    Route::controller(PesananPublikasiController::class)->prefix('publikasi')->name('publikasi.')->group(function () {
        Route::post('/store', 'store')->name('store');
        Route::post('/confirm/{id?}', 'confirm')->name('confirm');
        Route::get('/confirm-payment/{id?}', 'confirmPayment')->name('confirm-payment');
        Route::get('/add-order-cost/{id?}', 'tambahBiayaPesanan')->name('add-order-cost');
        Route::post('/store-order-cost/{id?}', 'storeBiayaPesanan')->name('store-order-cost');
        Route::get('/view/{id?}', 'view')->name('view');
        Route::get('/download-excel/{id?}', 'downloadExcel')->name('download-excel');
        Route::get('/download-invoice/{id?}', 'downloadInvoice')->name('download-invoice');
    });
});

Route::prefix('pesanan')->name('pesanan.')->group(function () {
    Route::controller(PesananGedungController::class)->prefix('gedung')->name('gedung.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/detail', 'detail')->name('detail');
    });

    Route::controller(PesananPublikasiController::class)->prefix('publikasi')->name('publikasi.')->group(function () {
        Route::get('/', 'index')->name('index');
    });
});


Route::controller(GedungController::class)->prefix('gedung')->name('gedung.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/edit/{id?}', 'edit')->name('edit');
    Route::post('/update/{id?}', 'update')->name('update');
    Route::get('/delete/{id?}', 'delete')->name('delete');
    Route::get('/view/{id?}', 'view')->name('view');
    Route::get('/view-360/{id?}', 'view_360')->name('view-360');
});

Route::prefix('configuration')->name('configuration.')->group(function () {
    Route::controller(UserController::class)->prefix('user')->name('user.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id?}', 'edit')->name('edit');
        Route::post('/update/{id?}', 'update')->name('update');
        Route::get('/delete/{id?}', 'delete')->name('delete');
        Route::get('/view/{id?}', 'view')->name('view');
    });

    Route::controller(RoleController::class)->prefix('role')->name('role.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id?}', 'edit')->name('edit');
        Route::post('/update/{id?}', 'update')->name('update');
        Route::get('/delete/{id?}', 'delete')->name('delete');
        Route::get('/view/{id?}', 'view')->name('view');
        Route::get('/setting/{id?}', 'setting')->name('setting');
        Route::post('/update-permission/{id?}', 'updatePermission')->name('update-permission');
    });
});
