-- ============================================
-- SQL untuk Update Tabel Karyawan
-- ============================================
-- Jalankan script ini di phpMyAdmin atau MySQL client
-- Pastikan database sudah dipilih sebelum menjalankan

-- ============================================
-- 1. Tambahkan Kolom Email (jika belum ada)
-- ============================================
-- Cek apakah kolom email sudah ada
SET @dbname = DATABASE();
SET @tablename = 'karyawan';
SET @columnname = 'email';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' VARCHAR(255) DEFAULT NULL')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- ============================================
-- 2. Tambahkan Kolom Tanggal Dibuat (jika belum ada)
-- ============================================
SET @columnname = 'tanggal_dibuat';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' DATE DEFAULT NULL')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- ============================================
-- 3. Update Tanggal Dibuat untuk Data yang Sudah Ada
-- ============================================
-- Jika tanggal_dibuat masih NULL, set ke tanggal sekarang
UPDATE `karyawan` 
SET `tanggal_dibuat` = CURDATE() 
WHERE `tanggal_dibuat` IS NULL;

-- ============================================
-- 3. Tambahkan Kolom Foto Profil (jika belum ada)
-- ============================================
SET @columnname = 'foto_profil';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' VARCHAR(255) DEFAULT NULL')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- ============================================
-- Catatan:
-- - Kolom email bersifat opsional (bisa NULL)
-- - Kolom tanggal_dibuat akan otomatis terisi untuk data baru
-- - Untuk data lama, tanggal_dibuat akan diisi dengan tanggal saat script dijalankan
-- - Kolom foto_profil menyimpan path/nama file foto profil
-- ============================================

