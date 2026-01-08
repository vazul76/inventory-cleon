# Panduan Penggunaan - Inventory System Cleon ISP

Dokumentasi lengkap cara menggunakan sistem inventori untuk admin dan peminjam.

---

## Daftar Isi

1. [Admin Panel](#admin-panel)
2. [Portal Peminjam](#portal-peminjam)
3. [Workflow Peminjaman](#workflow-peminjaman)
4. [FAQ](#faq)

---

## Admin Panel

Admin panel menggunakan Filament v3 untuk kemudahan pengelolaan data.

### Akses Admin Panel

1. Buka browser dan akses: `http://localhost:8000/admin`
2. Login dengan kredensial admin yang sudah dibuat
3. Dashboard admin akan tampil dengan menu navigasi di sidebar

### 1. Manajemen Kategori

**Menu: Categories**

Kategori digunakan untuk mengelompokkan alat dan material.

#### Membuat Kategori Baru
1. Klik menu **Categories** di sidebar
2. Klik tombol **New Category** (pojok kanan atas)
3. Isi form:
   - **Name**: Nama kategori (contoh: "Hand Tools", "Network Equipment", "Kabel")
4. Klik **Create**

#### Edit/Hapus Kategori
- Klik ikon **Edit** (pensil) untuk mengubah data
- Klik ikon **Delete** (trash) untuk menghapus kategori
- Kategori yang sudah digunakan tidak bisa dihapus

---

### 2. Manajemen Alat

**Menu: Alat**

Kelola data alat yang bisa dipinjam oleh peminjam.

#### Menambah Alat Baru
1. Klik menu **Alat** di sidebar
2. Klik tombol **New Alat**
3. Isi form:
   - **Category**: Pilih kategori alat
   - **Name**: Nama alat (contoh: "Tang Potong", "Crimping Tool")
   - **Available**: Jumlah alat yang tersedia untuk dipinjam
   - **Deskripsi**: (opsional) Deskripsi detail alat
4. Klik **Create**

#### Informasi Penting
- **Available** adalah jumlah alat yang bisa dipinjam
- Saat alat dipinjam, jumlah **Available** otomatis berkurang
- Saat alat dikembalikan, jumlah **Available** otomatis bertambah
- Kolom **Available** bisa diupdate manual jika ada penambahan/pengurangan stok

#### Edit Alat
1. Klik ikon **Edit** pada data alat
2. Ubah data yang diperlukan
3. Klik **Save**

#### Hapus Alat
- Klik ikon **Delete** untuk menghapus alat
- Alat yang sedang dipinjam tidak bisa dihapus

---

### 3. Manajemen Material

**Menu: Material**

Kelola data material yang bisa diambil oleh peminjam.

#### Menambah Material Baru
1. Klik menu **Material** di sidebar
2. Klik tombol **New Material**
3. Isi form:
   - **Category**: Pilih kategori material
   - **Name**: Nama material (contoh: "Kabel UTP Cat6", "Connector RJ45")
   - **Stock**: Jumlah stok material yang tersedia
   - **Satuan**: Unit material (contoh: "meter", "pcs", "roll")
   - **Deskripsi**: (opsional) Deskripsi material
4. Klik **Create**

#### Informasi Penting
- **Stock** otomatis berkurang saat material diambil peminjam
- **Stock** otomatis bertambah saat material dikembalikan
- Bisa update stok manual untuk restock atau adjustment

#### Edit/Hapus Material
- Sama seperti manajemen alat
- Material yang sedang digunakan tidak bisa dihapus

---

### 4. Peminjaman Alat (View Only)

**Menu: Peminjaman Alat**

Menu ini menampilkan semua transaksi peminjaman alat. Admin **tidak bisa** membuat peminjaman baru (hanya peminjam yang bisa via portal).

#### Fitur yang Tersedia:

**1. View Data Peminjaman**
- Kolom ditampilkan:
  - **Alat**: Nama alat yang dipinjam
  - **Jumlah**: Jumlah yang dipinjam
  - **Nama Peminjam**: Nama peminjam/tim
  - **Tanggal Pinjam**: Waktu peminjaman
  - **Tanggal Kembali**: Waktu pengembalian (kosong jika belum kembali)
  - **Status**: Badge hijau (Dikembalikan) atau oranye (Dipinjam)

**2. Tombol Kembalikan**
- Tombol **Kembalikan** muncul pada data berstatus "Dipinjam"
- Klik tombol untuk mengembalikan alat secara manual dari admin panel
- Konfirmasi action, lalu alat akan dikembalikan dan stok available bertambah

**3. Filter**
- Filter berdasarkan **Status**: Dipinjam / Dikembalikan
- Gunakan untuk melihat peminjaman yang masih aktif

**4. Edit Data**
- Admin bisa edit keterangan atau jumlah jika ada koreksi
- **Perhatian**: Mengubah jumlah tidak otomatis update stok available

**5. Delete Data**
- Bisa menghapus transaksi peminjaman
- **Hati-hati**: Menghapus data tidak akan mengembalikan stok available alat

---

### 5. Pengambilan Material (View Only)

**Menu: Pengambilan Material**

Menu ini menampilkan transaksi pengambilan material. Admin **tidak bisa** membuat pengambilan baru.

#### Fitur yang Tersedia:

**1. View Data Pengambilan**
- Kolom ditampilkan:
  - **Material**: Nama material
  - **Pengambil**: Nama pengambil/tim
  - **Jumlah**: Jumlah yang diambil
  - **Tanggal Ambil**: Waktu pengambilan
  - **Tanggal Kembali**: Waktu pengembalian (kosong jika belum kembali)

**2. Toggle Kolom**
- Kolom **Tanggal Kembali** bisa di-toggle (show/hide) via icon kolom

**3. Edit/Delete**
- Admin bisa edit atau hapus data pengambilan
- **Perhatian**: Hapus data tidak otomatis mengembalikan stok material

---

### 6. Stock Snapshot

**Menu: Stock Snapshot**

Snapshot otomatis menyimpan data stok harian untuk tracking dan pelaporan.

#### Cara Kerja
- **Otomatis**: Cron job menjalankan snapshot setiap hari pukul 00:00
- **Manual**: Admin bisa trigger manual via command:
  ```bash
  php artisan app:stock-snapshot
  ```

#### Data yang Disimpan
- **Tanggal**: Tanggal snapshot
- **Total Alat Available**: Total semua alat yang tersedia
- **Total Material Stock**: Total semua stok material
- **Timestamps**: Waktu snapshot dibuat

#### Kegunaan
- Tracking stok harian
- Membuat laporan bulanan
- Analisis penggunaan alat/material

---

## Portal Peminjam

Portal ini digunakan oleh teknisi/tim untuk meminjam alat dan mengambil material.

### Akses Portal Peminjam

- URL: `http://localhost:8000/`
- **Tidak perlu login** - akses langsung

---

### 1. Dashboard

**URL: `/`**

Halaman utama menampilkan overview:

#### Quick Stats
- **Alat Dipinjam**: Jumlah alat yang sedang Anda pinjam
- **Material Diambil**: Jumlah material yang sedang Anda gunakan
- **Total Alat Tersedia**: Total semua alat yang bisa dipinjam
- **Total Material Tersedia**: Total semua material yang tersedia

#### Recent Activity
- 5 peminjaman terakhir Anda
- 5 pengambilan material terakhir

---

### 2. Peminjaman Alat

**Menu: Alat**

#### Cara Meminjam Alat

1. Klik menu **Alat** di sidebar
2. Lihat daftar alat yang tersedia
3. Pada alat yang ingin dipinjam, klik tombol **Pinjam**
4. Isi form di modal:
   - **Nama Peminjam**: Nama Anda atau tim (contoh: "Tim Instalasi 1")
   - **Jumlah**: Jumlah alat yang ingin dipinjam
   - **Keterangan**: (opsional) Keperluan peminjaman
5. Klik tombol **Pinjam Alat**

#### Informasi
- Jumlah yang bisa dipinjam dibatasi sesuai stok **Available**
- Setelah dipinjam, stok available otomatis berkurang
- Alat yang available = 0 tidak bisa dipinjam

---

### 3. Pengembalian Alat

**Menu: Pengembalian Alat**

#### Cara Mengembalikan Alat (Single)

1. Klik menu **Pengembalian Alat** di sidebar
2. Lihat daftar alat yang sedang Anda pinjam
3. Centang **checkbox** pada alat yang ingin dikembalikan
4. Klik tombol **Kembalikan Terpilih**
5. Konfirmasi di modal yang muncul
6. Klik **Kembalikan**

#### Cara Mengembalikan Multiple Alat

1. Centang **checkbox** pada beberapa alat sekaligus
2. Klik tombol **Kembalikan Terpilih**
3. Modal akan menampilkan list semua alat yang akan dikembalikan
4. Konfirmasi dan klik **Kembalikan**

#### Informasi Penting
- Alat dikembalikan **full** (tidak bisa partial quantity)
- Contoh: Jika pinjam 5 unit, harus kembalikan 5 unit sekaligus
- Stok available otomatis bertambah setelah pengembalian
- Tanggal kembali otomatis tercatat

---

### 4. Pengambilan Material

**Menu: Material**

#### Cara Mengambil Material

1. Klik menu **Material** di sidebar
2. Lihat daftar material yang tersedia
3. Pada material yang ingin diambil, klik tombol **Ambil**
4. Isi form di modal:
   - **Nama Pengambil**: Nama Anda atau tim (contoh: "Tim Field 2")
   - **Jumlah**: Jumlah material yang diambil
   - **Keperluan**: (opsional) Untuk apa material digunakan
5. Klik tombol **Ambil Material**

#### Informasi
- Jumlah dibatasi sesuai stok yang tersedia
- Material dengan stok = 0 tidak bisa diambil
- Stok otomatis berkurang setelah pengambilan

---

### 5. Pengembalian Material

**Menu: Pengembalian Material**

#### Cara Mengembalikan Material

Sama seperti pengembalian alat:

1. Klik menu **Pengembalian Material**
2. Centang checkbox pada material yang ingin dikembalikan
3. Klik **Kembalikan Terpilih**
4. Konfirmasi di modal
5. Klik **Kembalikan**

#### Informasi
- Material dikembalikan **full** (tidak partial)
- Stok otomatis kembali setelah pengembalian
- Tanggal kembali tercatat otomatis

---

### 6. Riwayat

**Menu: Riwayat**

Lihat history lengkap peminjaman dan pengambilan Anda.

#### Filter Riwayat
- **Nama Peminjam/Pengambil**: Filter berdasarkan nama
- **Jenis**: Filter Alat atau Material
- **Status**: Filter Dipinjam/Diambil atau Dikembalikan

#### Informasi Ditampilkan
- Nama alat/material
- Jumlah
- Tanggal pinjam/ambil
- Tanggal kembali
- Status (badge warna)
- Keterangan

---

## Workflow Peminjaman

### Alur Peminjaman Alat

```
1. Peminjam akses portal → Menu Alat
2. Pilih alat → Klik "Pinjam"
3. Isi form (nama, jumlah, keterangan)
4. Submit → Stok Available berkurang
5. Data tercatat di "Peminjaman Alat" (admin panel)
6. Saat selesai → Menu "Pengembalian Alat"
7. Centang alat → Klik "Kembalikan Terpilih"
8. Konfirmasi → Stok Available bertambah
9. Status berubah "Dikembalikan"
```

### Alur Pengambilan Material

```
1. Peminjam akses portal → Menu Material
2. Pilih material → Klik "Ambil"
3. Isi form (nama, jumlah, keperluan)
4. Submit → Stok Material berkurang
5. Data tercatat di "Pengambilan Material" (admin panel)
6. Jika material dikembalikan → Menu "Pengembalian Material"
7. Centang material → Klik "Kembalikan Terpilih"
8. Konfirmasi → Stok Material bertambah
9. Tanggal kembali tercatat
```

---

## FAQ

### 1. Bagaimana cara menambah stok alat/material?

**Admin Panel:**
- Edit data alat/material
- Update field **Available** (alat) atau **Stock** (material)
- Save

### 2. Apakah peminjam perlu login?

**Tidak.** Portal peminjam tidak memerlukan autentikasi. Semua orang bisa akses dan input nama saat meminjam.

### 3. Bagaimana jika salah input jumlah saat meminjam?

**Admin bisa koreksi:**
- Buka menu **Peminjaman Alat** atau **Pengambilan Material**
- Edit data yang salah
- Update jumlah
- **Catatan**: Stok available/stock tidak otomatis update, perlu manual adjustment

### 4. Apakah bisa mengembalikan sebagian alat?

**Tidak.** Sistem hanya mendukung full return. Jika pinjam 5 unit, harus kembalikan 5 unit sekaligus.

### 5. Bagaimana cara tracking siapa yang pinjam alat tertentu?

**Admin Panel:**
- Menu **Peminjaman Alat**
- Filter status "Dipinjam"
- Lihat kolom "Nama Peminjam" dan "Alat"

### 6. Apakah bisa ekspor data ke Excel?

**Saat ini belum tersedia.** Tapi Filament mendukung export, bisa ditambahkan dengan plugin Filament Excel.

### 7. Bagaimana cara melihat laporan stok bulanan?

**Gunakan Stock Snapshot:**
- Menu **Stock Snapshot** di admin panel
- Filter berdasarkan range tanggal
- Data bisa diekspor atau diolah lebih lanjut

### 8. Apa yang terjadi jika stok available/stock negatif?

**Tidak bisa.** Sistem mencegah peminjaman/pengambilan melebihi stok yang tersedia.

### 9. Bagaimana cara menghapus semua data transaksi lama?

**Manual via admin panel:**
- Hapus satu per satu di menu Peminjaman Alat / Pengambilan Material

**Via database:**
```sql
TRUNCATE peminjaman_alats;
TRUNCATE pengambilan_materials;
```

### 10. Apakah ada notifikasi jika alat belum dikembalikan?

**Saat ini belum ada.** Fitur reminder bisa ditambahkan dengan queue dan email notification.

---

## Tips & Best Practices

### Untuk Admin

1. **Update stok secara berkala** - Cek stok fisik vs stok sistem
2. **Review peminjaman aktif** - Pastikan alat yang dipinjam lama segera dikembalikan
3. **Gunakan kategori dengan bijak** - Kategorisasi memudahkan pencarian
4. **Setup cron job** - Pastikan stock snapshot berjalan otomatis
5. **Backup database rutin** - Hindari kehilangan data transaksi

### Untuk Peminjam

1. **Input nama yang jelas** - Gunakan nama tim atau identitas yang mudah dikenali
2. **Kembalikan tepat waktu** - Agar alat bisa digunakan orang lain
3. **Cek stok sebelum meminjam** - Pastikan jumlah yang tersedia cukup
4. **Isi keterangan** - Memudahkan tracking penggunaan alat/material
5. **Kembalikan segera jika tidak digunakan** - Jangan menumpuk peminjaman

---

## Troubleshooting

### Masalah Umum

**1. Tombol "Pinjam" tidak muncul**
- Cek apakah stok available > 0
- Refresh halaman

**2. Error saat submit peminjaman**
- Pastikan semua field required terisi
- Cek jumlah tidak melebihi stok
- Periksa koneksi database

**3. Stok tidak update setelah pengembalian**
- Periksa apakah transaksi benar-benar berhasil
- Cek di admin panel apakah status sudah "Dikembalikan"
- Refresh cache: `php artisan cache:clear`

**4. Stock snapshot tidak berjalan otomatis**
- Pastikan cron job sudah disetup di server
- Test manual: `php artisan app:stock-snapshot`
- Cek log: `storage/logs/laravel.log`

**5. Halaman admin tidak bisa diakses**
- Pastikan sudah login sebagai admin
- Clear browser cache
- Periksa .env file (APP_URL, DB config)

---

## Kontak Support

Untuk bantuan lebih lanjut, hubungi:
- **Email**: support@cleon-isp.com
- **Developer**: vazul@cleon-isp.com

---

© 2026 Cleon ISP - Inventory Management System
