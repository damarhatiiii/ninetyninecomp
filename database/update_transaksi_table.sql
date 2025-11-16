-- ============================================
-- SQL untuk Update Tabel Transaksi
-- ============================================
-- Jalankan script ini di phpMyAdmin atau MySQL client
-- Pastikan database sudah dipilih sebelum menjalankan

-- ============================================
-- 1. Hapus Foreign Key Constraint (jika ada)
-- ============================================
-- Cek dulu nama constraint yang ada
SELECT CONSTRAINT_NAME 
FROM information_schema.KEY_COLUMN_USAGE 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'transaksi' 
AND COLUMN_NAME = 'id_customer'
AND REFERENCED_TABLE_NAME IS NOT NULL;

-- Jika ada constraint (misalnya: transaksi_ibfk_1), hapus dengan:
-- Ganti 'transaksi_ibfk_1' dengan nama constraint yang ditemukan di atas
-- ALTER TABLE transaksi DROP FOREIGN KEY transaksi_ibfk_1;

-- ============================================
-- 2. Ubah kolom id_customer agar bisa NULL
-- ============================================
ALTER TABLE transaksi MODIFY COLUMN id_customer VARCHAR(255) DEFAULT NULL;

-- ============================================
-- 3. Tambahkan kolom nama_pembeli
-- ============================================
-- Cek dulu apakah kolom sudah ada
SELECT COLUMN_NAME 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'transaksi' 
AND COLUMN_NAME = 'nama_pembeli';

-- Jika kolom belum ada, jalankan:
ALTER TABLE transaksi ADD COLUMN nama_pembeli VARCHAR(255) DEFAULT NULL AFTER id_customer;

-- ============================================
-- 4. Verifikasi struktur tabel
-- ============================================
DESCRIBE transaksi;

-- ============================================
-- 5. (Opsional) Re-add Foreign Key jika diperlukan
-- ============================================
-- Jika ingin menambahkan kembali foreign key constraint:
ALTER TABLE transaksi 
ADD CONSTRAINT transaksi_ibfk_1 
FOREIGN KEY (id_customer) REFERENCES customer(id_customer) ON DELETE SET NULL ON UPDATE CASCADE;

