# Inventory System - Cleon

Sistem manajemen inventori untuk pengelolaan alat dan material di Cleon. Aplikasi ini dibangun menggunakan Laravel 11 dan Filament v3 untuk admin panel.

## Fitur Utama

### Admin Panel (Filament)
- **Manajemen Kategori**: Kelola kategori untuk alat dan material
- **Manajemen Alat**: CRUD alat dengan tracking jumlah tersedia
- **Manajemen Material**: CRUD material dengan tracking stok
- **Peminjaman Alat**: View data peminjaman alat oleh peminjam (tidak bisa create manual)
- **Pengambilan Material**: View data pengambilan material oleh peminjam (tidak bisa create manual)
- **Stock Snapshot**: Snapshot otomatis stok harian (via cron job)

### Portal Peminjam
- **Dashboard**: Overview peminjaman dan pengambilan aktif
- **Peminjaman Alat**: Pinjam alat yang tersedia
- **Pengembalian Alat**: Kembalikan alat yang dipinjam (single/multiple)
- **Pengambilan Material**: Ambil material yang tersedia
- **Pengembalian Material**: Kembalikan material yang diambil (single/multiple)
- **Riwayat**: Lihat history peminjaman dan pengambilan (filter by name)
- **Riwayat Aktivitas**: Lihat semua aktivitas dari semua tim dengan filter tanggal (Filament-style)


## Tech Stack

- **Framework**: Laravel 12
- **PHP**: 8.2+
- **Database**: MySQL
- **Admin Panel**: Filament v3.3
- **Frontend**: TailwindCSS, FontAwesome 6.5.1
- **Package Manager**: Composer, NPM

## Struktur Database

### Tables
1. **categories** - Kategori alat/material
2. **alats** - Data alat dengan jumlah tersedia
3. **materials** - Data material dengan stok
4. **peminjaman_alats** - Transaksi peminjaman alat
5. **pengambilan_materials** - Transaksi pengambilan material
6. **stock_snapshots** - Snapshot stok harian
7. **users** - Data user (admin)

## Instalasi

### Requirements
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM
- MySQL 5.7+ / MariaDB 10.3+
- Web server (Apache/Nginx)

### Langkah Instalasi

1. **Clone Repository**
```bash
git clone https://github.com/vazul76/inventory-cleon.git
cd inventory-cleon
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database Configuration**

Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_cleon
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run Migration**
```bash
php artisan migrate
```

6. **Create Admin User**
```bash
php artisan make:filament-user
```
Ikuti prompt untuk membuat user admin.

7. **Build Assets**
```bash
npm run build
```

8. **Run Development Server**
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## Konfigurasi Cron Job

Untuk snapshot stok otomatis setiap hari, tambahkan cron job:

```bash
* * * * * cd /path/to/inventory-system && php artisan schedule:run >> /dev/null 2>&1
```

Scheduler akan menjalankan `StockSnapshotCommand` setiap hari pada pukul 00:00.

## Akses Aplikasi

### Admin Panel
- URL: `http://localhost:8000/admin`
- Login menggunakan kredensial yang dibuat saat `make:filament-user`

### Portal Peminjam
- URL: `http://localhost:8000/`
- Tidak memerlukan login

## Development

### Run Development Server
```bash
php artisan serve
```

### Watch Assets (Hot Reload)
```bash
npm run dev
```

### Run Tests
```bash
php artisan test
```

## Deployment

1. Set environment ke production di `.env`:
```env
APP_ENV=production
APP_DEBUG=false
```

2. Optimize aplikasi:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
npm run build
```

3. Setup cron job di server production
4. Konfigurasi web server (Apache/Nginx)

## Dokumentasi Penggunaan

Lihat [PENGGUNAAN.md](PENGGUNAAN.md) untuk panduan lengkap penggunaan aplikasi.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Author

© 2026 Vazul