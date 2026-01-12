<?php

use App\Http\Controllers\PeminjamController;
use Illuminate\Support\Facades\Route;

// Routes untuk Peminjam (Public - Tanpa Login)
Route::get('/', [PeminjamController::class, 'index'])->name('peminjam.dashboard');

// Alat
Route::get('/alat', [PeminjamController::class, 'alat'])->name('peminjam.alat');
Route::post('/alat/pinjam', [PeminjamController::class, 'pinjamAlat'])->name('peminjam.alat.pinjam');

// Pengembalian Alat
Route::get('/pengembalian-alat', [PeminjamController::class, 'pengembalianAlat'])->name('peminjam.pengembalian-alat');
Route::post('/pengembalian-alat/{id}', [PeminjamController::class, 'kembalikanAlat'])->name('peminjam.pengembalian-alat.kembali');
Route::post('/pengembalian-alat-multiple', [PeminjamController::class, 'kembalikanMultipleAlat'])->name('peminjam.pengembalian-alat.kembali.multiple');

// Material
Route::get('/material', [PeminjamController::class, 'material'])->name('peminjam.material');
Route::post('/material/ambil', [PeminjamController::class, 'ambilMaterial'])->name('peminjam.material.ambil');

// Pengembalian Material
Route::get('/pengembalian-material', [PeminjamController::class, 'pengembalianMaterial'])->name('peminjam.pengembalian-material');
Route::post('/pengembalian-material/{id}', [PeminjamController::class, 'kembalikanMaterial'])->name('peminjam.pengembalian-material.kembali');
Route::post('/pengembalian-material-multiple', [PeminjamController::class, 'kembalikanMultipleMaterial'])->name('peminjam.pengembalian-material.kembali.multiple');

// Riwayat
Route::get('/riwayat', [PeminjamController::class, 'riwayat'])->name('peminjam.riwayat');

// Riwayat Aktivitas (All Teams)
Route::get('/riwayat-aktivitas', [PeminjamController::class, 'riwayatAktivitas'])->name('peminjam.riwayat-aktivitas');