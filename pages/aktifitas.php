<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Ambil data transaksi
$transaksi_result = mysqli_query($conn, "SELECT t.*, c.nama as nama_customer, k.nama as nama_karyawan 
                                FROM transaksi t 
                                LEFT JOIN customer c ON t.id_customer = c.id_customer
                                JOIN karyawan k ON t.id_karyawan = k.id_karyawan
                                ORDER BY t.tanggal DESC");

// Ambil data barang masuk
$barang_masuk_result = mysqli_query($conn, "SELECT bm.*, p.nama_produk, s.nama as nama_supplier, k.nama as nama_karyawan
                                FROM barang_masuk bm
                                JOIN produk p ON bm.id_produk = p.id_produk
                                JOIN supplier s ON bm.id_supplier = s.id_supplier
                                JOIN karyawan k ON bm.id_karyawan = k.id_karyawan
                                ORDER BY bm.tanggal DESC");

// Ambil data barang keluar
$barang_keluar_result = mysqli_query($conn, "SELECT bk.*, p.nama_produk, k.nama as nama_karyawan
                                FROM barang_keluar bk
                                JOIN produk p ON bk.id_produk = p.id_produk
                                JOIN karyawan k ON bk.id_karyawan = k.id_karyawan
                                ORDER BY bk.tanggal DESC");

// Ambil data aktifitas log
$aktifitas_result = mysqli_query($conn, "SELECT a.*, k.nama as nama_karyawan
                                FROM aktifitas a
                                JOIN karyawan k ON a.id_karyawan = k.id_karyawan
                                ORDER BY a.tanggal DESC
                                LIMIT 100");

// Tab aktif
$active_tab = $_GET['tab'] ?? 'transaksi';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktifitas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="p-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
                <div class="flex items-center justify-between border-b pb-3 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Aktifitas</h2>
                    <div class="flex gap-2">
                        <a href="tambah_transaksi.php" 
                            class="inline-block bg-blue-700 hover:bg-blue-800 text-white font-medium px-4 py-2 rounded-lg text-sm">
                            + Transaksi
                        </a>
                        <a href="tambah_barang_masuk.php" 
                            class="inline-block bg-green-700 hover:bg-green-800 text-white font-medium px-4 py-2 rounded-lg text-sm">
                            + Barang Masuk
                        </a>
                        <a href="tambah_barang_keluar.php" 
                            class="inline-block bg-orange-700 hover:bg-orange-800 text-white font-medium px-4 py-2 rounded-lg text-sm">
                            + Barang Keluar
                        </a>
                    </div>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                        Data berhasil disimpan!
                    </div>
                <?php endif; ?>

                <!-- Tabs -->
                <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                        <li class="me-2">
                            <a href="?tab=transaksi" 
                                class="inline-block p-4 border-b-2 rounded-t-lg <?= $active_tab == 'transaksi' ? 'text-blue-600 border-blue-600' : 'text-gray-500 border-transparent hover:text-gray-600 hover:border-gray-300'; ?>">
                                Transaksi
                            </a>
                        </li>
                        <li class="me-2">
                            <a href="?tab=barang_masuk" 
                                class="inline-block p-4 border-b-2 rounded-t-lg <?= $active_tab == 'barang_masuk' ? 'text-blue-600 border-blue-600' : 'text-gray-500 border-transparent hover:text-gray-600 hover:border-gray-300'; ?>">
                                Barang Masuk
                            </a>
                        </li>
                        <li class="me-2">
                            <a href="?tab=barang_keluar" 
                                class="inline-block p-4 border-b-2 rounded-t-lg <?= $active_tab == 'barang_keluar' ? 'text-blue-600 border-blue-600' : 'text-gray-500 border-transparent hover:text-gray-600 hover:border-gray-300'; ?>">
                                Barang Keluar
                            </a>
                        </li>
                        <li class="me-2">
                            <a href="?tab=log" 
                                class="inline-block p-4 border-b-2 rounded-t-lg <?= $active_tab == 'log' ? 'text-blue-600 border-blue-600' : 'text-gray-500 border-transparent hover:text-gray-600 hover:border-gray-300'; ?>">
                                Log Aktifitas
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content: Transaksi -->
                <?php if ($active_tab == 'transaksi'): ?>
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
                            mysqli_data_seek($transaksi_result, 0);
                            while ($row = mysqli_fetch_assoc($transaksi_result)): 
                            ?>
                            <tr class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <td class="px-6 py-4"><?= $no++; ?></td>
                                <td class="px-6 py-4 font-medium"><?= htmlspecialchars($row['id_transaksi']); ?></td>
                                <td class="px-6 py-4"><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['nama_customer'] ?? 'Umum'); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['nama_karyawan']); ?></td>
                                <td class="px-6 py-4 font-semibold text-green-600">
                                    Rp <?= number_format($row['total'], 0, ',', '.'); ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="detail_transaksi.php?id=<?= $row['id_transaksi']; ?>&back=aktifitas" 
                                        class="text-blue-600 hover:underline">Detail</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

                <!-- Tab Content: Barang Masuk -->
                <?php if ($active_tab == 'barang_masuk'): ?>
                <div class="relative overflow-x-auto rounded-lg shadow-sm">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-600 dark:text-gray-300">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">ID</th>
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
                            mysqli_data_seek($barang_masuk_result, 0);
                            while ($row = mysqli_fetch_assoc($barang_masuk_result)): 
                            ?>
                            <tr class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <td class="px-6 py-4"><?= $no++; ?></td>
                                <td class="px-6 py-4 font-medium"><?= htmlspecialchars($row['id_masuk']); ?></td>
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
                <?php endif; ?>

                <!-- Tab Content: Barang Keluar -->
                <?php if ($active_tab == 'barang_keluar'): ?>
                <div class="relative overflow-x-auto rounded-lg shadow-sm">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-600 dark:text-gray-300">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">ID</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Produk</th>
                                <th class="px-6 py-3">Jumlah</th>
                                <th class="px-6 py-3">Karyawan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            mysqli_data_seek($barang_keluar_result, 0);
                            while ($row = mysqli_fetch_assoc($barang_keluar_result)): 
                            ?>
                            <tr class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <td class="px-6 py-4"><?= $no++; ?></td>
                                <td class="px-6 py-4 font-medium"><?= htmlspecialchars($row['id_keluar']); ?></td>
                                <td class="px-6 py-4"><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['nama_produk']); ?></td>
                                <td class="px-6 py-4"><?= $row['jumlah_keluar']; ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['nama_karyawan']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

                <!-- Tab Content: Log Aktifitas -->
                <?php if ($active_tab == 'log'): ?>
                <div class="relative overflow-x-auto rounded-lg shadow-sm">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-600 dark:text-gray-300">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Karyawan</th>
                                <th class="px-6 py-3">Jenis</th>
                                <th class="px-6 py-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            mysqli_data_seek($aktifitas_result, 0);
                            while ($row = mysqli_fetch_assoc($aktifitas_result)): 
                                $badge_color = '';
                                switch($row['jenis_aktifitas']) {
                                    case 'barang_masuk':
                                        $badge_color = 'bg-green-100 text-green-800';
                                        break;
                                    case 'barang_keluar':
                                        $badge_color = 'bg-orange-100 text-orange-800';
                                        break;
                                    case 'transaksi':
                                        $badge_color = 'bg-blue-100 text-blue-800';
                                        break;
                                }
                            ?>
                            <tr class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <td class="px-6 py-4"><?= $no++; ?></td>
                                <td class="px-6 py-4"><?= date('d/m/Y H:i', strtotime($row['tanggal'])); ?></td>
                                <td class="px-6 py-4 font-medium"><?= htmlspecialchars($row['nama_karyawan']); ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded text-xs font-semibold <?= $badge_color; ?>">
                                        <?= ucfirst(str_replace('_', ' ', $row['jenis_aktifitas'])); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['keterangan']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include '../includes/footbar.php'; ?>
</body>
</html>
