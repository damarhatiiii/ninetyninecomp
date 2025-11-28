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

// Cek apakah password ter-hash atau plain text
$is_password_hashed = (strpos($karyawan['password'], '$2y$') === 0 || 
                       strpos($karyawan['password'], '$2a$') === 0 || 
                       strpos($karyawan['password'], '$2b$') === 0);

// Cek apakah kolom email sudah ada, jika belum tambahkan
$check_email = mysqli_query($conn, "SHOW COLUMNS FROM karyawan LIKE 'email'");
if (mysqli_num_rows($check_email) == 0) {
    mysqli_query($conn, "ALTER TABLE karyawan ADD COLUMN email VARCHAR(255) DEFAULT NULL");
}

// Cek apakah kolom tanggal_dibuat sudah ada, jika belum tambahkan
$check_tanggal = mysqli_query($conn, "SHOW COLUMNS FROM karyawan LIKE 'tanggal_dibuat'");
if (mysqli_num_rows($check_tanggal) == 0) {
    mysqli_query($conn, "ALTER TABLE karyawan ADD COLUMN tanggal_dibuat DATE DEFAULT NULL");
    // Set tanggal_dibuat untuk data yang sudah ada
    mysqli_query($conn, "UPDATE karyawan SET tanggal_dibuat = CURDATE() WHERE tanggal_dibuat IS NULL");
}

// Ambil ulang data setelah mungkin ada perubahan struktur tabel
$karyawan_query = mysqli_query($conn, "SELECT * FROM karyawan WHERE id_karyawan = '$id_karyawan'");
$karyawan = mysqli_fetch_assoc($karyawan_query);

// Format email (jika tidak ada di database, gunakan username@example.com)
$email = !empty($karyawan['email']) ? $karyawan['email'] : $karyawan['username'] . '@example.com';

// Format tanggal dibuat (jika tidak ada, gunakan tanggal sekarang)
$tanggal_dibuat = !empty($karyawan['tanggal_dibuat']) ? $karyawan['tanggal_dibuat'] : date('Y-m-d');

// Format role
$role_display = ucfirst($karyawan['role']);
$status_akun = 'Aktif'; // Default status

// Cek apakah password ter-hash atau plain text
$is_password_hashed = (strpos($karyawan['password'], '$2y$') === 0 || 
                       strpos($karyawan['password'], '$2a$') === 0 || 
                       strpos($karyawan['password'], '$2b$') === 0);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include '../../includes/navbar.php'; ?>
    
    <div class="p-6 min-h-[calc(100vh-80px)] pb-20">
        <div class="max-w-2xl mx-auto">
            <!-- Header Profil -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-6">
                <div class="flex flex-col items-center text-center">
                    <!-- Foto Profil -->
                    <div class="mb-4">
                        <?php 
                        // Cek apakah kolom foto_profil sudah ada
                        $check_foto = mysqli_query($conn, "SHOW COLUMNS FROM karyawan LIKE 'foto_profil'");
                        if (mysqli_num_rows($check_foto) == 0) {
                            mysqli_query($conn, "ALTER TABLE karyawan ADD COLUMN foto_profil VARCHAR(255) DEFAULT NULL");
                            // Ambil ulang data setelah mungkin ada perubahan struktur tabel
                            $karyawan_query = mysqli_query($conn, "SELECT * FROM karyawan WHERE id_karyawan = '$id_karyawan'");
                            $karyawan = mysqli_fetch_assoc($karyawan_query);
                        }
                        
                        $foto_profil = !empty($karyawan['foto_profil']) ? BASE_PATH . '/assets/profiles/' . $karyawan['foto_profil'] : '';
                        if ($foto_profil && file_exists('../../assets/profiles/' . $karyawan['foto_profil'])): 
                        ?>
                            <img src="<?= $foto_profil; ?>" alt="Foto Profil" 
                                class="w-[120px] h-[120px] rounded-full object-cover border-4 border-white shadow-lg mx-auto">
                        <?php else: ?>
                            <div class="w-[120px] h-[120px] rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-5xl font-bold shadow-lg mx-auto">
                                <?= strtoupper(substr($karyawan['nama'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Nama dan Username -->
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">
                        <?= htmlspecialchars($karyawan['nama']); ?>
                    </h2>
                    <p class="text-gray-600 mb-1">
                        <span class="font-medium">@<?= htmlspecialchars($karyawan['username']); ?></span>
                    </p>
                    
                    <!-- Email -->
                    <p class="text-gray-500 text-sm">
                        <?= htmlspecialchars($email); ?>
                    </p>
                </div>
            </div>

            <!-- Info Akun -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="text-2xl mr-2"></span>
                    Info Akun
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Nama lengkap</span>
                        <span class="text-gray-900 font-medium"><?= htmlspecialchars($karyawan['nama']); ?></span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Tanggal dibuat</span>
                        <span class="text-gray-900 font-medium"><?= date('d/m/Y', strtotime($tanggal_dibuat)); ?></span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Role</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium <?= $karyawan['role'] == 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'; ?>">
                            <?= $role_display; ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Status akun</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">
                            <?= $status_akun; ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600">Password</span>
                        <div class="flex items-center gap-2">
                            <input type="password" id="passwordDisplay" value="••••••••" readonly 
                                class="text-gray-900 font-medium border-none bg-transparent focus:outline-none <?= $is_password_hashed ? 'w-48' : 'w-24'; ?> text-right">
                            <button type="button" onclick="togglePassword()" 
                                class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                <span id="toggleText">Lihat</span>
                            </button>
                        </div>
                    </div>
                    <?php if ($is_password_hashed): ?>
                    <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-700">
                        ⚠️ Password masih ter-hash. Silakan <a href="ubah_password.php" class="underline font-semibold">ubah password</a> untuk menyimpan sebagai plain text.
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Pengaturan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="text-2xl mr-2"></span>
                    Pengaturan
                </h3>
                <div class="space-y-2">
                    <a href="edit_profil.php" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                        <span class="text-gray-700 group-hover:text-gray-900">Ubah profil</span>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="ubah_password.php" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                        <span class="text-gray-700 group-hover:text-gray-900">Ubah password</span>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Logout -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <a href="<?= BASE_PATH; ?>/auth/logout.php" 
                    class="flex items-center justify-center w-full p-3 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors font-medium group">
                    <span class="text-2xl mr-2"></span>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>

    <?php include '../../includes/footbar.php'; ?>
    
    <script>
        let passwordVisible = false;
        const actualPassword = '<?= htmlspecialchars($karyawan['password'], ENT_QUOTES); ?>';
        const isPasswordHashed = <?= $is_password_hashed ? 'true' : 'false'; ?>;
        
        function togglePassword() {
            const passwordDisplay = document.getElementById('passwordDisplay');
            const toggleText = document.getElementById('toggleText');
            
            if (passwordVisible) {
                passwordDisplay.type = 'password';
                passwordDisplay.value = '••••••••';
                toggleText.textContent = 'Lihat';
                passwordVisible = false;
            } else {
                // Tampilkan password sebagai plain text
                passwordDisplay.type = 'text';
                if (isPasswordHashed) {
                    // Jika password ter-hash, tampilkan pesan
                    passwordDisplay.value = '[Password ter-hash - tidak bisa ditampilkan]';
                    alert('Password ini masih ter-hash. Silakan ubah password untuk menyimpan sebagai plain text.');
                } else {
                    // Password plain text, tampilkan langsung
                    passwordDisplay.value = actualPassword;
                }
                toggleText.textContent = 'Sembunyikan';
                passwordVisible = true;
            }
        }
    </script>
</body>
</html>
