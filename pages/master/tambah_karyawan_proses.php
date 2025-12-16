<?php
session_start();
include '../../config/db.php';
include '../../config/helper.php';

// Pastikan user login dulu
if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_PATH . '/auth/login.php');
    exit;
}

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ' . BASE_PATH . '/pages/master/karyawan.php?error=akses_ditolak');
    exit;
}

// Validasi input
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_PATH . '/pages/master/karyawan.php?error=invalid_method');
    exit;
}

$nama = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'staf';

// Validasi data tidak kosong
if (empty($nama) || empty($username) || empty($password)) {
    header('Location: ' . BASE_PATH . '/pages/master/tambah_karyawan.php?error=field_kosong');
    exit;
}

// Validasi role
if (!in_array($role, ['admin', 'staf'])) {
    $role = 'staf';
}

// Cek apakah username sudah ada
$cek_username = mysqli_prepare($conn, "SELECT id_karyawan FROM karyawan WHERE username = ?");
mysqli_stmt_bind_param($cek_username, "s", $username);
mysqli_stmt_execute($cek_username);
$result_cek = mysqli_stmt_get_result($cek_username);

if (mysqli_num_rows($result_cek) > 0) {
    mysqli_stmt_close($cek_username);
    header('Location: ' . BASE_PATH . '/pages/master/tambah_karyawan.php?error=username_ada');
    exit;
}
mysqli_stmt_close($cek_username);

// Generate ID karyawan otomatis
$id_karyawan = generateIdKaryawan($conn);

// Simpan password sebagai plain text (tidak di-hash)
// Insert ke database menggunakan prepared statement
$stmt = mysqli_prepare($conn, "INSERT INTO karyawan (id_karyawan, nama, username, password, role) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    die("Error preparing statement: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "sssss", $id_karyawan, $nama, $username, $password, $role);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: karyawan.php?msg=sukses&id=" . $id_karyawan);
    exit;
} else {
    $error = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);
    header('Location: ' . BASE_PATH . '/pages/master/tambah_karyawan.php?error=query_gagal&detail=' . urlencode($error));
    exit;
}
