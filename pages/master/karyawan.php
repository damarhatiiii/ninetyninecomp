<?php
session_start();
include '../../config/db.php';

// Pastikan user login dulu
if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_PATH . '/auth/login.php');
    exit;
}

// Ambil data karyawan dari database
$result = mysqli_query($conn, "SELECT * FROM karyawan");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>

<body class="bg-gray-50">
    <!-- Navbar -->
    <?php include '../../includes/navbar.php'; ?>

    <div class="p-6 min-h-[calc(100vh-80px)] pb-20">

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

            <!-- HEADER -->
            <div class="flex items-center justify-between bg-blue-600 text-white px-5 py-4">
                <h2 class="text-2xl font-bold">Data Karyawan</h2>

            </div>

            <!-- NOTIFIKASI -->
            <div class="px-5 pt-4">
                <?php if (isset($_GET['error']) && $_GET['error'] == 'akses_ditolak'): ?>
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                        Akses ditolak! Hanya admin yang dapat menambahkan atau menghapus karyawan.
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['msg']) && $_GET['msg'] == 'sukses'): ?>
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                        Karyawan berhasil ditambahkan!
                        <?php if (isset($_GET['id'])): ?>
                            ID: <strong><?= htmlspecialchars($_GET['id']); ?></strong>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- TABEL -->
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-700 border-collapse">
                    <thead class="text-xs uppercase bg-gray-100 text-gray-700 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-2.5 border-r border-gray-200">No</th>
                            <th class="px-4 py-2.5 border-r border-gray-200">Nama</th>
                            <th class="px-4 py-2.5 border-r border-gray-200">Username</th>
                            <th class="px-4 py-2.5 border-r border-gray-200">Role</th>
                            <th class="px-4 py-2.5 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="bg-white border-b border-gray-200 hover:bg-gray-50 transition">

                            <td class="px-4 py-3 font-medium text-gray-900 border-r border-gray-200">
                                <?= $no++; ?>
                            </td>

                            <td class="px-4 py-3 border-r border-gray-200">
                                <?= htmlspecialchars($row['nama']); ?>
                            </td>

                            <td class="px-4 py-3 border-r border-gray-200">
                                <?= htmlspecialchars($row['username']); ?>
                            </td>

                            <td class="px-4 py-3 capitalize border-r border-gray-200">
                                <?= htmlspecialchars($row['role']); ?>
                            </td>

                            <td class="px-4 py-3 text-center">
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                                <a href="hapus_karyawan.php?id=<?= $row['id_karyawan']; ?>"
                                    onclick="return confirm('Yakin ingin menghapus karyawan ini?')"
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm hover:shadow-md">
                                    Hapus
                                </a>
                                <?php else: ?>
                                <span class="text-gray-400 text-sm">-</span>
                                <?php endif; ?>
                            </td>

                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <?php include '../../includes/footbar.php'; ?>
</body>
</html>
