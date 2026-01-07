<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Material;
use App\Models\PeminjamanAlat;
use App\Models\PengambilanMaterial;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TeknisiController extends Controller
{
    // Halaman Dashboard Teknisi
    public function index()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // ========== ALAT STATS ==========
        
        // Jumlah alat dipinjam kemarin
        $alatKemarin = PeminjamanAlat::whereDate('tanggal_pinjam', $yesterday)
            ->where('status', 'dipinjam')
            ->count();

        // Jumlah alat dipinjam hari ini
        $alatHariIni = PeminjamanAlat::whereDate('tanggal_pinjam', $today)
            ->where('status', 'dipinjam')
            ->count();

        // Detail alat hari ini (yang bertambah/baru dipinjam)
        $alatBaru = PeminjamanAlat::with('alat')
            ->whereDate('tanggal_pinjam', $today)
            ->latest()
            ->get();

        // Alat yang dikembalikan hari ini
        $alatDikembalikan = PeminjamanAlat::with('alat')
            ->whereDate('tanggal_kembali', $today)
            ->where('status', 'dikembalikan')
            ->latest()
            ->get();

        // Alat yang masih dipinjam (belum dikembalikan)
        $alatBelumKembali = PeminjamanAlat::with('alat')
            ->where('status', 'dipinjam')
            ->latest()
            ->take(10)
            ->get();

        // ========== MATERIAL STATS ==========
        
        // Jumlah material diambil kemarin
        $materialKemarin = PengambilanMaterial::whereDate('tanggal_ambil', $yesterday)
            ->sum('jumlah');

        // Jumlah material diambil hari ini
        $materialHariIni = PengambilanMaterial::whereDate('tanggal_ambil', $today)
            ->sum('jumlah');

        // Detail material yang baru ditambah (ke stock) hari ini
        $materialBaru = Material::with('category')
            ->whereDate('created_at', $today)
            ->latest()
            ->get();

        // Detail material yang diambil hari ini
        $materialDiambil = PengambilanMaterial::with('material')
            ->whereDate('tanggal_ambil', $today)
            ->latest()
            ->get();

        return view('teknisi.dashboard', compact(
            'alatKemarin',
            'alatHariIni',
            'alatBaru',
            'alatDikembalikan',
            'alatBelumKembali',
            'materialKemarin',
            'materialHariIni',
            'materialBaru',
            'materialDiambil'
        ));
    }

    // ========== ALAT ==========
    
    // Halaman Lihat Alat
    public function alat()
    {
        $alats = Alat::with('category')
            ->where('available', '>', 0)
            ->get();
        
        return view('teknisi.alat', compact('alats'));
    }

    // Proses Pinjam Alat
    public function pinjamAlat(Request $request)
    {
        $request->validate([
            'alat_id' => 'required|exists:alats,id',
            'jumlah' => 'required|integer|min:1',
            'nama_peminjam' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $alat = Alat::findOrFail($request->alat_id);

        if (!$alat->isAvailable($request->jumlah)) {
            return back()->with('error', 'Alat tidak cukup! Tersedia: ' . $alat->available);
        }

        PeminjamanAlat::create([
            'alat_id' => $alat->id,
            'jumlah' => $request->jumlah,
            'nama_peminjam' => $request->nama_peminjam,
            'tanggal_pinjam' => now(),
            'status' => 'dipinjam',
            'keterangan' => $request->keterangan,
        ]);

        $alat->kurangiAvailable($request->jumlah);

        return back()->with('success', 'Alat berhasil dipinjam!');
    }

    // Halaman Kembalikan Alat
    public function pengembalian()
    {
        $peminjaman = PeminjamanAlat::with('alat')
            ->where('status', 'dipinjam')
            ->latest()
            ->get();

        return view('teknisi.pengembalian', compact('peminjaman'));
    }

    // Proses Kembalikan Alat
    public function kembalikanAlat($id)
    {
        $peminjaman = PeminjamanAlat::findOrFail($id);

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Alat sudah dikembalikan!');
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
        ]);

        $peminjaman->alat->tambahAvailable($peminjaman->jumlah);

        return back()->with('success', 'Alat berhasil dikembalikan!');
    }

    // ========== MATERIAL ==========
    
    // Halaman Lihat Material
    public function material()
    {
        $material = Material::with('category')
            ->where('stock', '>', 0)
            ->get();

        return view('teknisi.material', compact('material'));
    }

    // Proses Ambil Material
    public function ambilMaterial(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:materials,id',
            'nama_pengambil' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'keperluan' => 'nullable|string',
            'lokasi_pemasangan' => 'nullable|string',
        ]);

        $material = Material::findOrFail($request->material_id);

        if ($material->stock < $request->jumlah) {
            return back()->with('error', 'Stock tidak cukup! Tersedia: ' . $material->stock);
        }

        PengambilanMaterial::create([
            'material_id' => $material->id,
            'nama_pengambil' => $request->nama_pengambil,
            'jumlah' => $request->jumlah,
            'tanggal_ambil' => now(),
            'keperluan' => $request->keperluan,
            'lokasi_pemasangan' => $request->lokasi_pemasangan,
        ]);

        $material->kurangiStock($request->jumlah);

        return back()->with('success', 'Material berhasil diambil!');
    }

    // Halaman Riwayat Saya
    public function riwayat(Request $request)
    {
        $nama = $request->input('nama');

        $riwayatAlat = [];
        $riwayatMaterial = [];

        if ($nama) {
            $riwayatAlat = PeminjamanAlat::with('alat')
                ->where('nama_peminjam', 'LIKE', '%' . $nama . '%')
                ->latest()
                ->get();

            $riwayatMaterial = PengambilanMaterial::with('material')
                ->where('nama_pengambil', 'LIKE', '%' . $nama . '%')
                ->latest()
                ->get();
        }

        return view('teknisi.riwayat', compact('riwayatAlat', 'riwayatMaterial', 'nama'));
    }
}