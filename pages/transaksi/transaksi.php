<?php
session_start();
include '../../config/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_PATH . '/auth/login.php');
    exit;
}

// Ambil data transaksi dengan join customer & karyawan (LEFT JOIN agar tetap tampil meski data karyawan/customer hilang)
// Tidak lagi menggunakan kolom t.nama_pembeli agar tetap aman jika kolom tersebut dihapus dari database
$query = "SELECT 
                                t.*, 
                                COALESCE(k.nama, '-') AS nama_karyawan,
                                COALESCE(c.nama, '-') AS nama_pembeli_display,
                                c.id_customer, 
                                c.nama AS nama_customer
                                FROM transaksi t 
                                LEFT JOIN karyawan k ON t.id_karyawan = k.id_karyawan
                                LEFT JOIN customer c ON t.id_customer = c.id_customer
                                ORDER BY t.tanggal DESC";
$result = mysqli_query($conn, $query);
if (!$result && isset($_GET['debug_transaksi'])) {
    die('Error query transaksi: ' . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <body class="bg-gray-50 min-h-screen flex flex-col">
    <?php include '../../includes/navbar.php'; ?>
    <div class="p-6 bg-gray-100 dark:bg-gray-900 min-h-screen pb-20">
        <div class="p-6 flex-grow">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
                <div class="flex items-center justify-between border-b pb-3 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Data Transaksi</h2>
                    <a href="tambah_transaksi.php" 
                        class="inline-block bg-blue-700 hover:bg-blue-800 text-white font-medium px-4 py-2 rounded-lg text-sm">
                        + Transaksi Baru
                    </a>
                </div>

                <div class="relative overflow-x-auto rounded-lg shadow-sm">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-600 dark:text-gray-300">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">ID Transaksi</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Customer</th>
                                <th class="px-6 py-3">Karyawan</th>
                                <th class="px-6 py-3">Total</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)): 
                            ?>
                            <tr class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <td class="px-6 py-4"><?= $no++; ?></td>
                                <td class="px-6 py-4 font-medium"><?= htmlspecialchars($row['id_transaksi']); ?></td>
                                <td class="px-6 py-4"><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                <td class="px-6 py-4">
                                    <?php if (!empty($row['id_customer'])): ?>
                                        <span class="text-blue-600 dark:text-blue-400 font-medium">
                                            <?= htmlspecialchars($row['id_customer']); ?>
                                        </span>
                                        <br>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            <?= htmlspecialchars($row['nama_pembeli_display'] ?? '-'); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-600 dark:text-gray-400">
                                            <?= htmlspecialchars($row['nama_pembeli_display'] ?? '-'); ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['nama_karyawan']); ?></td>
                                <td class="px-6 py-4 font-semibold text-green-600">
                                    Rp <?= number_format($row['total'], 0, ',', '.'); ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="detail_transaksi.php?id=<?= $row['id_transaksi']; ?>" 
                                        class="text-blue-600 hover:underline">Detail</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php include '../../includes/footbar.php'; ?>
</body>
</html>

