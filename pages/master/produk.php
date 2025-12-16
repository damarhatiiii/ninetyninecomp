<?php 
session_start();
include '../../config/db.php';

// Pastikan user login dulu
if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_PATH . '/auth/login.php');
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
<body class="bg-gray-50">
    <!-- Navbar -->
    <?php include '../../includes/navbar.php'; ?>

    <div class="p-6 min-h-[calc(100vh-80px)] pb-20">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <h2 class="text-2xl font-bold bg-blue-600 text-white px-5 py-4">
                Data Produk
            </h2>
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-700 border-collapse">
                    <thead class="text-xs uppercase bg-gray-100 text-gray-700 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-4 py-2.5 border-r border-gray-200">Kode</th>
                            <th scope="col" class="px-4 py-2.5 border-r border-gray-200">Nama Produk</th>
                            <th scope="col" class="px-4 py-2.5 border-r border-gray-200">Kategori</th>
                            <th scope="col" class="px-4 py-2.5 border-r border-gray-200">Merk</th>
                            <th scope="col" class="px-4 py-2.5 border-r border-gray-200">Spesifikasi</th>
                            <th scope="col" class="px-4 py-2.5 border-r border-gray-200">Stok</th>
                            <th scope="col" class="px-4 py-2.5 border-r border-gray-200">Harga</th>
                            <th scope="col" class="px-4 py-2.5 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr class="bg-white border-b border-gray-200 hover:bg-gray-50 transition-colors">

                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap border-r border-gray-200">
                                <?= htmlspecialchars($row['id_produk']); ?>
                            </td>

                            <td class="px-4 py-3 border-r border-gray-200"><?= htmlspecialchars($row['nama_produk']); ?></td>
                            <td class="px-4 py-3 border-r border-gray-200"><?= htmlspecialchars($row['id_kategori']); ?></td>
                            <td class="px-4 py-3 border-r border-gray-200"><?= htmlspecialchars($row['merk']); ?></td>
                            <td class="px-4 py-3 border-r border-gray-200"><?= htmlspecialchars($row['spesifikasi']); ?></td>
                            <td class="px-4 py-3 border-r border-gray-200"><?= htmlspecialchars($row['stok']); ?></td>

                            <td class="px-4 py-3 text-green-600 font-semibold border-r border-gray-200">
                                Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
                            </td>

                            <!-- KOLOM AKSI -->
                            <td class="px-4 py-3 text-center">

                                <!-- Update Stok -->
                                <a href="../barang/update_stok.php?id=<?= $row['id_produk']; ?>"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                                Update Stok
                                </a>

                                <!-- Hapus -->
                                <a href="hapus_produk.php?id=<?= $row['id_produk']; ?>"
                                onclick="return confirm('Yakin ingin menghapus produk ini?')"
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium ml-2 transition-all duration-200 shadow-sm hover:shadow-md">
                                Hapus
                                </a>

                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../../includes/footbar.php'; ?>
</body>
</html>
