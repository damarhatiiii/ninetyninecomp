<?php
session_start();

// Proses login jika ada POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    include 'config/db.php';
    
    $login_input = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!empty($login_input) && !empty($password)) {
        // Cari berdasarkan ID karyawan, username, atau nama (semua bertipe string)
        $stmt = mysqli_prepare($conn, "SELECT * FROM karyawan WHERE id_karyawan = ? OR username = ? OR nama = ? LIMIT 1");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $login_input, $login_input, $login_input);
        }
        
        if ($stmt) {
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $data = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            
            $password_match = password_verify($password, $data['password']) || $password === $data['password'];

            if ($data && $password_match) {
                $_SESSION['username'] = $data['username'];
                $_SESSION['nama'] = $data['nama'];
                $_SESSION['role'] = $data['role'];
                $_SESSION['id_karyawan'] = $data['id_karyawan'];
                
                header("Location: pages/dashboard.php");
                exit;
            }
        }
    }
    
    // Jika login gagal
    $error = "ID Karyawan, Username, Nama, atau password salah!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Toko Komputer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen flex-col justify-center px-4 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-center text-2xl font-bold mb-6 text-gray-800">Login Karyawan</h2>

            <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                    Data berhasil disimpan!
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error']) || isset($error)): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    <?= isset($error) ? htmlspecialchars($error) : 'ID Karyawan, Username, Nama, atau password salah!'; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-5">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">ID Karyawan / Username / Nama</label>
                    <input name="username" type="text" required autocomplete="username"
                        placeholder="Masukkan ID, Username, atau Nama"
                        class="w-full p-2.5 border border-gray-300 rounded-lg mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <p class="text-xs text-gray-500 mt-1">Anda bisa login menggunakan ID Karyawan, Username, atau Nama</p>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input name="password" type="password" required autocomplete="current-password"
                        class="w-full p-2.5 border border-gray-300 rounded-lg mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <button name="login" type="submit"
                    class="w-full bg-blue-600 text-white py-2.5 rounded-lg hover:bg-blue-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Login
                </button>
            </form>
        </div>
    </div>
    </div>
</body>
</html>

