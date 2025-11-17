<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Ambil data produk dan supplier
$produk_result = mysqli_query($conn, "SELECT * FROM produk ORDER BY nama_produk");
if (!$produk_result) {
    $produk_result = false;
}

$supplier_result = mysqli_query($conn, "SELECT * FROM supplier ORDER BY nama");
if (!$supplier_result) {
    $supplier_result = false;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang Masuk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900">
    <?php include '../includes/navbar.php'; ?>
    
    <div class="p-6">
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Tambah Barang Masuk</h2>
            
            <form method="POST" action="tambah_barang_masuk_proses.php">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Supplier *</label>
                    <select name="id_supplier" required class="w-full p-2 border rounded">
                        <option value="">Pilih Supplier</option>
                        <?php 
                        if ($supplier_result && mysqli_num_rows($supplier_result) > 0) {
                            mysqli_data_seek($supplier_result, 0);
                            while ($s = mysqli_fetch_assoc($supplier_result)): ?>
                                <option value="<?= $s['id_supplier']; ?>"><?= htmlspecialchars($s['nama']); ?></option>
                            <?php endwhile;
                        }
                        ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        <a href="supplier.php" class="text-blue-600 hover:underline">Tambah supplier baru</a>
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Produk *</label>
                    <select name="id_produk" required class="w-full p-2 border rounded" onchange="updateStok()">
                        <option value="">Pilih Produk</option>
                        <?php 
                        if ($produk_result && mysqli_num_rows($produk_result) > 0) {
                            mysqli_data_seek($produk_result, 0);
                            while ($p = mysqli_fetch_assoc($produk_result)): 
                            ?>
                                <option value="<?= $p['id_produk']; ?>" data-stok="<?= $p['stok']; ?>">
                                    <?= htmlspecialchars($p['nama_produk']); ?> (Stok: <?= $p['stok']; ?>)
                                </option>
                            <?php 
                            endwhile;
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Jumlah Masuk *</label>
                    <input type="number" name="jumlah_masuk" required min="1" 
                        class="w-full p-2 border rounded"
                        placeholder="Masukkan jumlah">
                    <p class="text-xs text-gray-500 mt-1">Stok saat ini: <span id="currentStok">-</span></p>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Simpan
                    </button>
                    <a href="aktifitas.php?tab=barang_masuk" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateStok() {
            const select = document.querySelector('select[name="id_produk"]');
            const selectedOption = select.options[select.selectedIndex];
            const stok = selectedOption.dataset.stok || '-';
            document.getElementById('currentStok').textContent = stok;
        }
    </script>
</body>
</html>

