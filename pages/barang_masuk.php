<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Ambil data barang masuk
$result = mysqli_query($conn, "SELECT bm.*, p.nama_produk, s.nama as nama_supplier, k.nama as nama_karyawan
                                FROM barang_masuk bm
                                JOIN produk p ON bm.id_produk = p.id_produk
                                JOIN supplier s ON bm.id_supplier = s.id_supplier
                                JOIN karyawan k ON bm.id_karyawan = k.id_karyawan
                                ORDER BY bm.tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Masuk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="p-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
                <div class="flex items-center justify-between border-b pb-3 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Barang Masuk</h2>
                    <a href="tambah_barang_masuk.php" 
                        class="inline-block bg-blue-700 hover:bg-blue-800 text-white font-medium px-4 py-2 rounded-lg text-sm">
                        + Barang Masuk Baru
                    </a>
                </div>

                <div class="relative overflow-x-auto rounded-lg shadow-sm">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-600 dark:text-gray-300">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Produk</th>
                                <th class="px-6 py-3">Supplier</th>
                                <th class="px-6 py-3">Jumlah</th>
                                <th class="px-6 py-3">Karyawan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)): 
                            ?>
                            <tr class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <td class="px-6 py-4"><?= $no++; ?></td>
                                <td class="px-6 py-4"><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['nama_produk']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['nama_supplier']); ?></td>
                                <td class="px-6 py-4"><?= $row['jumlah_masuk']; ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['nama_karyawan']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footbar.php'; ?>
</body>
</html>

