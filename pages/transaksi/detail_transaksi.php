<?php
session_start();
include '../../config/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_PATH . '/auth/login.php');
    exit;
}

$id_transaksi = mysqli_real_escape_string($conn, $_GET['id']);

// Cek apakah tabel transaksi ada
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'transaksi'");
if (mysqli_num_rows($table_check) == 0) {
    die("<div style='padding:20px;'><h2>Error: Tabel belum dibuat!</h2><p>Silakan jalankan SQL di file <code>database/create_tables.sql</code> di phpMyAdmin terlebih dahulu.</p></div>");
}

// Ambil data transaksi dengan prepared statement
$stmt = mysqli_prepare($conn, "SELECT t.*, c.nama as nama_customer, k.nama as nama_karyawan 
                                FROM transaksi t 
                                LEFT JOIN customer c ON t.id_customer = c.id_customer
                                JOIN karyawan k ON t.id_karyawan = k.id_karyawan
                                WHERE t.id_transaksi = ?");
if (!$stmt) {
    die("<div style='padding:20px;'><h2>Error SQL:</h2><p>" . mysqli_error($conn) . "</p><p>Pastikan semua tabel sudah dibuat dengan menjalankan SQL di <code>database/create_tables.sql</code></p></div>");
}
mysqli_stmt_bind_param($stmt, "s", $id_transaksi);
mysqli_stmt_execute($stmt);
$transaksi_result = mysqli_stmt_get_result($stmt);
$t = mysqli_fetch_assoc($transaksi_result);
mysqli_stmt_close($stmt);

if (!$t) {
    $back = isset($_GET['back']) ? $_GET['back'] : 'transaksi';
    if ($back == 'aktifitas') {
        header('Location: ' . BASE_PATH . '/pages/aktifitas.php?tab=transaksi');
    } else {
        header("Location: transaksi.php");
    }
    exit;
}

// Ambil detail transaksi dengan prepared statement
$stmt2 = mysqli_prepare($conn, "SELECT dt.*, p.nama_produk 
                                FROM detail_transaksi dt
                                JOIN produk p ON dt.id_produk = p.id_produk
                                WHERE dt.id_transaksi = ?");
if (!$stmt2) {
    die("Error: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt2, "s", $id_transaksi);
mysqli_stmt_execute($stmt2);
$detail = mysqli_stmt_get_result($stmt2);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white">
    <?php include '../../includes/navbar.php'; ?>
    
    <div class="p-6 pb-20">
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Detail Transaksi #<?= $id_transaksi; ?></h2>
                <?php 
                $back_url = (isset($_GET['back']) && $_GET['back'] == 'aktifitas')
                    ? BASE_PATH . '/pages/aktifitas.php?tab=transaksi'
                    : BASE_PATH . '/pages/transaksi/transaksi.php';
                ?>
                <a href="<?= $back_url; ?>" class="text-blue-600 hover:underline">← Kembali</a>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                    Transaksi berhasil disimpan!
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-500">Tanggal</p>
                    <p class="font-semibold"><?= date('d/m/Y', strtotime($t['tanggal'])); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Karyawan</p>
                    <p class="font-semibold"><?= htmlspecialchars($t['nama_karyawan']); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Nama Pembeli</p>
                    <p class="font-semibold"><?= htmlspecialchars($t['nama_pembeli'] ?? 'Umum'); ?></p>
                </div>
            </div>

            <h3 class="text-lg font-semibold mb-3">Detail Produk</h3>
            <table class="w-full border-collapse border border-gray-300 mb-4">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 p-2 text-left">Produk</th>
                        <th class="border border-gray-300 p-2 text-center">Jumlah</th>
                        <th class="border border-gray-300 p-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($d = mysqli_fetch_assoc($detail)): ?>
                    <tr>
                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($d['nama_produk']); ?></td>
                        <td class="border border-gray-300 p-2 text-center"><?= $d['jumlah']; ?></td>
                        <td class="border border-gray-300 p-2 text-right">
                            Rp <?= number_format($d['subtotal'], 0, ',', '.'); ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 font-bold">
                        <td colspan="2" class="border border-gray-300 p-2 text-right">TOTAL:</td>
                        <td class="border border-gray-300 p-2 text-right text-green-600">
                            Rp <?= number_format($t['total'], 0, ',', '.'); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <div class="flex gap-2">
                <a href="cetak_struk.php?id=<?= $id_transaksi; ?>" target="_blank" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">
                    Cetak Struk
                </a>
                <a href="<?= $back_url; ?>" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <?php 
    mysqli_stmt_close($stmt2);
    include '../../includes/footbar.php'; 
    ?>
</body>
</html>

