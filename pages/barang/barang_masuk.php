<?php
session_start();
include '../../config/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_PATH . '/auth/login.php');
    exit;
}

// Ambil data produk & supplier
$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY nama_produk");
$supplier = mysqli_query($conn, "SELECT * FROM supplier ORDER BY nama");

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_supplier = $_POST['id_supplier'];
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah_masuk'];
    $tanggal = $_POST['tanggal'];
    $id_karyawan = $_POST['id_karyawan'];
 // pastikan ini sudah diset

$query = "INSERT INTO barang_masuk (id_supplier, id_produk, jumlah_masuk, id_karyawan, tanggal)
            VALUES ('$id_supplier', '$id_produk', '$jumlah', '$id_karyawan', '$tanggal')";


    if (mysqli_query($conn, $query)) {
        // Tambah stok produk
        mysqli_query($conn, "UPDATE produk SET stok = stok + $jumlah WHERE id_produk = '$id_produk'");
        header("Location: barang_masuk.php?s=1");
        exit;
    } else {
        $error = "Gagal menyimpan data";
    }
}

// Ambil data barang masuk
$result = mysqli_query($conn, "SELECT bm.*, p.nama_produk, s.nama as nama_supplier, k.nama as nama_karyawan
                                FROM barang_masuk bm
                                JOIN produk p ON bm.id_produk = p.id_produk
                                JOIN supplier s ON bm.id_supplier = s.id_supplier
                                JOIN karyawan k ON bm.id_karyawan = k.id_karyawan
                                ORDER BY bm.tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<script src="https://cdn.tailwindcss.com"></script>
<title>Barang Masuk</title>
</head>

<body class="bg-gray-50 min-h-screen">
<?php include '../../includes/navbar.php'; ?>

<div class="p-6 max-w-7xl mx-auto">
    
    <!-- Judul -->
    <div class="bg-white border border-gray-200 p-6 rounded-xl shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-200 pb-3 mb-5">
            <h1 class="text-2xl font-bold text-gray-900">Barang Masuk</h1>

            <button onclick="toggleForm()" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm shadow-sm">
                + Barang Masuk Baru
            </button>
        </div>

        <!-- FORM TAMBAH (Hidden by default) -->
        <div id="formTambah" class="hidden mb-8 p-6 bg-gray-50 border border-gray-300 rounded-xl">

            <h2 class="text-lg font-semibold mb-4 text-gray-800">Tambah Barang Masuk</h2>

            <form method="POST" class="space-y-5">
                <input type="hidden" name="id_karyawan" value="<?= $_SESSION['id_karyawan']; ?>">

                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Barang Masuk *</label>
                    <input type="date" name="tanggal" 
                        required
                        class="w-full p-2.5 border rounded-lg bg-white text-gray-900 
                                focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Supplier *</label>
                    <select name="id_supplier" required class="w-full p-2.5 border rounded-lg">
                        <option value="">Pilih Supplier</option>
                        <?php while ($s = mysqli_fetch_assoc($supplier)): ?>
                            <option value="<?= $s['id_supplier']; ?>"><?= $s['nama']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Produk *</label>
                    <select name="id_produk" required class="w-full p-2.5 border rounded-lg">
                        <option value="">Pilih Produk</option>
                        <?php while ($p = mysqli_fetch_assoc($produk)): ?>
                            <option value="<?= $p['id_produk']; ?>">
                                <?= $p['nama_produk']; ?> (Stok: <?= $p['stok']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Jumlah Masuk *</label>
                    <input type="number" name="jumlah_masuk" min="1" required
                            class="w-full p-2.5 border rounded-lg bg-white text-gray-900 
                            focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex gap-3">
                    <button type="submit" 
                        class="flex-1 bg-blue-600 text-white py-2.5 rounded-lg">Simpan</button>
                    
                    <button
                        type="button"
                        onclick="toggleForm()"
                        class="flex-1 bg-gray-300 py-2.5 rounded-lg">
                        Batal
                    </button>
                </div>

            </form>
        </div>

        <!-- TABEL DATA -->
        <div class="overflow-x-auto border border-gray-300 rounded-lg">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-100 text-gray-700 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3">No</th>
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
                    while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4"><?= $no++; ?></td>
                        <td class="px-6 py-4"><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                        <td class="px-6 py-4"><?= $row['nama_produk']; ?></td>
                        <td class="px-6 py-4"><?= $row['nama_supplier']; ?></td>
                        <td class="px-6 py-4"><?= $row['jumlah_masuk']; ?></td>
                        <td class="px-6 py-4"><?= $row['nama_karyawan']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php include '../../includes/footbar.php'; ?>

<script>
function toggleForm() {
    const form = document.getElementById('formTambah');
    form.classList.toggle('hidden');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

</body>
</html>
