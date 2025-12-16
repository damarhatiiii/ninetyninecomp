<?php
session_start();

// Proses login jika ada POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    include 'config/db.php';
    
    $login_input = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!empty($login_input) && !empty($password)) {
        // id_karyawan adalah VARCHAR (format: KRY001), jadi semua input adalah string
        // Cari berdasarkan id_karyawan, username, atau nama (semua bertipe string)
        $stmt = mysqli_prepare($conn, "SELECT * FROM karyawan WHERE id_karyawan = ? OR username = ? OR nama = ? LIMIT 1");
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $login_input, $login_input, $login_input);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $data = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            
            // Cek apakah data ditemukan dan password cocok
            if ($data) {
                // Cek password dengan password_verify (jika di-hash) atau plain text (untuk kompatibilitas)
                $password_match = false;
                
                if (password_verify($password, $data['password'])) {
                    $password_match = true;
                } elseif ($data['password'] === $password) {
                    // Fallback: cek plain text (untuk data lama yang belum di-hash)
                    $password_match = true;
                }
                
                if ($password_match) {
                    $_SESSION['username'] = $data['username'];
                    $_SESSION['nama'] = $data['nama'];
                    $_SESSION['role'] = $data['role'];
                    $_SESSION['id_karyawan'] = $data['id_karyawan'];
                    
                    header("Location: pages/dashboard.php");
                    exit;
                }
            }
        } else {
            // Jika prepared statement gagal, log error untuk debugging
            error_log("Login error: " . mysqli_error($conn));
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

            <?php 
            // Cek apakah ada user di database
            if (!isset($conn)) {
                include 'config/db.php';
            }
            $check = mysqli_query($conn, "SELECT COUNT(*) as total FROM karyawan");
            $row = mysqli_fetch_assoc($check);
            if ($row['total'] == 0): ?>
                <div class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4">
                    <strong>Belum ada user di database!</strong> 
                    <p class="text-sm mt-1">Silakan buat user pertama melalui phpMyAdmin atau hubungi administrator.</p>
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

