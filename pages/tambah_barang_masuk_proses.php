<?php
session_start();
include '../config/db.php';
include '../config/helper.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit;
}

$id_karyawan = $_SESSION['id_karyawan'];
$id_produk = mysqli_real_escape_string($conn, $_POST['id_produk']);
$id_supplier = mysqli_real_escape_string($conn, $_POST['id_supplier']);
$jumlah_masuk = (int)$_POST['jumlah_masuk'];

if ($jumlah_masuk <= 0) {
    header("Location: tambah_barang_masuk.php?error=1");
    exit;
}

// Generate ID barang masuk
$id_masuk = generateIdMasuk($conn);
$tanggal = date('Y-m-d');

// Insert barang masuk
$stmt = mysqli_prepare($conn, "INSERT INTO barang_masuk (id_masuk, id_produk, id_supplier, jumlah_masuk, tanggal, id_karyawan) VALUES (?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sssiss", $id_masuk, $id_produk, $id_supplier, $jumlah_masuk, $tanggal, $id_karyawan);

if (mysqli_stmt_execute($stmt)) {
    // Update stok produk
    mysqli_query($conn, "UPDATE produk SET stok = stok + $jumlah_masuk WHERE id_produk = '$id_produk'");
    
    // Ambil nama produk untuk aktifitas
    $produk = mysqli_query($conn, "SELECT nama_produk FROM produk WHERE id_produk = '$id_produk'");
    $produk_data = mysqli_fetch_assoc($produk);
    
    // Insert aktifitas
    $keterangan = "Menerima barang masuk: " . $produk_data['nama_produk'] . " sebanyak $jumlah_masuk unit";
    $stmt2 = mysqli_prepare($conn, "INSERT INTO aktifitas (id_karyawan, jenis_aktifitas, keterangan, tanggal) VALUES (?, 'barang_masuk', ?, NOW())");
    mysqli_stmt_bind_param($stmt2, "ss", $id_karyawan, $keterangan);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);
    
    mysqli_stmt_close($stmt);
    header("Location: aktifitas.php?tab=barang_masuk&success=1");
} else {
    header("Location: tambah_barang_masuk.php?error=1");
}
exit;
?>

