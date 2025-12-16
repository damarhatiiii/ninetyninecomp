<?php
// Deteksi halaman aktif berdasarkan nama file
$current_page = basename($_SERVER['PHP_SELF']);
?>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="<?= BASE_PATH; ?>/pages/dashboard.php" class="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="<?= BASE_PATH; ?>/assets/sssda.png" class="h-8" alt="NinetyNine Logo" />
        <span class="self-center text-2xl font-semibold whitespace-nowrap text-gray-900">
            NinetyNineComp
        </span>
        </a>

        <button data-collapse-toggle="navbar-default" type="button"
        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-600 rounded-lg md:hidden 
        hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300"
        aria-controls="navbar-default" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
            stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
        </svg>
        </button>

        <div class="hidden w-full md:block md:w-auto ml-auto" id="navbar-default">
        <ul
            class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-200 rounded-lg 
            bg-white md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 
            md:bg-transparent">

            <!-- Dashboard -->
            <li>
            <a href="<?= BASE_PATH; ?>/pages/dashboard.php"
                class="block py-2 px-3 rounded-lg md:p-0 transition-colors
                <?= $current_page == 'dashboard.php'
                    ? 'text-white bg-blue-600 md:bg-transparent md:text-blue-600 font-semibold'
                    : 'text-gray-700 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-600'; ?>">
                Dashboard
            </a>
            </li>

            <!-- Produk -->
            <li>
            <a href="<?= BASE_PATH; ?>/pages/master/produk.php"
                class="block py-2 px-3 rounded-lg md:p-0 transition-colors
                <?= $current_page == 'produk.php'
                    ? 'text-white bg-blue-600 md:bg-transparent md:text-blue-600 font-semibold'
                    : 'text-gray-700 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-600'; ?>">
                Produk
            </a>
            </li>

            <!-- Aktifitas -->
            <li>
            <a href="<?= BASE_PATH; ?>/pages/aktifitas.php"
                class="block py-2 px-3 rounded-lg md:p-0 transition-colors
                <?= in_array($current_page, ['aktifitas.php', 'transaksi.php', 'barang_masuk.php', 'tambah_transaksi.php', 'tambah_barang_masuk.php', 'detail_transaksi.php'])
                    ? 'text-white bg-blue-600 md:bg-transparent md:text-blue-600 font-semibold'
                    : 'text-gray-700 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-600'; ?>">
                Aktifitas
            </a>
            </li>

            <!-- Akun - Foto Profil -->
            <li>
            <?php
            // Ambil foto profil user yang sedang login
            if (isset($_SESSION['id_karyawan'])) {
                // Include db.php dengan path yang benar dari navbar
                $navbar_db_path = __DIR__ . '/../config/db.php';
                if (file_exists($navbar_db_path)) {
                    include $navbar_db_path;
                    $id_karyawan = $_SESSION['id_karyawan'];
                    $user_query = mysqli_query($conn, "SELECT foto_profil, nama FROM karyawan WHERE id_karyawan = '$id_karyawan'");
                    $user_data = mysqli_fetch_assoc($user_query);
                    $foto_path = __DIR__ . '/../assets/profiles/' . ($user_data['foto_profil'] ?? '');
                    $foto_profil = !empty($user_data['foto_profil']) && file_exists($foto_path)
                        ? BASE_PATH . '/assets/profiles/' . $user_data['foto_profil'] 
                        : '';
                    $user_nama = $user_data['nama'] ?? $_SESSION['nama'] ?? 'User';
                } else {
                    $foto_profil = '';
                    $user_nama = $_SESSION['nama'] ?? 'User';
                }
            } else {
                $foto_profil = '';
                $user_nama = 'User';
            }
            ?>
            <a href="<?= BASE_PATH; ?>/pages/master/karyawan.php"
                class="block py-2 px-3 rounded-lg md:p-0 transition-colors
                <?= $current_page == 'karyawan.php'
                    ? 'bg-blue-600 md:bg-transparent'
                    : 'hover:bg-gray-100 md:hover:bg-transparent'; ?>"
                title="Akun Saya">
                <?php if ($foto_profil): ?>
                    <img src="<?= $foto_profil; ?>" alt="<?= htmlspecialchars($user_nama); ?>" 
                        class="w-8 h-8 rounded-full object-cover border-2 <?= $current_page == 'karyawan.php' ? 'border-blue-300' : 'border-gray-300'; ?>">
                <?php else: ?>
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold border-2 <?= $current_page == 'karyawan.php' ? 'border-blue-300' : 'border-gray-300'; ?>">
                        <?= strtoupper(substr($user_nama, 0, 1)); ?>
                    </div>
                <?php endif; ?>
            </a>
            </li>

        </ul>
        </div>
    </div>
    </nav>
