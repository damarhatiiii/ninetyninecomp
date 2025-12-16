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

// Format email (jika tidak ada di database, gunakan username@example.com)
$email = !empty($karyawan['email']) ? $karyawan['email'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include '../../includes/navbar.php'; ?>
    
    <div class="p-6 min-h-[calc(100vh-80px)] pb-20">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Profil</h2>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                        <?php
                        $error = $_GET['error'];
                        if ($error == 'username_ada') {
                            echo 'Username sudah digunakan!';
                        } elseif ($error == 'field_kosong') {
                            echo 'Semua field wajib diisi!';
                        } elseif ($error == 'format_file_tidak_sesuai') {
                            echo 'Format file tidak sesuai! Hanya JPG, PNG, dan GIF yang diperbolehkan.';
                        } elseif ($error == 'ukuran_file_terlalu_besar') {
                            echo 'Ukuran file terlalu besar! Maksimal 2MB.';
                        } elseif ($error == 'gagal_upload_file') {
                            echo 'Gagal mengupload file! Silakan coba lagi.';
                        } else {
                            echo htmlspecialchars($error);
                        }
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['success'])): ?>
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                        Profil berhasil diperbarui!
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="update_profil_proses.php" enctype="multipart/form-data" class="space-y-5">
                    <!-- Foto Profil -->
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700">Foto Profil</label>
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0">
                                <?php 
                                $foto_profil = !empty($karyawan['foto_profil']) ? BASE_PATH . '/assets/profiles/' . $karyawan['foto_profil'] : '';
                                if ($foto_profil && file_exists('../../assets/profiles/' . $karyawan['foto_profil'])): 
                                ?>
                                    <img src="<?= $foto_profil; ?>" alt="Foto Profil" 
                                        class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                                <?php else: ?>
                                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold border-2 border-gray-200">
                                        <?= strtoupper(substr($karyawan['nama'], 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="foto_profil" id="foto_profil" accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB</p>
                                <?php if ($foto_profil && file_exists('../../assets/profiles/' . $karyawan['foto_profil'])): ?>
                                    <label class="flex items-center mt-2 text-sm text-red-600 cursor-pointer hover:text-red-700">
                                        <input type="checkbox" name="hapus_foto" value="1" class="mr-2">
                                        Hapus foto profil
                                    </label>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div id="preview" class="mt-2 hidden">
                            <img id="previewImg" src="" alt="Preview" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Nama Lengkap *</label>
                        <input type="text" name="nama" value="<?= htmlspecialchars($karyawan['nama']); ?>" required
                            class="w-full p-2.5 border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Username *</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($karyawan['username']); ?>" required
                            class="w-full p-2.5 border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <p class="text-xs text-gray-500 mt-1">Username digunakan untuk login</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($email); ?>"
                            class="w-full p-2.5 border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="email@example.com">
                        <p class="text-xs text-gray-500 mt-1">Email opsional</p>
                    </div>
                    
                    <div class="flex gap-2 pt-4">
                        <button type="submit" 
                            class="flex-1 bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Simpan Perubahan
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
        // Preview foto sebelum upload
        document.getElementById('foto_profil').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('preview');
                    const previewImg = document.getElementById('previewImg');
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                document.getElementById('preview').classList.add('hidden');
            }
        });
    </script>
</body>
</html>

