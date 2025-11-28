<?php
session_start();
include '../../config/db.php';

// Pastikan user login dulu
if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_PATH . '/auth/login.php');
    exit;
}

// Validasi method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ubah_password.php?error=invalid_method');
    exit;
}

$id_karyawan = $_SESSION['id_karyawan'];
$password_lama = $_POST['password_lama'] ?? '';
$password_baru = $_POST['password_baru'] ?? '';
$password_konfirmasi = $_POST['password_konfirmasi'] ?? '';

// Validasi data tidak kosong
if (empty($password_lama) || empty($password_baru) || empty($password_konfirmasi)) {
    header('Location: ubah_password.php?error=field_kosong');
    exit;
}

// Validasi password baru minimal 6 karakter
if (strlen($password_baru) < 6) {
    header('Location: ubah_password.php?error=password_minimal_6');
    exit;
}

// Validasi password baru dan konfirmasi cocok
if ($password_baru !== $password_konfirmasi) {
    header('Location: ubah_password.php?error=password_tidak_cocok');
    exit;
}

// Ambil data karyawan
$karyawan_query = mysqli_query($conn, "SELECT * FROM karyawan WHERE id_karyawan = '$id_karyawan'");
$karyawan = mysqli_fetch_assoc($karyawan_query);

if (!$karyawan) {
    header('Location: ' . BASE_PATH . '/auth/login.php');
    exit;
}

// Verifikasi password lama
$password_valid = false;

// Cek dengan password_verify (jika di-hash - untuk backward compatibility)
if (password_verify($password_lama, $karyawan['password'])) {
    $password_valid = true;
} 
// Cek plain text (default - tidak di-hash)
elseif ($karyawan['password'] === $password_lama) {
    $password_valid = true;
}

if (!$password_valid) {
    header('Location: ubah_password.php?error=password_lama_salah');
    exit;
}

// Simpan password baru sebagai plain text (tidak di-hash)
// Update password
$stmt = mysqli_prepare($conn, "UPDATE karyawan SET password = ? WHERE id_karyawan = ?");
mysqli_stmt_bind_param($stmt, "ss", $password_baru, $id_karyawan);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header('Location: ubah_password.php?success=1');
} else {
    mysqli_stmt_close($stmt);
    header('Location: ubah_password.php?error=' . urlencode('Gagal mengubah password: ' . mysqli_error($conn)));
}
exit;
?>

