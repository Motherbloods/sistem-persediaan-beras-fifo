<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JenisBerasController;
use App\Http\Controllers\SupplierController;
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
    Route::get('/dashboard', function () {
        return 'Dashboard';
    })->name('dashboard');

    Route::get('/monitoring', function () {
        return 'Monitoring';
    })->name('monitoring');
});
Route::middleware('role:admin')->group(function () {

    // Master data
    Route::resource('jenis-beras', JenisBerasController::class)
        ->parameters([
            'jenis-beras' => 'jenisBeras'
        ]);
    Route::resource('supplier', SupplierController::class);
});