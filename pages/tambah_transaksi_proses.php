<?php
session_start();
include '../config/db.php';
include '../config/helper.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit;
}

$id_karyawan = $_SESSION['id_karyawan'];
$id_customer = !empty($_POST['id_customer']) ? mysqli_real_escape_string($conn, $_POST['id_customer']) : '';
$produk_array = $_POST['produk'] ?? [];
$qty_array = $_POST['qty'] ?? [];

if (empty($produk_array)) {
    header("Location: tambah_transaksi.php?error=1");
    exit;
}

// Mulai transaksi
mysqli_begin_transaction($conn);

try {
    // Hitung total
    $total = 0;
    foreach ($produk_array as $id_produk) {
        $qty = (int)($qty_array[$id_produk] ?? 1);
        
        // Cek stok
        $cek_stok = mysqli_query($conn, "SELECT stok, harga FROM produk WHERE id_produk = '$id_produk'");
        $produk_data = mysqli_fetch_assoc($cek_stok);
        
        if (!$produk_data || $produk_data['stok'] < $qty) {
            throw new Exception("Stok produk tidak mencukupi!");
        }
        
        $total += $produk_data['harga'] * $qty;
    }

    // Generate ID transaksi
    $id_transaksi = generateIdTransaksi($conn);
    $tanggal = date('Y-m-d');

    // Insert transaksi (total sebagai int sesuai SQL dump)
    $total_int = (int)$total;
    $stmt = mysqli_prepare($conn, "INSERT INTO transaksi (id_transaksi, tanggal, total, id_customer, id_karyawan) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssiss", $id_transaksi, $tanggal, $total_int, $id_customer, $id_karyawan);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Insert detail transaksi dan update stok
    foreach ($produk_array as $id_produk) {
        $qty = (int)($qty_array[$id_produk] ?? 1);
        
        // Ambil harga
        $produk = mysqli_query($conn, "SELECT harga FROM produk WHERE id_produk = '$id_produk'");
        $produk_data = mysqli_fetch_assoc($produk);
        $subtotal = $produk_data['harga'] * $qty;
        
        // Generate ID detail
        $id_detail = generateIdDetail($conn);
        
        // Insert detail (subtotal sebagai decimal)
        $stmt = mysqli_prepare($conn, "INSERT INTO detail_transaksi (id_detail, id_transaksi, id_produk, jumlah, subtotal) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssid", $id_detail, $id_transaksi, $id_produk, $qty, $subtotal);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        // Update stok produk
        mysqli_query($conn, "UPDATE produk SET stok = stok - $qty WHERE id_produk = '$id_produk'");
        
        // Generate ID barang keluar
        $id_keluar = generateIdKeluar($conn);
        
        // Insert barang keluar
        $stmt = mysqli_prepare($conn, "INSERT INTO barang_keluar (id_keluar, id_produk, jumlah_keluar, tanggal, id_karyawan) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssiss", $id_keluar, $id_produk, $qty, $tanggal, $id_karyawan);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Insert aktifitas
    $keterangan = "Melakukan transaksi penjualan dengan total Rp " . number_format($total, 0, ',', '.');
    $stmt = mysqli_prepare($conn, "INSERT INTO aktifitas (id_karyawan, jenis_aktifitas, keterangan, tanggal) VALUES (?, 'transaksi', ?, NOW())");
    mysqli_stmt_bind_param($stmt, "ss", $id_karyawan, $keterangan);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Commit transaksi
    mysqli_commit($conn);
    
    header("Location: detail_transaksi.php?id=$id_transaksi&success=1&back=aktifitas");
    exit;

} catch (Exception $e) {
    // Rollback jika error
    mysqli_rollback($conn);
    header("Location: tambah_transaksi.php?error=" . urlencode($e->getMessage()));
    exit;
}
?>

