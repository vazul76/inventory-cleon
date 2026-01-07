<?php

use App\Http\Controllers\TeknisiController;
use Illuminate\Support\Facades\Route;

// Routes untuk Teknisi (Public - Tanpa Login)
Route::get('/', [TeknisiController::class, 'index'])->name('teknisi.dashboard');

// Alat
Route::get('/alat', [TeknisiController::class, 'alat'])->name('teknisi.alat');
Route::post('/alat/pinjam', [TeknisiController::class, 'pinjamAlat'])->name('teknisi.alat.pinjam');

// Pengembalian
Route::get('/pengembalian', [TeknisiController::class, 'pengembalian'])->name('teknisi.pengembalian');
Route::post('/pengembalian/{id}', [TeknisiController::class, 'kembalikanAlat'])->name('teknisi.pengembalian.kembali');
Route::post('/pengembalian-multiple', [TeknisiController::class, 'kembalikanMultipleAlat'])->name('teknisi.pengembalian.kembali.multiple');

// Material
Route::get('/material', [TeknisiController::class, 'material'])->name('teknisi.material');
Route::post('/material/ambil', [TeknisiController::class, 'ambilMaterial'])->name('teknisi.material.ambil');

// Riwayat
Route::get('/riwayat', [TeknisiController::class, 'riwayat'])->name('teknisi.riwayat');