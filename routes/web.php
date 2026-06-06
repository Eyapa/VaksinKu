<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KeluargaController;
use App\Http\Controllers\FaskesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('riwayat', RiwayatController::class)->only(['index', 'store', 'destroy']);
    Route::resource('jadwal', JadwalController::class)->only(['index', 'store', 'destroy']);
    Route::resource('keluarga', KeluargaController::class)->except(['show']);
    Route::get('/cari', [FaskesController::class, 'index'])->name('cari');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
