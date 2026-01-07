<?php

use App\Http\Controllers\TeknisiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/teknisi');
});

// Routes untuk Teknisi (Public - Tanpa Login)
Route::prefix('teknisi')->name('teknisi.')->group(function () {
    Route::get('/', [TeknisiController::class, 'index'])->name('dashboard');
    
    // Alat
    Route::get('/alat', [TeknisiController::class, 'alat'])->name('alat');
    Route::post('/alat/pinjam', [TeknisiController::class, 'pinjamAlat'])->name('alat.pinjam');
    
    // Pengembalian
    Route::get('/pengembalian', [TeknisiController::class, 'pengembalian'])->name('pengembalian');
    Route::post('/pengembalian/{id}', [TeknisiController::class, 'kembalikanAlat'])->name('pengembalian.kembali');
    Route::post('/pengembalian-multiple', [TeknisiController::class, 'kembalikanMultipleAlat'])->name('pengembalian.kembali.multiple');
    
    // Material
    Route::get('/material', [TeknisiController::class, 'material'])->name('material');
    Route::post('/material/ambil', [TeknisiController::class, 'ambilMaterial'])->name('material.ambil');
    
    // Riwayat
    Route::get('/riwayat', [TeknisiController::class, 'riwayat'])->name('riwayat');
});