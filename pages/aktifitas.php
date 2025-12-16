<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_PATH . '/auth/login.php');
    exit;
}

// Ambil data transaksi (join dengan customer & karyawan, tapi tetap tampil walau data karyawan hilang)
// Tidak lagi menggunakan kolom t.nama_pembeli agar tetap aman jika kolom tersebut dihapus dari database
$transaksi_query = "SELECT 
                                t.*, 
                                COALESCE(k.nama, '-') AS nama_karyawan,
                                COALESCE(c.nama, '-') AS nama_pembeli_display,
                                c.id_customer,
                                c.nama AS nama_customer
                                FROM transaksi t 
                                LEFT JOIN karyawan k ON t.id_karyawan = k.id_karyawan
                                LEFT JOIN customer c ON t.id_customer = c.id_customer
                                ORDER BY t.tanggal DESC";
$transaksi_result = mysqli_query($conn, $transaksi_query);
if (!$transaksi_result) {
    // Jika ingin melihat detail error SQL, buka URL dengan parameter ?debug_transaksi=1
    if (isset($_GET['debug_transaksi'])) {
        die('Error query transaksi: ' . mysqli_error($conn));
    }
    $transaksi_result = false;
}

// Ambil data barang masuk
$barang_masuk_result = mysqli_query($conn, "SELECT bm.*, p.nama_produk, s.nama as nama_supplier, k.nama as nama_karyawan
                                FROM barang_masuk bm
                                JOIN produk p ON bm.id_produk = p.id_produk
                                JOIN supplier s ON bm.id_supplier = s.id_supplier
                                JOIN karyawan k ON bm.id_karyawan = k.id_karyawan
                                ORDER BY bm.tanggal DESC");
if (!$barang_masuk_result) {
    $barang_masuk_result = false;
}

// Ambil data aktifitas log
// Gunakan LEFT JOIN supaya log tetap muncul meskipun data karyawan sudah dihapus/tidak cocok
$aktifitas_result = mysqli_query($conn, "SELECT 
                                a.*, 
                                COALESCE(k.nama, '-') AS nama_karyawan
                                FROM aktifitas a
                                LEFT JOIN karyawan k ON a.id_karyawan = k.id_karyawan
                                ORDER BY a.tanggal DESC
                                LIMIT 100");
if (!$aktifitas_result) {
    $aktifitas_result = false;
}

// Proses simpan produk baru (jika ada POST)
$produk_success = false;
$produk_error = '';
if (isset($_POST['simpan_produk'])) {
    $id_produk = mysqli_real_escape_string($conn, $_POST['id_produk']);
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $id_kategori = mysqli_real_escape_string($conn, $_POST['id_kategori']);
    $merk = mysqli_real_escape_string($conn, $_POST['merk']);
    $spesifikasi = mysqli_real_escape_string($conn, $_POST['spesifikasi']);
    $harga = (int) $_POST['harga'];
    $stok = (int) $_POST['stok'];

    // Cek apakah ID produk sudah ada
    $cek = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk='$id_produk'");
    if ($cek && mysqli_num_rows($cek) > 0) {
        $produk_error = 'ID Produk sudah ada, gunakan ID lain!';
    } else {
        // Simpan produk baru
        $insert = mysqli_query($conn, "INSERT INTO produk 
            (id_produk, nama_produk, id_kategori, merk, spesifikasi, stok, harga) 
            VALUES 
            ('$id_produk', '$nama_produk', '$id_kategori', '$merk', '$spesifikasi', $stok, $harga)");

        if ($insert) {
            $produk_success = true;
            // Reset form dengan redirect
            header("Location: aktifitas.php?tab=tambah_produk&success=1");
            exit;
        } else {
            $produk_error = 'Gagal menyimpan produk!';
        }
    }
}

// Tab aktif
$active_tab = $_GET['tab'] ?? 'transaksi';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktifitas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="p-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between border-b border-gray-200 pb-3 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Aktifitas</h2>
                    <div class="flex gap-2">
                        <a href="transaksi/tambah_transaksi.php" 
                            class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md">
                            + Transaksi
                        </a>
                        <a href="barang/tambah_barang_masuk.php" 
                            class="inline-block bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md">
                            + Barang Masuk
                        </a>
                        <a href="?tab=tambah_produk" 
                            class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md">
                            + Produk
                        </a>
                    </div>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                        Data berhasil disimpan!
                    </div>
                <?php endif; ?>

                <!-- Tabs -->
                <div class="border-b border-gray-200 mb-6">
                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                        <li class="me-2">
                            <a href="?tab=transaksi" 
                                class="inline-block p-4 border-b-2 rounded-t-lg <?= $active_tab == 'transaksi' ? 'text-blue-600 border-blue-600' : 'text-gray-500 border-transparent hover:text-gray-600 hover:border-gray-300'; ?>">
                                Transaksi
                            </a>
                        </li>
                        <li class="me-2">
                            <a href="?tab=barang_masuk" 
                                class="inline-block p-4 border-b-2 rounded-t-lg <?= $active_tab == 'barang_masuk' ? 'text-blue-600 border-blue-600' : 'text-gray-500 border-transparent hover:text-gray-600 hover:border-gray-300'; ?>">
                                Barang Masuk
                            </a>
                        </li>
                        <li class="me-2">
                            <a href="?tab=tambah_produk" 
                                class="inline-block p-4 border-b-2 rounded-t-lg <?= $active_tab == 'tambah_produk' ? 'text-blue-600 border-blue-600' : 'text-gray-500 border-transparent hover:text-gray-600 hover:border-gray-300'; ?>">
                                Tambah Produk
                            </a>
                        </li>
                        <li class="me-2">
                            <a href="?tab=log" 
                                class="inline-block p-4 border-b-2 rounded-t-lg <?= $active_tab == 'log' ? 'text-blue-600 border-blue-600' : 'text-gray-500 border-transparent hover:text-gray-600 hover:border-gray-300'; ?>">
                                Log Aktifitas
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content: Transaksi -->
                <?php if ($active_tab == 'transaksi'): ?>
                <div class="relative overflow-x-auto rounded-lg shadow-sm">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-700">
                        <thead class="text-xs uppercase bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">ID Transaksi</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Customer</th>
                                <th class="px-6 py-3">Karyawan</th>
                                <th class="px-6 py-3">Total</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if ($transaksi_result && mysqli_num_rows($transaksi_result) > 0) {
                                mysqli_data_seek($transaksi_result, 0);
                                while ($row = mysqli_fetch_assoc($transaksi_result)): 
                            ?>
                            <tr class="bg-white border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4"><?= $no++; ?></td>
                                <td class="px-6 py-4 font-medium"><?= htmlspecialchars($row['id_transaksi']); ?></td>
                                <td class="px-6 py-4"><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['nama_pembeli_display'] ?? '-'); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['nama_karyawan']); ?></td>
                                <td class="px-6 py-4 font-semibold text-green-600">
                                    Rp <?= number_format($row['total'], 0, ',', '.'); ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="transaksi/detail_transaksi.php?id=<?= $row['id_transaksi']; ?>&back=aktifitas" 
                                        class="text-blue-600 hover:underline">Detail</a>
                                </td>
                            </tr>
                            <?php 
                                endwhile;
                            } else {
                                echo '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data transaksi</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

                <!-- Tab Content: Barang Masuk -->
                <?php if ($active_tab == 'barang_masuk'): ?>
                <div class="relative overflow-x-auto rounded-lg shadow-sm">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-700">
                        <thead class="text-xs uppercase bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">ID</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Produk</th>
                                <th class="px-6 py-3">Supplier</th>
                                <th class="px-6 py-3">Jumlah</th>
                                <th class="px-6 py-3">Karyawan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if ($barang_masuk_result && mysqli_num_rows($barang_masuk_result) > 0) {
                                mysqli_data_seek($barang_masuk_result, 0);
                                while ($row = mysqli_fetch_assoc($barang_masuk_result)): 
                            ?>
                            <tr class="bg-white border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4"><?= $no++; ?></td>
                                <td class="px-6 py-4 font-medium"><?= htmlspecialchars($row['id_masuk']); ?></td>
                                <td class="px-6 py-4"><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['nama_produk']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['nama_supplier']); ?></td>
                                <td class="px-6 py-4"><?= $row['jumlah_masuk']; ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['nama_karyawan']); ?></td>
                            </tr>
                            <?php 
                                endwhile;
                            } else {
                                echo '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data barang masuk</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

                <!-- Tab Content: Tambah Produk -->
                <?php if ($active_tab == 'tambah_produk'): ?>
                <div class="bg-white rounded-lg p-6">
                    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                            Produk berhasil disimpan!
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($produk_error)): ?>
                        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                            <?= htmlspecialchars($produk_error); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-5">
                        <!-- ID Produk -->
                        <div>
                            <label for="id_produk" class="block mb-1 text-sm font-medium text-gray-700">
                                ID Produk *
                            </label>
                            <input type="text" name="id_produk" id="id_produk" required
                                value="<?= isset($_POST['id_produk']) ? htmlspecialchars($_POST['id_produk']) : ''; ?>"
                                class="w-full p-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                        </div>

                        <!-- Nama Produk -->
                        <div>
                            <label for="nama_produk" class="block mb-1 text-sm font-medium text-gray-700">
                                Nama Produk *
                            </label>
                            <input type="text" name="nama_produk" id="nama_produk" required
                                value="<?= isset($_POST['nama_produk']) ? htmlspecialchars($_POST['nama_produk']) : ''; ?>"
                                class="w-full p-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="id_kategori" class="block mb-1 text-sm font-medium text-gray-700">
                                Kategori *
                            </label>
                            <select name="id_kategori" id="id_kategori" required
                                class="w-full p-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="" disabled selected>Pilih Kategori</option>
                                <?php
                                $kategoriQuery = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                                while ($row = mysqli_fetch_assoc($kategoriQuery)) {
                                    $selected = (isset($_POST['id_kategori']) && $_POST['id_kategori'] == $row['id_kategori']) ? 'selected' : '';
                                    echo "<option value='{$row['id_kategori']}' $selected>{$row['nama_kategori']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Merk -->
                        <div>
                            <label for="merk" class="block mb-1 text-sm font-medium text-gray-700">
                                Merk *
                            </label>
                            <input type="text" name="merk" id="merk" required
                                value="<?= isset($_POST['merk']) ? htmlspecialchars($_POST['merk']) : ''; ?>"
                                class="w-full p-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                        </div>

                        <!-- Spesifikasi -->
                        <div>
                            <label for="spesifikasi" class="block mb-1 text-sm font-medium text-gray-700">
                                Spesifikasi
                            </label>
                            <textarea name="spesifikasi" id="spesifikasi" rows="3"
                                class="w-full p-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"><?= isset($_POST['spesifikasi']) ? htmlspecialchars($_POST['spesifikasi']) : ''; ?></textarea>
                        </div>

                        <!-- Harga & Stok -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="harga" class="block mb-1 text-sm font-medium text-gray-700">
                                    Harga (Rp) *
                                </label>
                                <input type="number" name="harga" id="harga" required min="0"
                                    value="<?= isset($_POST['harga']) ? htmlspecialchars($_POST['harga']) : ''; ?>"
                                    class="w-full p-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                            </div>

                            <div>
                                <label for="stok" class="block mb-1 text-sm font-medium text-gray-700">
                                    Stok *
                                </label>
                                <input type="number" name="stok" id="stok" required min="0"
                                    value="<?= isset($_POST['stok']) ? htmlspecialchars($_POST['stok']) : ''; ?>"
                                    class="w-full p-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                            </div>
                        </div>

                        <div class="flex gap-2 pt-4">
                            <button type="submit" name="simpan_produk"
                                class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg text-sm px-5 py-2.5 transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                Simpan Produk
                            </button>
                            <button type="reset"
                                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg text-sm px-5 py-2.5 transition-all duration-200">
                                Reset Form
                            </button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>

                <!-- Tab Content: Log Aktifitas -->
                <?php if ($active_tab == 'log'): ?>
                <div class="relative overflow-x-auto rounded-lg shadow-sm">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-700">
                        <thead class="text-xs uppercase bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Karyawan</th>
                                <th class="px-6 py-3 min-w-[140px]">Jenis</th>
                                <th class="px-6 py-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if ($aktifitas_result && mysqli_num_rows($aktifitas_result) > 0) {
                                mysqli_data_seek($aktifitas_result, 0);
                                while ($row = mysqli_fetch_assoc($aktifitas_result)): 
                                    $badge_color = '';
                                    switch($row['jenis_aktifitas']) {
                                        case 'barang_masuk':
                                            $badge_color = 'bg-green-100 text-green-800';
                                            break;
                                        case 'transaksi':
                                            $badge_color = 'bg-blue-100 text-blue-800';
                                            break;
                                        default:
                                            $badge_color = 'bg-gray-100 text-gray-800';
                                            break;
                                    }
                            ?>
                            <tr class="bg-white border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4"><?= $no++; ?></td>
                                <td class="px-6 py-4"><?= date('d/m/Y H:i', strtotime($row['tanggal'])); ?></td>
                                <td class="px-6 py-4 font-medium"><?= htmlspecialchars($row['nama_karyawan']); ?></td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1.5 rounded-md text-xs font-semibold whitespace-nowrap <?= $badge_color; ?>">
                                        <?= ucfirst(str_replace('_', ' ', $row['jenis_aktifitas'])); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['keterangan']); ?></td>
                            </tr>
                            <?php 
                                endwhile;
                            } else {
                                echo '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data log aktifitas</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include '../includes/footbar.php'; ?>
</body>
</html>
