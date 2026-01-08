<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Alat;
use App\Models\Material;
use App\Models\User;
use App\Models\StockSnapshot;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('s0t0kudus'),
        ]);

        // Buat Kategori Static
        $alatCategory = Category::create([
            'name' => 'Alat',
            'description' => 'Alat-alat yang dapat dipinjam',
        ]);

        $materialCategory = Category::create([
            'name' => 'Material',
            'description' => 'Material yang dapat diambil',
        ]);

        // Buat Alat
        $alats = [
            ['name' => 'Kunci Y', 'description' => 'Kunci Y untuk pekerjaan mekanikal'],
            ['name' => 'Kunci 8/9', 'description' => 'Kunci pas ukuran 8/9'],
            ['name' => 'Kunci 10/11', 'description' => 'Kunci pas ukuran 10/11'],
            ['name' => 'Kunci 12/12', 'description' => 'Kunci pas ukuran 12/12'],
            ['name' => 'Kunci 12/13', 'description' => 'Kunci pas ukuran 12/13'],
            ['name' => 'Kunci 9/16', 'description' => 'Kunci pas ukuran 9/16'],
            ['name' => 'Kunci Inggris', 'description' => 'Kunci inggris adjustable'],
            ['name' => 'Palu Bogem', 'description' => 'Palu untuk pekerjaan berat'],
            ['name' => 'Obeng Amerika', 'description' => 'Obeng tipe Amerika'],
            ['name' => 'Obeng Biru Hitam', 'description' => 'Obeng set biru hitam'],
            ['name' => 'Tang Jepit', 'description' => 'Tang untuk menjepit'],
            ['name' => 'Tang Potong', 'description' => 'Tang untuk memotong kabel'],
            ['name' => 'Tang Kombinasi Merah', 'description' => 'Tang kombinasi warna merah'],
            ['name' => 'Tang Kombinasi Biru', 'description' => 'Tang kombinasi warna biru'],
            ['name' => 'Tang Crimping', 'description' => 'Tang untuk crimping kabel UTP'],
            ['name' => 'Stripper Kuning Kecil', 'description' => 'Stripper kabel kuning ukuran kecil'],
            ['name' => 'Gergaji Besi', 'description' => 'Gergaji untuk memotong besi'],
            ['name' => 'Bor Box', 'description' => 'Bor dalam box'],
            ['name' => 'Konektor RJ45', 'description' => 'Konektor RJ45 untuk kabel UTP'],
            ['name' => 'Klem Kabel 6', 'description' => 'Klem kabel ukuran 6'],
            ['name' => 'Klem Kabel 7', 'description' => 'Klem kabel ukuran 7'],
            ['name' => 'Klem Kabel 8', 'description' => 'Klem kabel ukuran 8'],
            ['name' => 'Klem Kabel 9', 'description' => 'Klem kabel ukuran 9'],
            ['name' => 'Klem Kabel 10', 'description' => 'Klem kabel ukuran 10'],
            ['name' => 'Klem Knalpot', 'description' => 'Klem untuk knalpot'],
            ['name' => 'Klem Galvanis', 'description' => 'Klem galvanis'],
            ['name' => 'Kabel Ties Besar', 'description' => 'Cable ties ukuran besar'],
            ['name' => 'Kabel Ties Kecil', 'description' => 'Cable ties ukuran kecil'],
            ['name' => 'Ties Label', 'description' => 'Ties dengan label'],
            ['name' => 'Kabel Power', 'description' => 'Kabel power listrik'],
            ['name' => 'Kabel UTP', 'description' => 'Kabel UTP Cat5e/Cat6'],
            ['name' => 'Kabel Coaxial', 'description' => 'Kabel coaxial untuk antena'],
            ['name' => 'Switch Coaxial', 'description' => 'Switch untuk kabel coaxial'],
            ['name' => 'Steker', 'description' => 'Steker colokan listrik'],
            ['name' => 'Adaptor', 'description' => 'Adaptor power supply'],
            ['name' => 'Router Mercusys', 'description' => 'Router brand Mercusys'],
            ['name' => 'Dynabolt', 'description' => 'Dynabolt untuk instalasi'],
            ['name' => 'Terminal Listrik', 'description' => 'Terminal sambungan listrik'],
            ['name' => 'Paku Baja', 'description' => 'Paku baja untuk konstruksi'],
            ['name' => 'L mond', 'description' => 'L mond untuk kabel'],
            ['name' => 'Box Panel', 'description' => 'Box panel listrik'],
            ['name' => 'Isolasi Listrik', 'description' => 'Isolasi untuk kabel listrik'],
            ['name' => 'Baut K', 'description' => 'Baut tipe K'],
            ['name' => 'Baut B', 'description' => 'Baut tipe B'],
            ['name' => 'Foam Tape', 'description' => 'Foam tape untuk pemasangan'],
            ['name' => 'Ring', 'description' => 'Ring untuk baut'],
            ['name' => 'POE Segitiga', 'description' => 'POE adapter tipe segitiga'],
            ['name' => 'POE UBNT', 'description' => 'POE Ubiquiti'],
            ['name' => 'Meteran', 'description' => 'Meteran untuk mengukur'],
            ['name' => 'Tespen', 'description' => 'Tespen untuk cek listrik'],
            ['name' => 'Lan Tester', 'description' => 'Tester untuk kabel LAN'],
            ['name' => 'Matabor Tembok 10mm', 'description' => 'Mata bor tembok diameter 10mm'],
        ];

        foreach ($alats as $alat) {
            Alat::create([
                'name' => $alat['name'],
                'category_id' => $alatCategory->id,
                'description' => $alat['description'],
                'status' => 'available',
                'available' => 1,
            ]);
        }

        // Buat Material
        $materials = [
            ['name' => 'Router Mercusys', 'description' => 'Router Mercusys', 'stock' => 0],
            ['name' => 'Router Totolink', 'description' => 'Router Totolink', 'stock' => 0],
            ['name' => 'Router Tenda', 'description' => 'Router Tenda', 'stock' => 0],
            ['name' => 'Radio Bullet', 'description' => 'Radio Bullet', 'stock' => 0],
            ['name' => 'Radio Rocket', 'description' => 'Radio Rocket', 'stock' => 0],
            ['name' => 'Radio Litebeam', 'description' => 'Radio Litebeam', 'stock' => 0],
            ['name' => 'Radio NS M5', 'description' => 'Radio NS M5', 'stock' => 0],
            ['name' => 'Radio Loco M5', 'description' => 'Radio Loco M5', 'stock' => 0],
            ['name' => 'Radio Loco M2', 'description' => 'Radio Loco M2', 'stock' => 0],
            ['name' => 'Radio NS M2', 'description' => 'Radio NS M2', 'stock' => 0],
            ['name' => 'Radio Pharos', 'description' => 'Radio Pharos', 'stock' => 0],
            ['name' => 'Radio Tenda o1', 'description' => 'Radio Tenda o1', 'stock' => 0],
            ['name' => 'Radio Sqxt', 'description' => 'Radio Sqxt', 'stock' => 0],
            ['name' => 'HTB AB', 'description' => 'HTB AB', 'stock' => 11],
            ['name' => 'HTB A', 'description' => 'HTB A', 'stock' => 0],
            ['name' => 'HTB B', 'description' => 'HTB B', 'stock' => 8],
            ['name' => 'HTB 3', 'description' => 'HTB 3', 'stock' => 1],
            ['name' => 'HTB 6', 'description' => 'HTB 6', 'stock' => 5],
            ['name' => 'HTB 6Gbit', 'description' => 'HTB 6Gbit', 'stock' => 0],
            ['name' => 'HTB 6A', 'description' => 'HTB 6A', 'stock' => 0],
            ['name' => 'HTB GBit 2', 'description' => 'HTB GBit 2', 'stock' => 0],
            ['name' => 'HTB GBit AB', 'description' => 'HTB GBit AB', 'stock' => 29],
            ['name' => 'Switch 5 GBit', 'description' => 'Switch 5 GBit', 'stock' => 0],
            ['name' => 'Switch 8 GBit', 'description' => 'Switch 8 GBit', 'stock' => 0],
            ['name' => 'Switch 16 GBit', 'description' => 'Switch 16 GBit', 'stock' => 0],
            ['name' => 'Switch 24 GBit', 'description' => 'Switch 24 GBit', 'stock' => 0],
            ['name' => 'Adaptor 24V', 'description' => 'Adaptor 24V', 'stock' => 0],
            ['name' => 'Adaptor 5v', 'description' => 'Adaptor 5v', 'stock' => 0],
            ['name' => 'PoE GBit', 'description' => 'PoE GBit', 'stock' => 0],
            ['name' => 'PoE Biasa', 'description' => 'PoE Biasa', 'stock' => 4],
            ['name' => 'Kabel Lan Spectra', 'description' => 'Kabel Lan Spectra', 'stock' => 0],
            ['name' => 'Kabel Lan Vascolink', 'description' => 'Kabel Lan Vascolink', 'stock' => 0],
            ['name' => 'Kabel Lan Commscope', 'description' => 'Kabel Lan Commscope', 'stock' => 0],
            ['name' => 'Kabel Lan Belden', 'description' => 'Kabel Lan Belden', 'stock' => 0],
            ['name' => 'Dropcore Infinity', 'description' => 'Dropcore Infinity', 'stock' => 0],
            ['name' => 'Fastcont', 'description' => 'Fastcont', 'stock' => 0],
        ];

        foreach ($materials as $material) {
            Material::create([
                'name' => $material['name'],
                'category_id' => $materialCategory->id,
                'description' => $material['description'],
                'stock' => $material['stock'],
            ]);
        }

        // Buat snapshot untuk kemarin (agar dashboard bisa tampil beda)
        $totalAlatAvailable = Alat::sum('available');
        $totalMaterialStock = Material::sum('stock');
        
        StockSnapshot::create([
            'tanggal' => Carbon::yesterday(),
            'total_alat_available' => $totalAlatAvailable,
            'total_material_stock' => $totalMaterialStock,
        ]);
    }
}