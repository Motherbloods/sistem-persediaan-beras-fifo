<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisBerasController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\StokKeluarController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring');

    Route::resource('stok-masuk', StokMasukController::class)
        ->only(['index', 'create', 'store', 'show'])
        ->names('stok-masuk');

    Route::resource('stok-keluar', StokKeluarController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->names('stok-keluar');

    Route::get('/profil', [UserController::class, 'profil'])->name('profil');
    Route::put('/profil', [UserController::class, 'updateProfil'])->name('profil.update');
    Route::middleware('role:admin')->group(function () {

        // Master data
        Route::resource('jenis-beras', JenisBerasController::class)
            ->parameters([
                'jenis-beras' => 'jenisBeras'
            ]);
        Route::resource('supplier', SupplierController::class);
        Route::resource('users', UserController::class)->except(['show']);

        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/masuk', [LaporanController::class, 'masuk'])->name('masuk');
            Route::get('/masuk/export', [LaporanController::class, 'exportMasuk'])->name('masuk.export');
            Route::get('/keluar', [LaporanController::class, 'keluar'])->name('keluar');
            Route::get('/keluar/export', [LaporanController::class, 'exportKeluar'])->name('keluar.export');
            Route::get('/persediaan', [LaporanController::class, 'persediaan'])->name('persediaan');
            Route::get('/persediaan/export', [LaporanController::class, 'exportPersediaan'])->name('persediaan.export');
        });
    });
});
