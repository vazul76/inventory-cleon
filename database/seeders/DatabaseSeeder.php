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
        Alat::create([
            'name' => 'Tang Crimping',
            'category_id' => $alatCategory->id,
            'description' => 'Tang untuk crimping kabel UTP',
            'status' => 'available',
            'quantity' => 5,
            'available' => 5,
        ]);

        Alat::create([
            'name' => 'Obeng Set',
            'category_id' => $alatCategory->id,
            'description' => 'Obeng Plus & Minus berbagai ukuran',
            'status' => 'available',
            'quantity' => 3,
            'available' => 3,
        ]);

        Alat::create([
            'name' => 'Cable Tester',
            'category_id' => $alatCategory->id,
            'description' => 'Untuk tes kabel UTP',
            'status' => 'available',
            'quantity' => 2,
            'available' => 2,
        ]);

        Alat::create([
            'name' => 'LAN Tester',
            'category_id' => $alatCategory->id,
            'description' => 'Tester untuk kabel LAN',
            'status' => 'available',
            'quantity' => 4,
            'available' => 4,
        ]);

        // Buat Material
        Material::create([
            'name' => 'Router TP-Link AC1200',
            'category_id' => $materialCategory->id,
            'description' => 'Router dual band 1200Mbps',
            'stock' => 15,
        ]);

        Material::create([
            'name' => 'HTB Indoor',
            'category_id' => $materialCategory->id,
            'description' => 'Home Terminal Box untuk indoor',
            'stock' => 25,
        ]);

        Material::create([
            'name' => 'HTB Outdoor',
            'category_id' => $materialCategory->id,
            'description' => 'Home Terminal Box untuk outdoor',
            'stock' => 20,
        ]);

        Material::create([
            'name' => 'Switch 8 Port Gigabit',
            'category_id' => $materialCategory->id,
            'description' => 'Gigabit Switch 8 port',
            'stock' => 10,
        ]);

        Material::create([
            'name' => 'Kabel UTP Cat6 (Roll)',
            'category_id' => $materialCategory->id,
            'description' => 'Kabel UTP Cat6 per roll (305m)',
            'stock' => 8,
        ]);
    }
}