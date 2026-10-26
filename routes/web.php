<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LampiranController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [PengajuanController::class, 'dashboard'])->name('dashboard');

    // Pemohon: isi, ubah, lihat, dan cetak pengajuan sendiri
    Route::middleware('role:pemohon')->group(function () {
        Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/baru', [PengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
        Route::get('/pengajuan/{pengajuan}/ubah', [PengajuanController::class, 'edit'])->name('pengajuan.edit');
        Route::put('/pengajuan/{pengajuan}', [PengajuanController::class, 'update'])->name('pengajuan.update');
        Route::get('/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])->name('pengajuan.show');
        Route::get('/pengajuan/{pengajuan}/cetak', [PengajuanController::class, 'cetak'])->name('pengajuan.cetak');
        Route::get('/lampiran/{lampiran}', [LampiranController::class, 'unduh'])->name('lampiran.unduh');
    });

    // Admin: hanya mengelola pengguna
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->except(['show', 'destroy']);
    });
});
