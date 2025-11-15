<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Ambil data produk dan customer
$produk_result = mysqli_query($conn, "SELECT * FROM produk ORDER BY nama_produk");
$customer_result = mysqli_query($conn, "SELECT * FROM customer ORDER BY nama");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php include '../includes/navbar.php'; ?>
    
    <div class="p-6">
        <div class="max-w-5xl mx-auto bg-white rounded-xl shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Transaksi Baru</h2>
            
            <form method="POST" action="tambah_transaksi_proses.php" id="transaksiForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Customer (Opsional)</label>
                    <select name="id_customer" class="w-full p-2 border rounded">
                        <option value="">Umum (Tanpa Customer)</option>
                        <?php 
                        mysqli_data_seek($customer_result, 0);
                        while ($c = mysqli_fetch_assoc($customer_result)): ?>
                            <option value="<?= $c['id_customer']; ?>"><?= htmlspecialchars($c['nama']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <h3 class="text-lg font-semibold mb-3">Pilih Produk</h3>
                    <div class="border rounded-lg p-4 max-h-60 overflow-y-auto">
                        <?php while ($p = mysqli_fetch_assoc($produk_result)): ?>
                            <div class="flex items-center justify-between p-2 border-b">
                                <div class="flex-1">
                                    <input type="checkbox" name="produk[]" value="<?= $p['id_produk']; ?>" 
                                        class="produk-checkbox" 
                                        data-harga="<?= $p['harga']; ?>"
                                        data-nama="<?= htmlspecialchars($p['nama_produk']); ?>"
                                        data-stok="<?= $p['stok']; ?>"
                                        onchange="toggleProduk(this)">
                                    <span class="ml-2"><?= htmlspecialchars($p['nama_produk']); ?></span>
                                    <span class="text-gray-500 text-sm ml-2">
                                        (Stok: <?= $p['stok']; ?>) - 
                                        Rp <?= number_format($p['harga'], 0, ',', '.'); ?>
                                    </span>
                                </div>
                                <input type="number" name="qty[<?= $p['id_produk']; ?>]" 
                                    min="1" max="<?= $p['stok']; ?>" 
                                    value="1" 
                                    class="hidden qty-input w-20 p-1 border rounded"
                                    data-produk="<?= $p['id_produk']; ?>"
                                    onchange="updateTotal()">
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <div id="selectedProducts" class="mb-4"></div>

                <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold">Total:</span>
                        <span id="totalHarga" class="text-2xl font-bold text-green-600">Rp 0</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Simpan Transaksi
                    </button>
                    <a href="aktifitas.php?tab=transaksi" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        let selectedProducts = {};

        function toggleProduk(checkbox) {
            const produkId = checkbox.value;
            const qtyInput = document.querySelector(`input[name="qty[${produkId}]"]`);
            
            if (checkbox.checked) {
                qtyInput.classList.remove('hidden');
                selectedProducts[produkId] = {
                    nama: checkbox.dataset.nama,
                    harga: parseInt(checkbox.dataset.harga),
                    stok: parseInt(checkbox.dataset.stok),
                    qty: 1
                };
            } else {
                qtyInput.classList.add('hidden');
                qtyInput.value = 1;
                delete selectedProducts[produkId];
            }
            updateSelectedList();
            updateTotal();
        }

        function updateSelectedList() {
            const container = document.getElementById('selectedProducts');
            if (Object.keys(selectedProducts).length === 0) {
                container.innerHTML = '';
                return;
            }

            let html = '<h3 class="text-lg font-semibold mb-3">Produk Terpilih:</h3><div class="space-y-2">';
            for (let id in selectedProducts) {
                const p = selectedProducts[id];
                html += `
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                        <span>${p.nama}</span>
                        <div class="flex items-center gap-2">
                            <input type="number" min="1" max="${p.stok}" value="${p.qty}" 
                                class="w-20 p-1 border rounded"
                                onchange="updateQty('${id}', this.value)">
                            <span>x Rp ${p.harga.toLocaleString('id-ID')}</span>
                            <span class="font-semibold">= Rp ${(p.harga * p.qty).toLocaleString('id-ID')}</span>
                        </div>
                    </div>
                `;
            }
            html += '</div>';
            container.innerHTML = html;
        }

        function updateQty(produkId, qty) {
            if (selectedProducts[produkId]) {
                const maxStok = selectedProducts[produkId].stok;
                qty = Math.min(Math.max(1, parseInt(qty)), maxStok);
                selectedProducts[produkId].qty = qty;
                
                // Update hidden input
                const hiddenInput = document.querySelector(`input[name="qty[${produkId}]"]`);
                if (hiddenInput) hiddenInput.value = qty;
                
                updateSelectedList();
                updateTotal();
            }
        }

        function updateTotal() {
            let total = 0;
            for (let id in selectedProducts) {
                const p = selectedProducts[id];
                total += p.harga * p.qty;
            }
            document.getElementById('totalHarga').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }
    </script>
</body>
</html>

