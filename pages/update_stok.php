<?php
include 'config/db.php';

$id = $_GET['id'];
$q = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk='$id'");
$data = mysqli_fetch_assoc($q);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Stok Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-900 text-white">
    <!-- Navbar -->
    <?php include 'includes/navbar.php'; ?>
    <div class="max-w-md mx-auto mt-24 mb-32">
        <div class="bg-gray-800 rounded-lg shadow-md">

            <!-- HEADER -->
            <div class="bg-gray-800 text-white p-4 rounded-t-lg">
                <h2 class="text-lg font-bold">Update Stok Produk</h2>
            </div>

            <!-- FORM -->
            <div class="p-6">
                <form action="update_stok_proses.php" method="POST">

                    <input type="hidden" name="id_produk" value="<?= $data['id_produk']; ?>">

                    <label class="block mb-2 font-semibold text-gray-300">Nama Produk</label>
                    <input type="text" disabled value="<?= $data['nama_produk']; ?>"
                        class="w-full p-2 mb-4 border rounded bg-gray-700 text-white">

                    <label class="block mb-2 font-semibold text-gray-300">Stok Baru</label>
                    <input type="number" name="stok" required value="<?= $data['stok']; ?>"
                        class="w-full p-2 mb-4 border rounded bg-gray-700 text-white">

                    <button
                        class="bg-blue-600 text-white w-full py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                        Simpan Perubahan
                    </button>

                </form>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footbar.php'; ?>

</body>
</html>
