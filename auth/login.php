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

            <h2 class="text-center text-2xl font-bold mb-6">Login Karyawan</h2>

            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    Username atau password salah!
                </div>
            <?php endif; ?>

            <form action="login_proses.php" method="POST" class="space-y-5">
                <div>
                    <label class="text-sm font-medium">Username</label>
                    <input name="username" type="text" required
                        class="w-full p-2 border rounded mt-1">
                </div>

                <div>
                    <label class="text-sm font-medium">Password</label>
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
