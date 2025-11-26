<?php
session_start();
include '../../config/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_PATH . '/auth/login.php');
    exit;
}

// Ambil data produk
$produk_result = mysqli_query($conn, "SELECT * FROM produk WHERE stok > 0 ORDER BY nama_produk");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang Keluar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <?php include '../../includes/navbar.php'; ?>
    
    <div class="p-6 min-h-[calc(100vh-80px)] pb-20">
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-900">Tambah Barang Keluar</h2>
            
            <form method="POST" action="tambah_barang_keluar_proses.php" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Produk *</label>
                    <select name="id_produk" required 
                        class="w-full p-2.5 border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        onchange="updateStok()">
                        <option value="">Pilih Produk</option>
                        <?php while ($p = mysqli_fetch_assoc($produk_result)): ?>
                            <option value="<?= $p['id_produk']; ?>" data-stok="<?= $p['stok']; ?>">
                                <?= htmlspecialchars($p['nama_produk']); ?> (Stok: <?= $p['stok']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Jumlah Keluar *</label>
                    <input type="number" name="jumlah_keluar" required min="1" 
                        class="w-full p-2.5 border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="Masukkan jumlah"
                        id="jumlahInput">
                    <p class="text-xs text-gray-500 mt-1">Stok saat ini: <span id="currentStok" class="font-semibold">-</span></p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Keterangan</label>
                    <textarea name="keterangan" rows="3" 
                        class="w-full p-2.5 border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="Alasan barang keluar (opsional)"></textarea>
                </div>

                <div class="flex gap-2 pt-4">
                    <button type="submit" 
                        class="flex-1 bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Simpan
                    </button>
                    <a href="../aktifitas.php?tab=barang_keluar" 
                        class="flex-1 bg-gray-200 text-gray-800 px-6 py-2.5 rounded-lg hover:bg-gray-300 transition-all duration-200 font-medium text-center">
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
            
            // Set max value untuk input jumlah
            const jumlahInput = document.getElementById('jumlahInput');
            if (stok !== '-') {
                jumlahInput.setAttribute('max', stok);
            }
        }
    </script>
</body>
</html>

