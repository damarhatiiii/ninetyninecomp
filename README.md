# ninetyninecomp - Sistem Manajemen Toko

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
- Tambah karyawan baru dengan **auto-generate ID** (format: `KRY001`, `KRY002`, dst.)
- Hapus karyawan
- Role: Admin dan Staf
- Login tracking
- **Sinkronisasi otomatis**: ID karyawan otomatis tersinkron di tabel `aktifitas`, `barang_keluar`, dan `transaksi`

### 4. **Manajemen Customer & Supplier**
- Daftar customer
- Tambah customer baru
- Daftar supplier
- Tambah supplier baru

### 5. **Transaksi Penjualan**
- Form transaksi dengan multi-produk
- Pilih customer (opsional) atau **auto-generate customer baru** jika nama pembeli belum terdaftar
- Auto-calculate total
- Update stok otomatis saat transaksi
- Detail transaksi dengan struk
- Cetak struk
- **Sinkronisasi otomatis**: `id_customer` dan `id_karyawan` otomatis tersinkron di tabel `transaksi`

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
- `id_karyawan` (VARCHAR) - Primary Key, **Auto-generated** (format: `KRY001`, `KRY002`, dst.)
- `nama` (VARCHAR)
- `username` (VARCHAR)
- `password` (VARCHAR) - Hashed dengan `password_hash()` (bcrypt)
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
- `id_customer` (VARCHAR) - Primary Key, **Auto-generated** (format: `CUS001`, `CUS002`, dst.)
- `nama` (TEXT)
- `email` (TEXT)
- **Auto-generate**: Customer baru otomatis dibuat saat transaksi jika nama pembeli belum terdaftar

#### `supplier`
- `id_supplier` (VARCHAR) - Primary Key
- `nama` (VARCHAR)
- `alamat` (TEXT)
- `email` (TEXT)
- `telepon` (INT)

#### `transaksi`
- `id_transaksi` (VARCHAR) - Primary Key, **Auto-generated** (format: `TRX001`, `TRX002`, dst.)
- `tanggal` (DATE)
- `total` (INT)
- `id_customer` (VARCHAR) - Foreign Key, **Auto-generated** jika customer baru
- `nama_pembeli` (VARCHAR) - Nama pembeli (wajib diisi)
- `id_karyawan` (VARCHAR) - Foreign Key, **Auto-sinkron** dari session login

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
- `id_keluar` (VARCHAR) - Primary Key, **Auto-generated** (format: `BK001`, `BK002`, dst.)
- `id_produk` (VARCHAR) - Foreign Key
- `jumlah_keluar` (INT)
- `tanggal` (DATE)
- `id_karyawan` (VARCHAR) - Foreign Key, **Auto-sinkron** dari session login

#### `aktifitas`
- `id_aktifitas` (INT) - Primary Key, Auto Increment
- `id_karyawan` (VARCHAR) - Foreign Key, **Auto-sinkron** dari session login
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
1. **ID Karyawan** (contoh: `KRY001`, `KRY002`, dst.)
2. **Username** (contoh: `admin`)
3. **Nama** (contoh: `Admin`)

**Catatan:**
- Password disimpan dalam format **bcrypt hash** (contoh: `$2y$10$...`) untuk keamanan
- Password akan otomatis di-hash jika masih plain text saat login pertama kali
- Password asli **tidak bisa** dilihat kembali dari database (one-way encryption)

## 📝 Cara Penggunaan

### Menambah Karyawan Baru
1. Login sebagai Admin
2. Klik menu **Karyawan**
3. Klik tombol **+ Tambah Karyawan**
4. Isi form: Nama, Username, Password, Role
5. Simpan
6. **ID Karyawan otomatis ter-generate** (format: `KRY001`, `KRY002`, dst.)
7. Login bisa menggunakan ID, Username, atau Nama

### Menambah Produk
1. Login sebagai Admin/Staf
2. Klik menu **Tambah**
3. Isi form produk
4. Simpan

### Melakukan Transaksi
1. Klik menu **Aktifitas**
2. Klik tombol **+ Transaksi**
3. Masukkan **Nama Pembeli** (wajib)
   - Jika nama sudah terdaftar sebagai customer, akan otomatis ter-link
   - Jika nama belum terdaftar, **customer baru otomatis dibuat** dengan ID `CUS001`, `CUS002`, dst.
4. Pilih produk yang akan dibeli
5. Atur jumlah
6. Klik **Simpan Transaksi**
7. Stok otomatis berkurang
8. **ID Karyawan dan ID Customer otomatis tersinkron** di tabel transaksi

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
- **Karyawan**: `KRY001`, `KRY002`, dst. (saat tambah karyawan baru)
- **Transaksi**: `TRX001`, `TRX002`, dst.
- **Customer**: `CUS001`, `CUS002`, dst. (saat tambah customer atau transaksi dengan pembeli baru)
- **Supplier**: `SUP001`, `SUP002`, dst.
- **Barang Masuk**: `BM001`, `BM002`, dst.
- **Barang Keluar**: `BK001`, `BK002`, dst.
- **Detail Transaksi**: `DTL001`, `DTL002`, dst.

### Auto-Sinkronisasi ID
Sistem otomatis menyinkronkan ID di berbagai tabel:
- **`id_karyawan`**: Otomatis tersinkron di tabel `aktifitas`, `barang_keluar`, dan `transaksi` dari session login
- **`id_customer`**: Otomatis tersinkron di tabel `transaksi` (auto-generate jika customer baru)

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
- Pastikan password di database sudah di-hash dengan format bcrypt (`$2y$10$...`)
- Cek apakah username/ID/nama ada di database
- Pastikan format ID karyawan sesuai (contoh: `KRY001`, bukan angka saja)
- Lihat error log PHP untuk detail error

### Password di Database
- Password disimpan dalam format **bcrypt hash** (contoh: `$2y$10$24HPranGAzz5RZ3/3K3kjuzUfNBIURQwsKJmvEM37jwS1HU9KMzkS`)
- **Password asli tidak bisa dilihat** dari database (one-way encryption)
- Untuk reset password, gunakan form tambah karyawan atau update langsung di database dengan hash baru
- Generate hash baru dengan PHP: `password_hash('password_baru', PASSWORD_DEFAULT)`

### Error: Path tidak ditemukan
- Pastikan struktur folder sesuai dengan dokumentasi
- Cek semua path include sudah benar (menggunakan `../` jika perlu)

## 📅 Changelog

### Update Terbaru - Auto-Generate & Sinkronisasi ID
- ✅ **Auto-generate ID Karyawan**: Format `KRY001`, `KRY002`, dst. saat tambah karyawan baru
- ✅ **Auto-generate ID Customer**: Format `CUS001`, `CUS002`, dst. saat transaksi dengan pembeli baru
- ✅ **Sinkronisasi `id_karyawan`**: Otomatis tersinkron di tabel `aktifitas`, `barang_keluar`, dan `transaksi` dari session login
- ✅ **Sinkronisasi `id_customer`**: Otomatis tersinkron di tabel `transaksi` (auto-generate jika customer baru)
- ✅ Perbaikan sistem login untuk support format ID baru (KRY001, dst.)

### 15 November 2025 (Sesi Development)
- ✅ Perbaikan semua path file
- ✅ Perbaikan sistem login (support ID/Username/Nama)
- ✅ Penambahan fitur transaksi lengkap
- ✅ Penambahan fitur barang masuk
- ✅ Penambahan fitur barang keluar
- ✅ Penambahan fitur aktifitas dengan tab
- ✅ Integrasi semua fitur ke halaman Aktifitas
- ✅ Penyesuaian dengan struktur database yang ada

### 18 November 2025 (Sesi Development)
- ✅ Auto-generate ID untuk semua entitas
- ✅ Auto-update stok
- ✅ Auto-log aktifitas

## 👤 Developer

Dikembangkan oleh damarhatii untuk tugas sistem basis data.

## 📄 License

Proyek internal (Kuliah) - All rights reserved.

---

**Selamat menggunakan sistem manajemen ninetyninecomp!** 🎉

