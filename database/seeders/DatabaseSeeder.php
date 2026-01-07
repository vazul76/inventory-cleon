<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Alat;
use App\Models\Material;
use App\Models\User;
use Illuminate\Database\Seeder;

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
                'quantity' => 1,
                'available' => 1,
            ]);
        }

        // Buat Material (sample, bisa diedit lewat admin)
        Material::create([
            'name' => 'Sample Material',
            'category_id' => $materialCategory->id,
            'description' => 'Material contoh, bisa diedit lewat admin',
            'stock' => 1,
        ]);
    }
}