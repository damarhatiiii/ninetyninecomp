<?php 
session_start();
include '../config/db.php';

// Pastikan user login dulu
if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Jalankan query
$query = "SELECT * FROM produk";
$result = mysqli_query($conn, $query);

// Jika query gagal, tampilkan pesan error MySQL
if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Produk</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    </head>
<body>
    <!-- Navbar -->
    <?php include '../includes/navbar.php'; ?>

    <div class="relative overflow-x-auto shadow-md">
        <h2 class="text-2xl font-bold bg-gray-800 text-white p-5">
            Data Produk
        </h2>
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 text-gray-700">
            <tr>
                <th scope="col" class="px-6 py-3">Kode</th>
                <th scope="col" class="px-6 py-3">Nama Produk</th>
                <th scope="col" class="px-6 py-3">Kategori</th>
                <th scope="col" class="px-6 py-3">Merk</th>
                <th scope="col" class="px-6 py-3">Spesifikasi</th>
                <th scope="col" class="px-6 py-3">Stok</th>
                <th scope="col" class="px-6 py-3">Harga</th>
                <th scope="col" class="px-6 py-3 text-center">Aksi</th> <!-- INI POSISI YANG BENAR -->
            </tr>
        </thead>

        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <?= htmlspecialchars($row['id_produk']); ?>
                </td>

                <td class="px-6 py-4"><?= htmlspecialchars($row['nama_produk']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['id_kategori']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['merk']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['spesifikasi']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['stok']); ?></td>

                <td class="px-6 py-4 text-green-600 font-semibold">
                    Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
                </td>

                <!-- KOLOM AKSI -->
                <td class="px-6 py-4 text-center">

                    <!-- Update Stok -->
                    <a href="update_stok.php?id=<?= $row['id_produk']; ?>"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">
                    Update Stok
                    </a>

                    <!-- Hapus -->
                    <a href="hapus_produk.php?id=<?= $row['id_produk']; ?>"
                    onclick="return confirm('Yakin ingin menghapus produk ini?')"
                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs ml-2">
                    Hapus
                    </a>

                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>

    <!-- Footer -->
    <?php include '../includes/footbar.php'; ?>
</body>
</html>
