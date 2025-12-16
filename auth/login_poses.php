<?php
session_start();
include '../config/db.php';

// Cek koneksi database
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $login_input = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($login_input) || empty($password)) {
        header("Location: login.php?error=1");
        exit;
    }

    // Cek apakah input adalah angka (untuk id_karyawan)
    $is_numeric = is_numeric($login_input);
    
    // Gunakan prepared statement untuk mencari berdasarkan id_karyawan, username, atau nama
    if ($is_numeric) {
        // Jika numeric, cari berdasarkan id_karyawan (integer) atau username/nama (string)
        $stmt = mysqli_prepare($conn, "SELECT * FROM karyawan WHERE id_karyawan = ? OR username = ? OR nama = ?");
        if ($stmt) {
            // Convert ke integer untuk id_karyawan, string untuk yang lain
            $id_karyawan = (int)$login_input;
            mysqli_stmt_bind_param($stmt, "iss", $id_karyawan, $login_input, $login_input);
        }
    } else {
        // Jika bukan numeric, hanya cari berdasarkan username atau nama
        $stmt = mysqli_prepare($conn, "SELECT * FROM karyawan WHERE username = ? OR nama = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $login_input, $login_input);
        }
    }
    
    if (!$stmt) {
        // Debug: tampilkan error SQL
        error_log("SQL Error: " . mysqli_error($conn));
        header("Location: login.php?error=1");
        exit;
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    // Debug: cek apakah data ditemukan
    if (!$data) {
        error_log("Login failed: User not found - Input: " . $login_input);
        header("Location: login.php?error=1");
        exit;
    }

    // Cek password
    // Support kedua format: hash (backward compatibility) dan plain text (default)
    $password_valid = false;
    
    // Cek dengan password_verify (jika di-hash - untuk backward compatibility)
    if (password_verify($password, $data['password'])) {
        $password_valid = true;
    } 
    // Cek plain text (default - tidak di-hash)
    elseif ($data['password'] === $password) {
        $password_valid = true;
    }
    
    if ($password_valid) {
        // Login berhasil
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama']     = $data['nama'];
        $_SESSION['role']     = $data['role'];
        $_SESSION['id_karyawan'] = $data['id_karyawan'];

        $destination = BASE_PATH . '/pages/dashboard.php';
        header("Location: " . ($destination === '' ? '/' : $destination));
        exit;
    } else {
        // Password salah
        error_log("Login failed: Wrong password for user - " . $login_input);
        header("Location: login.php?error=1");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>
