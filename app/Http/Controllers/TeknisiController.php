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
            ->orderBy('name', 'asc')
            ->get();
        
        return view('teknisi.alat', compact('alats'));
    }

    // Proses Pinjam Alat
    public function pinjamAlat(Request $request)
    {
        $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'alats' => 'required|json',
        ]);

        $alats = json_decode($request->alats, true);
        
        if (empty($alats)) {
            return back()->with('error', 'Tidak ada alat yang dipilih!');
        }

        foreach ($alats as $alatData) {
            $alat = Alat::findOrFail($alatData['id']);
            $jumlah = $alatData['jumlah'];

            if (!$alat->isAvailable($jumlah)) {
                return back()->with('error', "Alat {$alat->name} tidak cukup! Tersedia: {$alat->available}");
            }

            PeminjamanAlat::create([
                'alat_id' => $alat->id,
                'jumlah' => $jumlah,
                'nama_peminjam' => $request->nama_peminjam,
                'tanggal_pinjam' => now(),
                'status' => 'dipinjam',
                'keterangan' => $request->keterangan,
            ]);

            $alat->kurangiAvailable($jumlah);
        }

        $count = count($alats);
        $message = $count === 1 ? 'Alat berhasil dipinjam!' : "{$count} alat berhasil dipinjam!";
        
        return back()->with('success', $message);
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

    // Proses Kembalikan Multiple Alat
    public function kembalikanMultipleAlat(Request $request)
    {
        $request->validate([
            'peminjaman_ids' => 'required|json',
        ]);

        $ids = json_decode($request->peminjaman_ids, true);
        
        if (empty($ids)) {
            return back()->with('error', 'Tidak ada alat yang dipilih!');
        }

        foreach ($ids as $id) {
            $peminjaman = PeminjamanAlat::findOrFail($id);
            
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => now(),
            ]);
            
            $peminjaman->alat->tambahAvailable($peminjaman->jumlah);
        }

        $count = count($ids);
        $message = $count === 1 ? 'Alat berhasil dikembalikan!' : "{$count} alat berhasil dikembalikan!";
        
        return back()->with('success', $message);
    }

    // ========== MATERIAL ==========
    
    // Halaman Lihat Material
    public function material()
    {
        $material = Material::with('category')
            ->orderBy('name', 'asc')
            ->get();

        return view('teknisi.material', compact('material'));
    }

    // Proses Ambil Material
    public function ambilMaterial(Request $request)
    {
        $request->validate([
            'nama_pengambil' => 'required|string|max:255',
            'keperluan' => 'nullable|string',
            'lokasi_pemasangan' => 'nullable|string',
            'materials' => 'required|json',
        ]);

        $materials = json_decode($request->materials, true);
        
        if (empty($materials)) {
            return back()->with('error', 'Tidak ada material yang dipilih!');
        }

        foreach ($materials as $materialData) {
            $material = Material::findOrFail($materialData['id']);
            $jumlah = $materialData['jumlah'];

            if ($material->stock < $jumlah) {
                return back()->with('error', "Material {$material->name} tidak cukup! Stock: {$material->stock}");
            }

            PengambilanMaterial::create([
                'material_id' => $material->id,
                'jumlah' => $jumlah,
                'nama_pengambil' => $request->nama_pengambil,
                'tanggal_ambil' => now(),
                'keperluan' => $request->keperluan,
                'lokasi_pemasangan' => $request->lokasi_pemasangan,
            ]);

            $material->kurangiStock($jumlah);
        }

        $count = count($materials);
        $message = $count === 1 ? 'Material berhasil diambil!' : "{$count} material berhasil diambil!";
        
        return back()->with('success', $message);
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