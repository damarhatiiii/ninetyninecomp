<?php
session_start();
include '../../config/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_PATH . '/auth/login.php');
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
<body class="bg-gray-50">
    <?php include '../../includes/navbar.php'; ?>
    
    <div class="p-6 min-h-[calc(100vh-80px)] pb-20">
        <div class="max-w-5xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-900">Transaksi Baru</h2>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    <?= htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="tambah_transaksi_proses.php" id="transaksiForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1 text-gray-700">Pilih Member (Customer)</label>
                    <select name="id_customer" id="id_customer" 
                        class="w-full p-2.5 border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        onchange="updateNamaPembeli()">
                        <option value="">-- Pilih Customer / Umum --</option>
                        <?php 
                        mysqli_data_seek($customer_result, 0);
                        while ($c = mysqli_fetch_assoc($customer_result)): 
                        ?>
                            <option value="<?= htmlspecialchars($c['id_customer']); ?>" 
                                data-nama="<?= htmlspecialchars($c['nama']); ?>">
                                <?= htmlspecialchars($c['id_customer']); ?> - <?= htmlspecialchars($c['nama']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pilih customer yang terdaftar atau biarkan kosong untuk pembeli umum</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1 text-gray-700">Nama Pembeli *</label>
                    <input type="text" name="nama_pembeli" id="nama_pembeli" 
                        class="w-full p-2.5 border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                        placeholder="Masukkan nama pembeli"
                        required>
                    <p class="text-xs text-gray-500 mt-1">Nama pembeli akan terisi otomatis jika memilih customer, atau isi manual untuk pembeli umum</p>
                </div>

                <div class="mb-4">
                    <h3 class="text-lg font-semibold mb-3 text-gray-900">Pilih Produk</h3>
                    <div class="border border-gray-200 rounded-lg p-4 max-h-60 overflow-y-auto bg-gray-50">
                        <?php while ($p = mysqli_fetch_assoc($produk_result)): ?>
                            <div class="flex items-center justify-between p-2 border-b border-gray-200">
                                <div class="flex-1">
                                    <input type="checkbox" name="produk[]" value="<?= $p['id_produk']; ?>" 
                                        class="produk-checkbox" 
                                        data-harga="<?= $p['harga']; ?>"
                                        data-nama="<?= htmlspecialchars($p['nama_produk']); ?>"
                                        data-stok="<?= $p['stok']; ?>"
                                        onchange="toggleProduk(this)">
                                    <span class="ml-2 text-gray-900"><?= htmlspecialchars($p['nama_produk']); ?></span>
                                    <span class="text-gray-600 text-sm ml-2">
                                        (Stok: <?= $p['stok']; ?>) - 
                                        Rp <?= number_format($p['harga'], 0, ',', '.'); ?>
                                    </span>
                                </div>
                                <input type="number" name="qty[<?= $p['id_produk']; ?>]" 
                                    min="1" max="<?= $p['stok']; ?>" 
                                    value="1" 
                                    class="hidden qty-input w-20 p-1 border border-gray-300 rounded-lg bg-white text-gray-900"
                                    data-produk="<?= $p['id_produk']; ?>"
                                    onchange="updateTotal()">
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <div id="selectedProducts" class="mb-4"></div>

                <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-900">Total:</span>
                        <span id="totalHarga" class="text-2xl font-bold text-green-600">Rp 0</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Simpan Transaksi
                    </button>
                    <a href="../aktifitas.php?tab=transaksi" class="bg-gray-200 text-gray-800 px-6 py-2.5 rounded-lg hover:bg-gray-300 transition-all duration-200 font-medium">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        let selectedProducts = {};

        // Update nama pembeli ketika customer dipilih
        function updateNamaPembeli() {
            const customerSelect = document.getElementById('id_customer');
            const namaPembeliInput = document.getElementById('nama_pembeli');
            
            if (customerSelect.value) {
                const selectedOption = customerSelect.options[customerSelect.selectedIndex];
                const namaCustomer = selectedOption.getAttribute('data-nama');
                if (namaCustomer) {
                    namaPembeliInput.value = namaCustomer;
                }
            } else {
                // Jika memilih "Umum", biarkan kosong untuk diisi manual
                namaPembeliInput.value = '';
            }
        }

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

            let html = '<h3 class="text-lg font-semibold mb-3 text-gray-900">Produk Terpilih:</h3><div class="space-y-2">';
            for (let id in selectedProducts) {
                const p = selectedProducts[id];
                html += `
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded border border-gray-200">
                        <span class="text-gray-900">${p.nama}</span>
                        <div class="flex items-center gap-2">
                            <input type="number" min="1" max="${p.stok}" value="${p.qty}" 
                                class="w-20 p-1 border border-gray-300 rounded-lg bg-white text-gray-900"
                                onchange="updateQty('${id}', this.value)">
                            <span class="text-gray-600">x Rp ${p.harga.toLocaleString('id-ID')}</span>
                            <span class="font-semibold text-gray-900">= Rp ${(p.harga * p.qty).toLocaleString('id-ID')}</span>
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

