# Toko Komputer - Sistem Manajemen Toko

Sistem manajemen toko komputer berbasis web dengan PHP dan MySQL untuk mengelola produk, transaksi, stok, dan aktivitas karyawan.

## 📋 Fitur Utama

### 1. **Autentikasi & Keamanan**
- Login fleksibel menggunakan **ID Karyawan**, **Username**, atau **Nama**
- Password hashing dengan `password_hash()`
- Session management
- Prepared statements untuk mencegah SQL injection

### 2. **Manajemen Produk**
- Daftar produk dengan kategori
- Tambah produk baru
- Update stok produk
- Hapus produk
- Informasi: Kode, Nama, Kategori, Merk, Spesifikasi, Stok, Harga

### 3. **Manajemen Karyawan**
- Daftar karyawan
- Tambah karyawan baru
- Hapus karyawan
- Role: Admin dan Staf
- Login tracking

### 4. **Manajemen Customer & Supplier**
- Daftar customer
- Tambah customer baru
- Daftar supplier
- Tambah supplier baru

### 5. **Transaksi Penjualan**
- Form transaksi dengan multi-produk
- Pilih customer (opsional)
- Auto-calculate total
- Update stok otomatis saat transaksi
- Detail transaksi dengan struk
- Cetak struk

### 6. **Barang Masuk**
- Form penerimaan barang dari supplier
- Update stok otomatis
- Tracking supplier
- Log aktifitas otomatis

### 7. **Barang Keluar**
- Form pengeluaran barang
- Validasi stok sebelum keluar
- Update stok otomatis
- Keterangan alasan keluar
- Log aktifitas otomatis

### 8. **Aktifitas (Dashboard Aktivitas)**
- **Tab Transaksi**: Daftar semua transaksi penjualan
- **Tab Barang Masuk**: Daftar semua penerimaan barang
- **Tab Barang Keluar**: Daftar semua pengeluaran barang
- **Tab Log Aktifitas**: Log semua aktivitas karyawan
- Tombol aksi cepat untuk tambah transaksi/barang masuk/barang keluar

## 🗄️ Struktur Database

### Tabel Utama

#### `karyawan`
- `id_karyawan` (VARCHAR) - Primary Key
- `nama` (VARCHAR)
- `username` (VARCHAR)
- `password` (VARCHAR) - Hashed
- `role` (ENUM: admin, staf)

#### `produk`
- `id_produk` (VARCHAR) - Primary Key
- `nama_produk` (TEXT)
- `id_kategori` (VARCHAR)
- `merk` (TEXT)
- `spesifikasi` (VARCHAR)
- `stok` (INT)
- `harga` (BIGINT)

#### `kategori`
- `id_kategori` (VARCHAR) - Primary Key
- `nama_kategori` (VARCHAR)

#### `customer`
- `id_customer` (VARCHAR) - Primary Key
- `nama` (TEXT)
- `email` (TEXT)

#### `supplier`
- `id_supplier` (VARCHAR) - Primary Key
- `nama` (VARCHAR)
- `alamat` (TEXT)
- `email` (TEXT)
- `telepon` (INT)

#### `transaksi`
- `id_transaksi` (VARCHAR) - Primary Key
- `tanggal` (DATE)
- `total` (INT)
- `id_customer` (VARCHAR) - Foreign Key
- `id_karyawan` (VARCHAR) - Foreign Key

#### `detail_transaksi`
- `id_detail` (VARCHAR) - Primary Key
- `id_transaksi` (VARCHAR) - Foreign Key
- `id_produk` (VARCHAR) - Foreign Key
- `jumlah` (INT)
- `subtotal` (DECIMAL)

#### `barang_masuk`
- `id_masuk` (VARCHAR) - Primary Key
- `id_produk` (VARCHAR) - Foreign Key
- `id_supplier` (VARCHAR) - Foreign Key
- `jumlah_masuk` (INT)
- `tanggal` (DATE)
- `id_karyawan` (VARCHAR) - Foreign Key

#### `barang_keluar`
- `id_keluar` (VARCHAR) - Primary Key
- `id_produk` (VARCHAR) - Foreign Key
- `jumlah_keluar` (INT)
- `tanggal` (DATE)
- `id_karyawan` (VARCHAR) - Foreign Key

#### `aktifitas`
- `id_aktifitas` (INT) - Primary Key, Auto Increment
- `id_karyawan` (VARCHAR) - Foreign Key
- `jenis_aktifitas` (ENUM: barang_masuk, barang_keluar, transaksi)
- `keterangan` (TEXT)
- `tanggal` (DATETIME)

## 📁 Struktur Folder

```
toko_komputer/
├── assets/
│   ├── css/
│   │   ├── input.css
│   │   └── output.css
│   ├── js/
│   │   └── main.js
│   └── sssda.png
├── auth/
│   ├── login.php
│   ├── login_poses.php
│   └── logout.php
├── config/
│   ├── db.php
│   ├── helper.php (fungsi generate ID)
│   └── koneksi.php
├── database/
│   └── create_tables.sql
├── includes/
│   ├── navbar.php
│   └── footbar.php
├── pages/
│   ├── dashboard.php
│   ├── produk.php
│   ├── tambah.php
│   ├── update_stok.php
│   ├── update_stok_proses.php
│   ├── hapus_produk.php
│   ├── karyawan.php
│   ├── tambah_karyawan.php
│   ├── tambah_karyawan_proses.php
│   ├── hapus_karyawan.php
│   ├── customer.php
│   ├── tambah_customer_proses.php
│   ├── supplier.php
│   ├── tambah_supplier_proses.php
│   ├── aktifitas.php (halaman utama aktivitas)
│   ├── transaksi.php (tidak digunakan di navbar, diakses via aktifitas)
│   ├── tambah_transaksi.php
│   ├── tambah_transaksi_proses.php
│   ├── detail_transaksi.php
│   ├── barang_masuk.php (tidak digunakan di navbar, diakses via aktifitas)
│   ├── tambah_barang_masuk.php
│   ├── tambah_barang_masuk_proses.php
│   ├── barang_keluar.php (tidak digunakan di navbar, diakses via aktifitas)
│   ├── tambah_barang_keluar.php
│   └── tambah_barang_keluar_proses.php
├── index.php
├── tailwind.config.js
├── package.json
└── README.md
```

## 🚀 Instalasi

### 1. Persyaratan
- XAMPP (PHP 7.4+ dan MySQL 5.7+)
- Web browser modern

### 2. Setup Database
1. Buka phpMyAdmin
2. Buat database baru: `toko_komputer`
3. Import file SQL yang sudah ada atau jalankan `database/create_tables.sql`
4. Pastikan semua tabel sudah dibuat

### 3. Konfigurasi
Edit file `config/db.php` jika diperlukan:
```php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'toko_komputer';
```

### 4. Akses Aplikasi
- Buka browser: `http://localhost/toko_komputer/`
- Login dengan username/password yang sudah ada di database

## 🔑 Login

Sistem login mendukung 3 cara:
1. **ID Karyawan** (contoh: `ADM001`)
2. **Username** (contoh: `admin`)
3. **Nama** (contoh: `Admin`)

Password akan otomatis di-hash jika masih plain text saat login pertama kali.

## 📝 Cara Penggunaan

### Menambah Produk
1. Login sebagai Admin/Staf
2. Klik menu **Tambah**
3. Isi form produk
4. Simpan

### Melakukan Transaksi
1. Klik menu **Aktifitas**
2. Klik tombol **+ Transaksi**
3. Pilih customer (opsional)
4. Pilih produk yang akan dibeli
5. Atur jumlah
6. Klik **Simpan Transaksi**
7. Stok otomatis berkurang

### Menerima Barang Masuk
1. Klik menu **Aktifitas**
2. Klik tombol **+ Barang Masuk**
3. Pilih supplier
4. Pilih produk
5. Masukkan jumlah
6. Simpan
7. Stok otomatis bertambah

### Mengeluarkan Barang
1. Klik menu **Aktifitas**
2. Klik tombol **+ Barang Keluar**
3. Pilih produk
4. Masukkan jumlah
5. Tambahkan keterangan (opsional)
6. Simpan
7. Stok otomatis berkurang

### Melihat Aktifitas
1. Klik menu **Aktifitas**
2. Pilih tab yang diinginkan:
   - **Transaksi**: Lihat semua transaksi penjualan
   - **Barang Masuk**: Lihat semua penerimaan barang
   - **Barang Keluar**: Lihat semua pengeluaran barang
   - **Log Aktifitas**: Lihat log aktivitas karyawan

## 🔧 Fitur Teknis

### Auto-Generate ID
Sistem otomatis generate ID untuk:
- Transaksi: `TRX001`, `TRX002`, dst.
- Customer: `CUS001`, `CUS002`, dst.
- Supplier: `SUP001`, `SUP002`, dst.
- Barang Masuk: `BM001`, `BM002`, dst.
- Barang Keluar: `BK001`, `BK002`, dst.
- Detail Transaksi: `DTL001`, `DTL002`, dst.

### Auto-Update Stok
- **Transaksi**: Stok berkurang otomatis
- **Barang Masuk**: Stok bertambah otomatis
- **Barang Keluar**: Stok berkurang otomatis

### Auto-Log Aktifitas
Setiap aktivitas (transaksi, barang masuk, barang keluar) otomatis tercatat di tabel `aktifitas` dengan informasi:
- Karyawan yang melakukan
- Jenis aktivitas
- Keterangan detail
- Waktu aktivitas

## 🎨 Teknologi yang Digunakan

- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Frontend**: Tailwind CSS
- **UI Components**: Flowbite
- **JavaScript**: Vanilla JS

## 📌 Catatan Penting

1. **Path File**: Semua path sudah disesuaikan dengan struktur folder
2. **Keamanan**: Menggunakan prepared statements untuk semua query
3. **Session**: Semua halaman yang memerlukan login sudah dilindungi
4. **Error Handling**: Error handling sudah ditambahkan di semua file

## 🐛 Troubleshooting

### Error: Tabel tidak ditemukan
- Pastikan sudah menjalankan SQL di `database/create_tables.sql`
- Cek koneksi database di `config/db.php`

### Error: Login tidak berhasil
- Pastikan password di database sudah di-hash
- Cek apakah username/ID/nama ada di database
- Lihat error log PHP untuk detail error

### Error: Path tidak ditemukan
- Pastikan struktur folder sesuai dengan dokumentasi
- Cek semua path include sudah benar (menggunakan `../` jika perlu)

## 📅 Changelog

### 15 November 2025 (Sesi Development)
- ✅ Perbaikan semua path file
- ✅ Perbaikan sistem login (support ID/Username/Nama)
- ✅ Penambahan fitur transaksi lengkap
- ✅ Penambahan fitur barang masuk
- ✅ Penambahan fitur barang keluar
- ✅ Penambahan fitur aktifitas dengan tab
- ✅ Integrasi semua fitur ke halaman Aktifitas
- ✅ Penyesuaian dengan struktur database yang ada
- ✅ Auto-generate ID untuk semua entitas
- ✅ Auto-update stok
- ✅ Auto-log aktifitas

## 👤 Developer

Dikembangkan untuk sistem manajemen toko komputer.

## 📄 License

Proyek internal - All rights reserved.

---

**Selamat menggunakan sistem manajemen toko komputer!** 🎉

