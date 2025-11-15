<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Ambil data customer
$result = mysqli_query($conn, "SELECT * FROM customer ORDER BY nama ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Customer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="p-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="max-w-6xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between border-b pb-3 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Data Customer</h2>
                <button onclick="showAddForm()" 
                    class="inline-block bg-blue-700 hover:bg-blue-800 text-white font-medium px-4 py-2 rounded-lg text-sm">
                    + Tambah Customer
                </button>
            </div>

            <!-- Form Tambah Customer (Hidden) -->
            <div id="addForm" class="hidden mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Tambah Customer Baru</h3>
                <form method="POST" action="tambah_customer_proses.php" class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Nama *</label>
                        <input type="text" name="nama" required
                            class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Email *</label>
                        <input type="email" name="email" required
                            class="w-full p-2 border rounded">
                    </div>
                    <div class="col-span-2 flex gap-2">
                        <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Simpan
                        </button>
                        <button type="button" onclick="hideAddForm()"
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            Batal
                        </button>
                    </div>
                </form>
            </div>

            <div class="relative overflow-x-auto rounded-lg shadow-sm">
                <table class="w-full text-sm text-left rtl:text-right text-gray-600 dark:text-gray-300">
                    <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">Nama</th>
                            <th class="px-6 py-3">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)): 
                        ?>
                        <tr class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <td class="px-6 py-4"><?= $no++; ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($row['id_customer']); ?></td>
                            <td class="px-6 py-4 font-medium"><?= htmlspecialchars($row['nama']); ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($row['email']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include '../includes/footbar.php'; ?>

    <script>
        function showAddForm() {
            document.getElementById('addForm').classList.remove('hidden');
        }
        function hideAddForm() {
            document.getElementById('addForm').classList.add('hidden');
        }
    </script>
</body>
</html>

