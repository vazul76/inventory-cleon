<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Material;
use App\Models\PeminjamanAlat;
use App\Models\PengambilanMaterial;
use App\Models\StockSnapshot;
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
        
        // Total sisa alat kemarin (dari snapshot)
        $snapshotKemarin = StockSnapshot::whereDate('tanggal', $yesterday)->first();
        $alatKemarin = $snapshotKemarin ? $snapshotKemarin->total_alat_available : Alat::sum('available');

        // Total sisa alat hari ini (real-time)
        $alatHariIni = Alat::sum('available');

        // Detail alat hari ini (yang bertambah/baru dipinjam)
        $alatBaru = PeminjamanAlat::with('alat')
            ->whereDate('tanggal_pinjam', $today)
            ->get();

        // Alat yang dikembalikan hari ini
        $alatDikembalikan = PeminjamanAlat::with('alat')
            ->whereDate('tanggal_kembali', $today)
            ->where('status', 'dikembalikan')
            ->get();

        // Alat yang masih dipinjam (belum dikembalikan) - dari hari sebelumnya
        $alatBelumKembali = PeminjamanAlat::with('alat')
            ->where('status', 'dipinjam')
            ->whereDate('tanggal_pinjam', '<', $today)
            ->take(10)
            ->get();

        // Gabungkan semua aktivitas hari ini dan sort berdasarkan waktu terbaru
        $allAlatActivities = collect()
            ->merge($alatBaru->map(function($item) {
                $item->activity_type = 'baru_dipinjam';
                $item->activity_time = $item->tanggal_pinjam;
                return $item;
            }))
            ->merge($alatDikembalikan->map(function($item) {
                $item->activity_type = 'dikembalikan';
                $item->activity_time = $item->updated_at;
                return $item;
            }))
            ->merge($alatBelumKembali->map(function($item) {
                $item->activity_type = 'belum_kembali';
                $item->activity_time = $item->tanggal_pinjam;
                return $item;
            }))
            ->sortByDesc('activity_time')
            ->values();

        // ========== MATERIAL STATS ==========
        
        // Total sisa material kemarin (dari snapshot)
        $materialKemarin = $snapshotKemarin ? $snapshotKemarin->total_material_stock : Material::sum('stock');

        // Total sisa material hari ini (real-time)
        $materialHariIni = Material::sum('stock');

        // Detail material yang baru ditambah (ke stock) hari ini
        $materialBaru = Material::with('category')
            ->whereDate('created_at', $today)
            ->get();

        // Detail material yang diambil hari ini
        $materialDiambil = PengambilanMaterial::with('material')
            ->whereDate('tanggal_ambil', $today)
            ->get();

        // Gabungkan semua aktivitas material hari ini dan sort berdasarkan waktu terbaru
        $allMaterialActivities = collect()
            ->merge($materialBaru->map(function($item) {
                $item->activity_type = 'baru_ditambah';
                $item->activity_time = $item->created_at;
                return $item;
            }))
            ->merge($materialDiambil->map(function($item) {
                $item->activity_type = 'diambil';
                $item->activity_time = $item->created_at;
                return $item;
            }))
            ->sortByDesc('activity_time')
            ->values();

        return view('teknisi.dashboard', compact(
            'alatKemarin',
            'alatHariIni',
            'allAlatActivities',
            'materialKemarin',
            'materialHariIni',
            'allMaterialActivities'
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
            ->orderBy('tanggal_pinjam', 'desc')
            ->orderBy('id', 'desc')
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

        return redirect()->route('teknisi.pengembalian')->with('success', 'Alat berhasil dikembalikan!');
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
        
        return redirect()->route('teknisi.pengembalian')->with('success', $message);
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