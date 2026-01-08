<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Material;
use App\Models\PeminjamanAlat;
use App\Models\PengambilanMaterial;
use App\Models\StockSnapshot;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamController extends Controller
{
    // Halaman Dashboard Peminjam
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

        // Detail alat hari ini (hanya yang dipinjam)
        $alatBaru = PeminjamanAlat::with('alat')
            ->whereDate('tanggal_pinjam', $today)
            ->get();

        // Hanya tampilkan aktivitas peminjaman
        $allAlatActivities = $alatBaru->map(function($item) {
                $item->activity_type = 'baru_dipinjam';
                $item->activity_time = $item->tanggal_pinjam;
                return $item;
            })
            ->sortByDesc('activity_time')
            ->values();

        // ========== MATERIAL STATS ==========
        
        // Total sisa material kemarin (dari snapshot)
        $materialKemarin = $snapshotKemarin ? $snapshotKemarin->total_material_stock : Material::sum('stock');

        // Total sisa material hari ini (real-time)
        $materialHariIni = Material::sum('stock');

        // Detail material yang diambil hari ini saja
        $materialDiambil = PengambilanMaterial::with('material')
            ->whereDate('tanggal_ambil', $today)
            ->get();

        $allMaterialActivities = $materialDiambil->map(function($item) {
                $item->activity_type = 'diambil';
                $item->activity_time = $item->created_at;
                return $item;
            })
            ->sortByDesc('activity_time')
            ->values();

        return view('peminjam.dashboard', compact(
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
    public function alat(Request $request)
    {
        $q = $request->input('q');

        $alats = Alat::with('category')
            ->when($q, function($query, $q) {
                $query->where('name', 'LIKE', "%{$q}%")
                      ->orWhere('description', 'LIKE', "%{$q}%");
            })
            ->orderBy('name', 'asc')
            ->get();
        
        return view('peminjam.alat', compact('alats', 'q'));
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
    public function pengembalianAlat(Request $request)
    {
        $q = $request->input('q');

        $peminjaman = PeminjamanAlat::with('alat')
            ->where('status', 'dipinjam')
            ->when($q, function($query, $q) {
                $query->where(function($sub) use ($q) {
                    $sub->whereHas('alat', function($alat) use ($q) {
                            $alat->where('name', 'LIKE', "%{$q}%");
                        })
                        ->orWhere('nama_peminjam', 'LIKE', "%{$q}%")
                        ->orWhere('keterangan', 'LIKE', "%{$q}%");
                });
            })
            ->orderBy('tanggal_pinjam', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('peminjam.pengembalian-alat', compact('peminjaman', 'q'));
    }

    // Proses Kembalikan Alat
    public function kembalikanAlat(Request $request, $id)
    {
        $peminjaman = PeminjamanAlat::findOrFail($id);

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Alat sudah dikembalikan!');
        }

        // Get jumlah kembali from request
        $jumlahData = json_decode($request->input('jumlah_data', '{}'), true);
        $jumlahKembali = isset($jumlahData[$id]) ? (int)$jumlahData[$id] : $peminjaman->jumlah;

        // Validasi: jumlah kembali tidak boleh melebihi yang dipinjam
        if ($jumlahKembali > $peminjaman->jumlah) {
            return back()->with('error', "Jumlah pengembalian ({$jumlahKembali} unit) melebihi jumlah yang dipinjam ({$peminjaman->jumlah} unit)!");
        }

        if ($jumlahKembali < 1) {
            return back()->with('error', 'Jumlah pengembalian minimal 1 unit!');
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
        ]);

        // Add back to available stock
        $peminjaman->alat->tambahAvailable($jumlahKembali);

        return redirect()->route('peminjam.pengembalian-alat')->with('success', 'Alat berhasil dikembalikan!');
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

        // Get jumlah data
        $jumlahData = json_decode($request->input('jumlah_data', '{}'), true);

        // Validasi dulu semua item
        foreach ($ids as $id) {
            $peminjaman = PeminjamanAlat::findOrFail($id);
            $jumlahKembali = isset($jumlahData[$id]) ? (int)$jumlahData[$id] : $peminjaman->jumlah;
            
            if ($jumlahKembali > $peminjaman->jumlah) {
                return back()->with('error', "Jumlah pengembalian {$peminjaman->alat->name} ({$jumlahKembali} unit) melebihi yang dipinjam ({$peminjaman->jumlah} unit)!");
            }
            
            if ($jumlahKembali < 1) {
                return back()->with('error', "Jumlah pengembalian {$peminjaman->alat->name} minimal 1 unit!");
            }
        }

        // Jika validasi lolos, baru proses pengembalian
        foreach ($ids as $id) {
            $peminjaman = PeminjamanAlat::findOrFail($id);
            $jumlahKembali = isset($jumlahData[$id]) ? (int)$jumlahData[$id] : $peminjaman->jumlah;
            
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => now(),
            ]);
            
            $peminjaman->alat->tambahAvailable($jumlahKembali);
        }

        $count = count($ids);
        $message = $count === 1 ? 'Alat berhasil dikembalikan!' : "{$count} alat berhasil dikembalikan!";
        
        return redirect()->route('peminjam.pengembalian-alat')->with('success', $message);
    }

    // ========== MATERIAL ==========
    
    // Halaman Lihat Material
    public function material(Request $request)
    {
        $q = $request->input('q');

        $material = Material::with('category')
            ->when($q, function($query, $q) {
                $query->where(function($sub) use ($q) {
                    $sub->where('name', 'LIKE', "%{$q}%")
                        ->orWhere('description', 'LIKE', "%{$q}%");
                });
            })
            ->orderBy('name', 'asc')
            ->get();

        return view('peminjam.material', compact('material', 'q'));
    }

    // Proses Ambil Material
    public function ambilMaterial(Request $request)
    {
        $request->validate([
            'nama_pengambil' => 'required|string|max:255',
            'keperluan' => 'nullable|string',
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
                'status' => 'diambil',
            ]);

            $material->kurangiStock($jumlah);
        }

        $count = count($materials);
        $message = $count === 1 ? 'Material berhasil diambil!' : "{$count} material berhasil diambil!";
        
        return back()->with('success', $message);
    }

    // Halaman Pengembalian Material
    public function pengembalianMaterial(Request $request)
    {
        $q = $request->input('q');

        $pengambilan = PengambilanMaterial::with('material')
            ->where('status', 'diambil')
            ->when($q, function($query, $q) {
                $query->where(function($sub) use ($q) {
                    $sub->whereHas('material', function($mat) use ($q) {
                            $mat->where('name', 'LIKE', "%{$q}%");
                        })
                        ->orWhere('nama_pengambil', 'LIKE', "%{$q}%")
                        ->orWhere('keperluan', 'LIKE', "%{$q}%");
                });
            })
            ->orderBy('tanggal_ambil', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('peminjam.pengembalian-material', compact('pengambilan', 'q'));
    }

    // Proses Kembalikan Material
    public function kembalikanMaterial(Request $request, $id)
    {
        $pengambilan = PengambilanMaterial::findOrFail($id);

        if ($pengambilan->status !== 'diambil') {
            return back()->with('error', 'Material sudah dikembalikan!');
        }

        // Get jumlah kembali from request
        $jumlahData = json_decode($request->input('jumlah_data', '{}'), true);
        $itemsData = json_decode($request->input('items', '[]'), true);
        
        // Try to get from jumlahData first, then itemsData
        $jumlahKembali = $pengambilan->jumlah; // default
        
        if (isset($jumlahData[$id])) {
            $jumlahKembali = (int)$jumlahData[$id];
        } elseif (!empty($itemsData)) {
            foreach ($itemsData as $item) {
                if ($item['id'] == $id) {
                    $jumlahKembali = (int)$item['jumlah_kembali'];
                    break;
                }
            }
        }

        // Validasi: jumlah kembali tidak boleh melebihi yang diambil
        if ($jumlahKembali > $pengambilan->jumlah) {
            return back()->with('error', "Jumlah pengembalian ({$jumlahKembali} unit) melebihi jumlah yang diambil ({$pengambilan->jumlah} unit)!");
        }

        if ($jumlahKembali < 1) {
            return back()->with('error', 'Jumlah pengembalian minimal 1 unit!');
        }

        $pengambilan->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
        ]);

        // Add back to stock
        $pengambilan->material->tambahStock($jumlahKembali);

        return redirect()->route('peminjam.pengembalian-material')->with('success', 'Material berhasil dikembalikan!');
    }

    // Proses Kembalikan Multiple Material
    public function kembalikanMultipleMaterial(Request $request)
    {
        $request->validate([
            'items' => 'required|json',
        ]);

        $items = json_decode($request->items, true);
        
        if (empty($items)) {
            return back()->with('error', 'Tidak ada material yang dipilih!');
        }

        // Get jumlah data
        $jumlahData = json_decode($request->input('jumlah_data', '{}'), true);

        // Validasi dulu semua item
        foreach ($items as $item) {
            $pengambilan = PengambilanMaterial::findOrFail($item['id']);
            $jumlahKembali = isset($jumlahData[$item['id']]) 
                ? (int)$jumlahData[$item['id']] 
                : (int)$item['jumlah_kembali'];
            
            if ($jumlahKembali > $pengambilan->jumlah) {
                return back()->with('error', "Jumlah pengembalian {$pengambilan->material->name} ({$jumlahKembali} unit) melebihi yang diambil ({$pengambilan->jumlah} unit)!");
            }
            
            if ($jumlahKembali < 1) {
                return back()->with('error', "Jumlah pengembalian {$pengambilan->material->name} minimal 1 unit!");
            }
        }

        // Jika validasi lolos, baru proses pengembalian
        foreach ($items as $item) {
            $pengambilan = PengambilanMaterial::findOrFail($item['id']);
            $jumlahKembali = isset($jumlahData[$item['id']]) 
                ? (int)$jumlahData[$item['id']] 
                : (int)$item['jumlah_kembali'];
            
            $pengambilan->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => now(),
            ]);
            
            $pengambilan->material->tambahStock($jumlahKembali);
        }

        $count = count($items);
        $message = $count === 1 ? 'Material berhasil dikembalikan!' : "{$count} material berhasil dikembalikan!";
        
        return redirect()->route('peminjam.pengembalian-material')->with('success', $message);
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

        return view('peminjam.riwayat', compact('riwayatAlat', 'riwayatMaterial', 'nama'));
    }
}