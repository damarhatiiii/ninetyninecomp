<?php
session_start();
include '../../config/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_PATH . '/auth/login.php');
    exit;
}

// Ambil data barang keluar
$result = mysqli_query($conn, "SELECT bk.*, p.nama_produk, k.nama as nama_karyawan
                                FROM barang_keluar bk
                                JOIN produk p ON bk.id_produk = p.id_produk
                                JOIN karyawan k ON bk.id_karyawan = k.id_karyawan
                                ORDER BY bk.tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Keluar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <?php include '../../includes/navbar.php'; ?>
    
    <div class="p-6 bg-gray-50 min-h-screen pb-20">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between border-b border-gray-200 pb-3 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Barang Keluar</h2>
                    <a href="tambah_barang_keluar.php" 
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md">
                        + Barang Keluar Baru
                    </a>
                </div>

                <div class="relative overflow-x-auto rounded-lg shadow-sm border border-gray-200">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-700">
                        <thead class="text-xs uppercase bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Produk</th>
                                <th class="px-6 py-3">Jumlah</th>
                                <th class="px-6 py-3">Karyawan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)): 
                            ?>
                            <tr class="bg-white border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4"><?= $no++; ?></td>
                                <td class="px-6 py-4"><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['nama_produk']); ?></td>
                                <td class="px-6 py-4"><?= $row['jumlah_keluar']; ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['nama_karyawan']); ?></td>
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

