-- ============================================
-- SQL untuk Membuat/Update Tabel Aktifitas
-- ============================================
-- Jalankan script ini di phpMyAdmin atau MySQL client
-- Pastikan database sudah dipilih sebelum menjalankan

-- ============================================
-- 1. Buat Tabel Aktifitas (jika belum ada)
-- ============================================
CREATE TABLE IF NOT EXISTS `aktifitas` (
  `id_aktifitas` INT(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` VARCHAR(255) NOT NULL,
  `jenis_aktifitas` ENUM('barang_masuk', 'barang_keluar', 'transaksi') NOT NULL,
  `keterangan` TEXT NOT NULL,
  `tanggal` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_aktifitas`),
  KEY `idx_id_karyawan` (`id_karyawan`),
  KEY `idx_tanggal` (`tanggal`),
  KEY `idx_jenis_aktifitas` (`jenis_aktifitas`),
  CONSTRAINT `aktifitas_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- 2. Update Tabel Aktifitas (jika sudah ada)
-- ============================================
-- Pastikan kolom tanggal menggunakan DATETIME dan bisa NULL atau DEFAULT CURRENT_TIMESTAMP
ALTER TABLE `aktifitas` 
MODIFY COLUMN `tanggal` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;

-- Pastikan jenis_aktifitas adalah ENUM dengan 3 nilai
ALTER TABLE `aktifitas` 
MODIFY COLUMN `jenis_aktifitas` ENUM('barang_masuk', 'barang_keluar', 'transaksi') NOT NULL;

-- ============================================
-- 3. Tambahkan Index untuk Performa (jika belum ada)
-- ============================================
-- Cek index yang sudah ada
SHOW INDEX FROM `aktifitas`;

-- Tambahkan index jika belum ada
-- CREATE INDEX idx_id_karyawan ON aktifitas(id_karyawan);
-- CREATE INDEX idx_tanggal ON aktifitas(tanggal);
-- CREATE INDEX idx_jenis_aktifitas ON aktifitas(jenis_aktifitas);

-- ============================================
-- 4. Verifikasi Struktur Tabel
-- ============================================
DESCRIBE aktifitas;

-- ============================================
-- 5. Cek Data Aktifitas (Opsional)
-- ============================================
SELECT COUNT(*) as total_aktifitas FROM aktifitas;
SELECT * FROM aktifitas ORDER BY tanggal DESC LIMIT 10;

