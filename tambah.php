<?php
session_start();
include 'config/db.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Cek role
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'staf')) {
    die('Akses ditolak!');
}

// Proses simpan produk baru
if (isset($_POST['simpan'])) {

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
        echo "<script>
                alert('ID Produk sudah ada, gunakan ID lain!');
                window.history.back();
            </script>";
        exit;
    }

    // Simpan produk baru
    $insert = mysqli_query($conn, "INSERT INTO produk 
        (id_produk, nama_produk, id_kategori, merk, spesifikasi, stok, harga) 
        VALUES 
        ('$id_produk', '$nama_produk', '$id_kategori', '$merk', '$spesifikasi', $stok, $harga)");

    if ($insert) {
        echo "<script>
                alert('Produk berhasil ditambahkan!');
                window.location='barang.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menyimpan: ".mysqli_error($conn)."');
                window.history.back();
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 dark:bg-gray-900">

    <?php include 'includes/navbar.php'; ?>

    <div class="p-6 max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 border-b pb-3">
                Tambah Produk Baru
            </h2>

            <form method="POST" class="space-y-5">

                <!-- ID Produk -->
                <div>
                    <label for="id_produk" class="block mb-1 text-sm font-medium text-gray-900 dark:text-gray-200">
                        ID Produk
                    </label>
                    <input type="text" name="id_produk" id="id_produk" required
                        class="w-full p-2.5 rounded-lg border bg-gray-50 dark:bg-gray-700 dark:text-white" />
                </div>

                <!-- Nama Produk -->
                <div>
                    <label for="nama_produk" class="block mb-1 text-sm font-medium text-gray-900 dark:text-gray-200">
                        Nama Produk
                    </label>
                    <input type="text" name="nama_produk" id="nama_produk" required
                        class="w-full p-2.5 rounded-lg border bg-gray-50 dark:bg-gray-700 dark:text-white" />
                </div>

                <!-- Kategori -->
                <div>
                    <label for="id_kategori" class="block mb-1 text-sm font-medium text-gray-900 dark:text-gray-200">
                        Kategori
                    </label>

                    <select name="id_kategori" id="id_kategori" required
                        class="w-full p-2.5 rounded-lg border bg-gray-50 dark:bg-gray-700 dark:text-white">

                        <option value="" disabled selected>Pilih Kategori</option>

                        <?php
                        $kategoriQuery = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                        while ($row = mysqli_fetch_assoc($kategoriQuery)) {
                            echo "<option value='{$row['id_kategori']}'>{$row['nama_kategori']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Merk -->
                <div>
                    <label for="merk" class="block mb-1 text-sm font-medium text-gray-900 dark:text-gray-200">
                        Merek
                    </label>
                    <input type="text" name="merk" id="merk" required
                        class="w-full p-2.5 rounded-lg border bg-gray-50 dark:bg-gray-700 dark:text-white" />
                </div>

                <!-- Spesifikasi -->
                <div>
                    <label for="spesifikasi" class="block mb-1 text-sm font-medium text-gray-900 dark:text-gray-200">
                        Spesifikasi
                    </label>
                    <textarea name="spesifikasi" id="spesifikasi" rows="3"
                        class="w-full p-2.5 rounded-lg border bg-gray-50 dark:bg-gray-700 dark:text-white"></textarea>
                </div>

                <!-- Harga & Stok -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="harga" class="block mb-1 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Harga (Rp)
                        </label>
                        <input type="number" name="harga" id="harga" required
                            class="w-full p-2.5 rounded-lg border bg-gray-50 dark:bg-gray-700 dark:text-white" />
                    </div>

                    <div>
                        <label for="stok" class="block mb-1 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Stok
                        </label>
                        <input type="number" name="stok" id="stok" required
                            class="w-full p-2.5 rounded-lg border bg-gray-50 dark:bg-gray-700 dark:text-white" />
                    </div>
                </div>

                <button type="submit" name="simpan"
                    class="mt-4 w-full text-white bg-blue-700 hover:bg-blue-800 font-semibold rounded-lg text-sm px-5 py-2.5">
                    Simpan Produk
                </button>

            </form>
        </div>
    </div>

    <?php include 'includes/footbar.php'; ?>

</body>
</html>
