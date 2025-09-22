<?php

use App\Http\Controllers\ArtController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PesananGedungController;
use App\Http\Controllers\PesananPublikasiController;
use App\Http\Controllers\RaffleController;
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

Route::get('/login', function () {
    return redirect()->route('auth.index');
})->name('login');


Route::controller(AuthController::class)->prefix('auth')->name('auth.')->group(function () {
    Route::get('/index', 'index')->name('index');
    Route::get('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
});


Route::middleware(['auth'])->group(function () {
    Route::prefix('art-gallery')->name('art-gallery.')->group(function ()  {
        Route::controller(ArtController::class)->prefix('arts')->name('arts.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/view/{id?}', 'view')->name('view');
            Route::get('/confirm-dialog/{id?}', 'confirmDialog')->name('confirm-dialog');
            Route::post('/confirm/{id?}', 'confirm')->name('confirm');
            Route::delete('/delete/{id?}', 'delete')->name('delete');
        });
        Route::controller(ArtistController::class)->prefix('artist')->name('artist.')->group(function () {
            Route::get('/', 'index')->name('index');
        });
    });

    Route::controller(DashboardController::class)->prefix('dashboard')->middleware('can:Dashboard')->name('dashboard.')->group(function () {
       Route::get('/', 'index')->name('index');
    });

    Route::prefix('pesanan')->name('pesanan.')->middleware('can:Pesanan')->group(function () {
        Route::controller(PesananGedungController::class)->prefix('gedung')->middleware('can:Pesanan Gedung-Pesanan')->name('gedung.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/view/{id?}', 'view')->name('view');
            Route::post('/input-gedung/{id?}', 'inputGedung')->name('inputGedung');
            Route::post('/confirm/{id?}', 'confirm')->name('confirm');
            Route::post('/confirm-payment/{id?}', 'confirmPayment')->name('confirm-payment');
            Route::get('/add-optional/{id?}', 'tambahOpsional')->name('add-optional');
            Route::post('/store-optional/{id?}', 'storeOpsional')->name('store-optional');
            Route::get('/tambah-dokumen/{id?}', 'tambahDokumen')->name('tambah-dokumen');
            Route::post('/store-dokumen/{id?}', "storeDokumen")->name('store-dokumen');
        });

        Route::controller(PesananPublikasiController::class)->prefix('publikasi')->middleware('can:Pesanan Publikasi Acara-Pesanan')->name('publikasi.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/view/{id?}', 'view')->name('view');
            Route::post('/confirm/{id?}', 'confirm')->name('confirm');
            Route::post('/confirm-payment/{id?}', 'confirmPayment')->name('confirm-payment');
            Route::get('/add-order-cost/{id?}', 'tambahHargaPesanan')->name('add-order-cost');
            Route::post('/store-order-cost/{id?}', 'storeHargaPesanan')->name('store-order-cost');
            Route::get('/tambah-dokumen/{id?}', 'tambahDokumen')->name('tambah-dokumen');
            Route::post('/store-dokumen/{id?}', "storeDokumen")->name('store-dokumen');
        });
    });

    Route::controller(GedungController::class)->prefix('gedung')->middleware('can:Gedung')->name('gedung.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id?}', 'edit')->name('edit');
        Route::post('/update/{id?}', 'update')->name('update');
        Route::get('/delete/{id?}', 'delete')->name('delete');
    });

    Route::controller(GedungController::class)->prefix('gedung')->middleware('can:Gedung Read')->name('gedung.')->group(function () {
        Route::get('/view/{id?}', 'view')->name('view');
        Route::get('/view-360/{id?}', 'view_360')->name('view-360');
    });

    Route::prefix('configuration')->name('configuration.')->middleware('can:Configuration')->group(function () {
        Route::controller(UserController::class)->prefix('user')->middleware('can:User-Configuration')->name('user.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id?}', 'edit')->name('edit');
            Route::post('/update/{id?}', 'update')->name('update');
            Route::get('/delete/{id?}', 'delete')->name('delete');
            Route::get('/view/{id?}', 'view')->name('view');
        });

        Route::controller(RoleController::class)->prefix('role')->middleware('can:Role-Configuration')->name('role.')->group(function () {
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

    Route::controller(RaffleController::class)->prefix('raffle')->name('raffle.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id?}', 'edit')->name('edit');
        Route::post('/update/{id?}', 'update')->name('update');
        Route::get('/delete/{id?}', 'delete')->name('delete');
        Route::get('/view/{id?}', 'view')->name('view');
        Route::get('/random-picker', 'randomPicker')->name('random-picker');
    });
});

