<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Toko Komputer</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen items-center justify-center px-4">
        <div class="w-full max-w-sm bg-white shadow-lg rounded-xl p-6">

            <h2 class="text-center text-2xl font-bold mb-6 text-gray-700">Login Karyawan</h2>

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
                    <a href="setup_user.php" class="underline text-blue-600">Buat user admin pertama</a>
                </div>
            <?php endif; ?>

            <form action="login_poses.php" method="POST" class="space-y-5">
                <div>
                    <label class="text-white text-sm font-medium">ID Karyawan / Username / Nama</label>
                    <input name="username" type="text" required
                        placeholder="Masukkan ID, Username, atau Nama"
                        class="w-full p-2 border rounded mt-1">
                    <p class="text-xs text-gray-500 mt-1">Anda bisa login menggunakan ID Karyawan, Username, atau Nama</p>
                </div>

                <div>
                    <label class="text-white text-sm font-medium">Password</label>
                    <input name="password" type="password" required
                        class="w-full p-2 border rounded mt-1">
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>
