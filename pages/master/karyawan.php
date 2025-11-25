<?php
session_start();
include '../../config/db.php';

// Pastikan user login dulu
if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_PATH . '/auth/login.php');
    exit;
}

// Ambil data karyawan dari database
$result = mysqli_query($conn, "SELECT * FROM karyawan ORDER BY id_karyawan ASC");

// Cek jika query gagal
if (!$result) {
    die("Error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <!-- Navbar -->
    <?php include '../../includes/navbar.php'; ?>
    
    <div class="p-6 bg-gray-100 dark:bg-gray-900 min-h-screen pb-20">
    <div class="max-w-5xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between border-b pb-3 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Data Karyawan</h2>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <a href="tambah_karyawan.php" 
                class="inline-block bg-blue-700 hover:bg-blue-800 text-white font-medium px-4 py-2 rounded-lg text-sm focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
                + Tambah Karyawan
            </a>
            <?php endif; ?>
        </div>

        <?php if (isset($_GET['error']) && $_GET['error'] == 'akses_ditolak'): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                Akses ditolak! Hanya admin yang dapat menambahkan atau menghapus karyawan.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'sukses'): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                Karyawan berhasil ditambahkan! <?php if (isset($_GET['id'])): ?>ID: <strong><?= htmlspecialchars($_GET['id']); ?></strong><?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="relative overflow-x-auto rounded-lg shadow-sm">
            <table class="w-full text-sm text-left rtl:text-right text-gray-600 dark:text-gray-300">
                <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Nama</th>
                        <th scope="col" class="px-6 py-3">Username</th>
                        <th scope="col" class="px-6 py-3">Role</th>
                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)): 
                    ?>
                    <tr class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white"><?php echo $no++; ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($row['username']); ?></td>
                        <td class="px-6 py-4 capitalize"><?php echo htmlspecialchars($row['role']); ?></td>
                        <td class="px-6 py-4 text-center">
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                            <a href="hapus_karyawan.php?id=<?php echo $row['id_karyawan']; ?>" 
                                class="font-medium text-red-600 dark:text-red-400 hover:underline"
                                onclick="return confirm('Yakin ingin menghapus karyawan ini?')">Hapus</a>
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
