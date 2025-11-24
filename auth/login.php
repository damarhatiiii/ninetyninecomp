<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Komputer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen items-center justify-center px-4">
        <div class="w-full max-w-sm bg-white shadow-lg rounded-xl p-6">

            <h2 class="text-center text-2xl font-bold mb-6 text-gray-800">Login Karyawan</h2>

            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    ID Karyawan, Username, Nama, atau password salah!
                </div>
            <?php endif; ?>
            
            <?php
            // Cek apakah ada user di database
            include '../config/db.php';
            $check = mysqli_query($conn, "SELECT COUNT(*) as total FROM karyawan");
            $row = mysqli_fetch_assoc($check);
            if ($row['total'] == 0): ?>
                <div class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4">
                    <strong>Belum ada user!</strong> 
                    <a href="setup_user.php" class="underline text-blue-700">Buat user admin pertama</a>
                </div>
            <?php endif; ?>

            <form action="login_poses.php" method="POST" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID Karyawan / Username / Nama</label>
                    <input name="username" type="text" required
                        placeholder="Masukkan ID, Username, atau Nama"
                        class="w-full p-2 border border-gray-300 rounded-md mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Anda bisa login menggunakan ID Karyawan, Username, atau Nama</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input name="password" type="password" required
                        class="w-full p-2 border border-gray-300 rounded-md mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">
                </div>

                <button type="submit"
                    class="w-full bg-blue-700 text-white py-2 rounded-md hover:bg-blue-800 transition-colors font-medium">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>
