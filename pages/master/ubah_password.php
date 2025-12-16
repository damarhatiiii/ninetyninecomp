<?php
session_start();
include '../../config/db.php';

// Pastikan user login dulu
if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_PATH . '/auth/login.php');
    exit;
}

// Ambil data karyawan yang sedang login
$id_karyawan = $_SESSION['id_karyawan'];
$karyawan_query = mysqli_query($conn, "SELECT * FROM karyawan WHERE id_karyawan = '$id_karyawan'");
$karyawan = mysqli_fetch_assoc($karyawan_query);

if (!$karyawan) {
    header('Location: ' . BASE_PATH . '/auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include '../../includes/navbar.php'; ?>
    
    <div class="p-6 min-h-[calc(100vh-80px)] pb-20">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Ubah Password</h2>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                        <?php
                        $error = $_GET['error'];
                        if ($error == 'password_lama_salah') {
                            echo 'Password lama salah!';
                        } elseif ($error == 'password_tidak_cocok') {
                            echo 'Password baru dan konfirmasi password tidak cocok!';
                        } elseif ($error == 'field_kosong') {
                            echo 'Semua field wajib diisi!';
                        } else {
                            echo htmlspecialchars($error);
                        }
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['success'])): ?>
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                        Password berhasil diubah!
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="ubah_password_proses.php" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Password Lama *</label>
                        <div class="relative">
                            <input type="password" name="password_lama" id="password_lama" required
                                class="w-full p-2.5 border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <button type="button" onclick="togglePassword('password_lama', 'toggle_lama')" 
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <span id="toggle_lama">👁️</span>
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Password Baru *</label>
                        <div class="relative">
                            <input type="password" name="password_baru" id="password_baru" required minlength="6"
                                class="w-full p-2.5 border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <button type="button" onclick="togglePassword('password_baru', 'toggle_baru')" 
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <span id="toggle_baru">👁️</span>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Konfirmasi Password Baru *</label>
                        <div class="relative">
                            <input type="password" name="password_konfirmasi" id="password_konfirmasi" required minlength="6"
                                class="w-full p-2.5 border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <button type="button" onclick="togglePassword('password_konfirmasi', 'toggle_konfirmasi')" 
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <span id="toggle_konfirmasi">👁️</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 pt-4">
                        <button type="submit" 
                            class="flex-1 bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Ubah Password
                        </button>
                        <a href="karyawan.php" 
                            class="flex-1 bg-gray-200 text-gray-800 px-6 py-2.5 rounded-lg hover:bg-gray-300 transition-all duration-200 font-medium text-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../../includes/footbar.php'; ?>
    
    <script>
        function togglePassword(inputId, toggleId) {
            const input = document.getElementById(inputId);
            const toggle = document.getElementById(toggleId);
            
            if (input.type === 'password') {
                input.type = 'text';
                toggle.textContent = '🙈';
            } else {
                input.type = 'password';
                toggle.textContent = '👁️';
            }
        }
    </script>
</body>
</html>

