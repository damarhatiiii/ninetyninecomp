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
$jumlah_keluar = (int)$_POST['jumlah_keluar'];
$keterangan = mysqli_real_escape_string($conn, $_POST['keterangan'] ?? '');

if ($jumlah_keluar <= 0) {
    header("Location: tambah_barang_keluar.php?error=1");
    exit;
}

// Cek stok
$cek_stok = mysqli_query($conn, "SELECT stok, nama_produk FROM produk WHERE id_produk = '$id_produk'");
$produk_data = mysqli_fetch_assoc($cek_stok);

if (!$produk_data || $produk_data['stok'] < $jumlah_keluar) {
    header("Location: tambah_barang_keluar.php?error=2");
    exit;
}

// Generate ID barang keluar
$id_keluar = generateIdKeluar($conn);
$tanggal = date('Y-m-d');

// Insert barang keluar
$stmt = mysqli_prepare($conn, "INSERT INTO barang_keluar (id_keluar, id_produk, jumlah_keluar, tanggal, id_karyawan) VALUES (?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssiss", $id_keluar, $id_produk, $jumlah_keluar, $tanggal, $id_karyawan);

if (mysqli_stmt_execute($stmt)) {
    // Update stok produk
    mysqli_query($conn, "UPDATE produk SET stok = stok - $jumlah_keluar WHERE id_produk = '$id_produk'");
    
    // Insert aktifitas
    $ket_aktifitas = "Mengeluarkan barang: " . $produk_data['nama_produk'] . " sebanyak $jumlah_keluar unit";
    if (!empty($keterangan)) {
        $ket_aktifitas .= " - " . $keterangan;
    }
    $stmt2 = mysqli_prepare($conn, "INSERT INTO aktifitas (id_karyawan, jenis_aktifitas, keterangan, tanggal) VALUES (?, 'barang_keluar', ?, NOW())");
    mysqli_stmt_bind_param($stmt2, "ss", $id_karyawan, $ket_aktifitas);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);
    
    mysqli_stmt_close($stmt);
    header("Location: aktifitas.php?tab=barang_keluar&success=1");
} else {
    header("Location: tambah_barang_keluar.php?error=1");
}
exit;
?>

