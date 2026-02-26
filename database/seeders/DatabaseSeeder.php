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
        ]);

        $materialCategory = Category::create([
            'name' => 'Material',
        ]);

        // Buat Alat
        $alats = [
            ['name' => 'Lan Tester', 'description' => 'Tester untuk kabel LAN'],
            ['name' => 'OPM', 'description' => 'OPM'],
            ['name' => 'OTDR', 'description' => 'OTDR'],
            ['name' => 'Cleaver', 'description' => 'Cleaver'],
            ['name' => 'Splicer', 'description' => 'Splicer'],
            ['name' => 'Kunci Y', 'description' => 'Kunci Y untuk pekerjaan mekanikal'],
            ['name' => 'Kunci 8/9', 'description' => 'Kunci pas ukuran 8/9'],
            ['name' => 'Kunci 10/11', 'description' => 'Kunci pas ukuran 10/11'],
            ['name' => 'Kunci 12/12', 'description' => 'Kunci pas ukuran 12/12'],
            ['name' => 'Kunci 12/13', 'description' => 'Kunci pas ukuran 12/13'],
            ['name' => 'Kunci 9/16', 'description' => 'Kunci pas ukuran 9/16'],
            ['name' => 'Kunci Inggris', 'description' => 'Kunci inggris adjustable'],
            ['name' => 'Palu Bogem', 'description' => 'Palu untuk pekerjaan berat'],
            ['name' => 'Palu Kambing', 'description' => 'Palu untuk pekerjaan berat'],
            ['name' => 'Obeng Amerika', 'description' => 'Obeng tipe Amerika'],
            ['name' => 'Obeng Biru Hitam', 'description' => 'Obeng set biru hitam'],
            ['name' => 'Tang Jepit', 'description' => 'Tang untuk menjepit'],
            ['name' => 'Tang Potong', 'description' => 'Tang untuk memotong kabel'],
            ['name' => 'Tang Kombinasi', 'description' => 'Tang kombinasi'],
            ['name' => 'Tang Crimping', 'description' => 'Tang untuk crimping kabel UTP'],
            ['name' => 'Stripper', 'description' => 'Stripper kabel'],
            ['name' => 'Gergaji Besi', 'description' => 'Gergaji untuk memotong besi'],
            ['name' => 'Bor Box', 'description' => 'Bor dalam box'],
            ['name' => 'Paku Baja', 'description' => 'Paku baja untuk konstruksi'],
            ['name' => 'L mond', 'description' => 'L mond untuk kabel'],
            ['name' => 'Box Panel', 'description' => 'Box panel listrik'],
            ['name' => 'Baut K', 'description' => 'Baut tipe K'],
            ['name' => 'Baut B', 'description' => 'Baut tipe B'],
            ['name' => 'Ring', 'description' => 'Ring untuk baut'],
            ['name' => 'Meteran', 'description' => 'Meteran untuk mengukur'],
            ['name' => 'Tespen', 'description' => 'Tespen untuk cek listrik'],
            ['name' => 'Matabor Tembok 10mm', 'description' => 'Mata bor tembok diameter 10mm'],
        ];

        $totalAlat = count($alats);
        $halfIndex = (int) ($totalAlat / 2);

        foreach ($alats as $index => $alat) {
            $available = $index < $halfIndex ? 10 : 0;

            Alat::create([
                'name' => $alat['name'],
                'category_id' => $alatCategory->id,
                'description' => $alat['description'],
                'available' => $available,
                'status' => 'available',
            ]);
        }

        // Buat Material
        $materials = [
            ['name' => 'Konektor RJ45 Belden', 'description' => 'Konektor RJ45 Belden', 'stock' => 0],
            ['name' => 'Konektor RJ45 Commscope', 'description' => 'Konektor RJ45 Commscope', 'stock' => 0],
            ['name' => 'Kabel Lan Spectra Cat 5e', 'description' => 'Kabel Lan Spectra Cat 5e', 'stock' => 0],
            ['name' => 'Kabel Lan Spectra Cat 6e', 'description' => 'Kabel Lan Spectra Cat 6e', 'stock' => 0],
            ['name' => 'Kabel Lan Vascolink Cat 5e', 'description' => 'Kabel Lan Vascolink Cat 5e', 'stock' => 0],
            ['name' => 'Kabel Lan Vascolink Cat 6e', 'description' => 'Kabel Lan Vascolink Cat 6e', 'stock' => 0],
            ['name' => 'Kabel Lan Commscope Cat 5e', 'description' => 'Kabel Lan Commscope Cat 5e', 'stock' => 0],
            ['name' => 'Kabel Lan Commscope Cat 6e', 'description' => 'Kabel Lan Commscope Cat 6e', 'stock' => 0],
            ['name' => 'Kabel Lan Belden Cat 5e', 'description' => 'Kabel Lan Belden Cat 6e', 'stock' => 0],
            ['name' => 'Kabel Lan Belden Cat 6e', 'description' => 'Kabel Lan Belden Cat 6e', 'stock' => 0],
            ['name' => 'Kabel Lan Infinity Cat 5e', 'description' => 'Kabel Lan Infinity Cat 5e', 'stock' => 0],
            ['name' => 'Kabel Lan Infinity Cat 6e', 'description' => 'Kabel Lan Infinity Cat 6e', 'stock' => 0],
            ['name' => 'Dropcore Infinity 1c', 'description' => 'Dropcore Infinity 1c', 'stock' => 0],
            ['name' => 'Dropcore Infinity 4c', 'description' => 'Dropcore Infinity 4c', 'stock' => 0],
            ['name' => 'Dropcore Interluc 1c', 'description' => 'Dropcore Interluc 1c', 'stock' => 0],
            ['name' => 'Dropcore Interluc 4c', 'description' => 'Dropcore Interluc 4c', 'stock' => 0],
            ['name' => 'Dropcore Zimmlink 1c', 'description' => 'Dropcore Zimmlink 1c', 'stock' => 0],
            ['name' => 'Dropcore Zimmlink 4c', 'description' => 'Dropcore Zimmlink 4c', 'stock' => 0],
            ['name' => 'Kabel Listrik', 'description' => 'Kabel Listrik', 'stock' => 0],
            ['name' => 'Kabel Power Listrik', 'description' => 'Kabel Power Listrik', 'stock' => 0],
            ['name' => 'Kabel Coaxial', 'description' => 'Kabel coaxial untuk antena', 'stock' => 0],
            ['name' => 'Switch Coaxial', 'description' => 'Switch untuk kabel coaxial', 'stock' => 0],
            ['name' => 'HTB TARMOC Single A', 'description' => 'HTB A', 'stock' => 0],
            ['name' => 'HTB TARMOC Single B', 'description' => 'HTB B', 'stock' => 8],
            ['name' => 'HTB TARMOC Single A GBit', 'description' => 'HTB A GBit', 'stock' => 5],
            ['name' => 'HTB TARMOC Single B GBit', 'description' => 'HTB B GBit', 'stock' => 5],
            ['name' => 'HTB TARMOC Single A No Cover', 'description' => 'HTB A No Cover', 'stock' => 6],
            ['name' => 'HTB TARMOC AB Biasa', 'description' => 'HTB AB Biasa', 'stock' => 11],
            ['name' => 'HTB TARMOC AB GBit', 'description' => 'HTB AB GBit', 'stock' => 14],
            ['name' => 'HTB TARMOC AAB 3FO2LAN', 'description' => 'HTB AAB', 'stock' => 1],
            ['name' => 'HTB TARMOC ABB 3FO2LAN', 'description' => 'HTB ABB', 'stock' => 5],
            ['name' => 'HTB TARMOC AAABBB 6FO2LAN', 'description' => 'HTB AAABBB', 'stock' => 0],
            ['name' => 'Switch HTB AB', 'description' => 'Switch HTB AB', 'stock' => 21],
            ['name' => 'Switch HTB AB GBit', 'description' => 'Switch HTB AB GBit', 'stock' => 0],
            ['name' => 'Switch 5 GBit', 'description' => 'Switch 5 GBit', 'stock' => 0],
            ['name' => 'Switch 8 GBit', 'description' => 'Switch 8 GBit', 'stock' => 1],
            ['name' => 'Switch 16 GBit', 'description' => 'Switch 16 GBit', 'stock' => 0],
            ['name' => 'Switch 24 GBit', 'description' => 'Switch 24 GBit', 'stock' => 0],
            ['name' => 'Router: Mercusys MW302R', 'description' => 'Router Mercusys MW302R', 'stock' => 0],
            ['name' => 'Router: Totolink', 'description' => 'Router Totolink', 'stock' => 0],
            ['name' => 'Router: Tenda', 'description' => 'Router Tenda', 'stock' => 0],
            ['name' => 'Access Point LM', 'description' => 'Access Point LifeMedia', 'stock' => 0],
            ['name' => 'Fast Connector', 'description' => 'Fast Connector', 'stock' => 0],
            ['name' => 'Sleeve Kecil', 'description' => 'Sleeve Kecil', 'stock' => 0],
            ['name' => 'Sleeve Besar', 'description' => 'Sleeve Besar', 'stock' => 0],
            ['name' => 'Patchcord SC-SC', 'description' => 'Patchcord SC-SC', 'stock' => 0],
            ['name' => 'Patchcord SC-UPC', 'description' => 'Patchcord SC-UPC', 'stock' => 0],
            ['name' => 'Patchcord SC-LC', 'description' => 'Patchcord SC-LC', 'stock' => 0],
            ['name' => 'Pigtail: Jolink', 'description' => 'Pigtail Jolink', 'stock' => 0],
            ['name' => 'ODP Tarmoc', 'description' => 'ODP Tarmoc', 'stock' => 0],
            ['name' => 'Roset 4 Core Kosongan', 'description' => 'Roset 4 Core Kosongan', 'stock' => 0],
            ['name' => 'Adaptor 24V', 'description' => 'Adaptor 24V', 'stock' => 0],
            ['name' => 'Adaptor 5v', 'description' => 'Adaptor 5v', 'stock' => 0],
            ['name' => 'Adaptor', 'description' => 'Adaptor power supply', 'stock' => 0],
            ['name' => 'PoE GBit', 'description' => 'PoE GBit', 'stock' => 0],
            ['name' => 'PoE Biasa', 'description' => 'PoE Biasa', 'stock' => 4],
            ['name' => 'POE Segitiga', 'description' => 'POE adapter tipe segitiga', 'stock' => 0],
            ['name' => 'POE UBNT', 'description' => 'POE Ubiquiti', 'stock' => 0],
            ['name' => 'Terminal 4 Lubang', 'description' => 'Terminal 4 Lubang', 'stock' => 0],
            ['name' => 'Steker', 'description' => 'Steker', 'stock' => 0],
            ['name' => 'Barrel', 'description' => 'Barrel', 'stock' => 0],
            ['name' => 'Headlamp', 'description' => 'Headlamp', 'stock' => 0],
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
            ['name' => 'Klem Kabel 6', 'description' => 'Klem kabel ukuran 6', 'stock' => 0],
            ['name' => 'Klem Kabel 7', 'description' => 'Klem kabel ukuran 7', 'stock' => 0],
            ['name' => 'Klem Kabel 8', 'description' => 'Klem kabel ukuran 8', 'stock' => 0],
            ['name' => 'Klem Kabel 9', 'description' => 'Klem kabel ukuran 9', 'stock' => 0],
            ['name' => 'Klem Kabel 10', 'description' => 'Klem kabel ukuran 10', 'stock' => 0],
            ['name' => 'Klem Knalpot', 'description' => 'Klem untuk knalpot', 'stock' => 0],
            ['name' => 'Klem Galvanis', 'description' => 'Klem galvanis', 'stock' => 0],
            ['name' => 'Ties Kabel 100', 'description' => 'Cable ties ukuran 100', 'stock' => 0],
            ['name' => 'Ties Kabel 150', 'description' => 'Cable ties ukuran 150', 'stock' => 0],
            ['name' => 'Ties Kabel 200', 'description' => 'Cable ties ukuran 200', 'stock' => 0],
            ['name' => 'Ties Kabel 250', 'description' => 'Cable ties ukuran 250', 'stock' => 0],
            ['name' => 'Ties Label', 'description' => 'Ties dengan label', 'stock' => 0],
            ['name' => 'Dynabolt', 'description' => 'Dynabolt', 'stock' => 0],
            ['name' => 'Isolasi Listrik', 'description' => 'Isolasi untuk kabel listrik', 'stock' => 10],
            ['name' => 'Foam Tape', 'description' => 'Foam tape untuk pemasangan', 'stock' => 3],
        ];

        $totalMaterials = count($materials);
        $halfMaterialIndex = (int) ($totalMaterials / 2);

        foreach ($materials as $index => $material) {
            $stock = $index < $halfMaterialIndex ? 10 : 0;

            Material::create([
                'name' => $material['name'],
                'category_id' => $materialCategory->id,
                'description' => $material['description'],
                'stock' => $stock,
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