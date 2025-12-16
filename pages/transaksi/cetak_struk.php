<?php
session_start();
include '../../config/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_PATH . '/auth/login.php');
    exit;
}

$id_transaksi = mysqli_real_escape_string($conn, $_GET['id'] ?? '');

if (empty($id_transaksi)) {
    die("ID Transaksi tidak ditemukan");
}

// Ambil data transaksi
$stmt = mysqli_prepare($conn, "SELECT t.*, k.nama as nama_karyawan 
                                FROM transaksi t 
                                JOIN karyawan k ON t.id_karyawan = k.id_karyawan
                                WHERE t.id_transaksi = ?");
if (!$stmt) {
    die("Error: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "s", $id_transaksi);
mysqli_stmt_execute($stmt);
$transaksi_result = mysqli_stmt_get_result($stmt);
$t = mysqli_fetch_assoc($transaksi_result);
mysqli_stmt_close($stmt);

if (!$t) {
    die("Transaksi tidak ditemukan");
}

// Ambil detail transaksi
$stmt2 = mysqli_prepare($conn, "SELECT dt.*, p.nama_produk, p.harga as harga_satuan
                                FROM detail_transaksi dt
                                JOIN produk p ON dt.id_produk = p.id_produk
                                WHERE dt.id_transaksi = ?
                                ORDER BY dt.id_detail");
if (!$stmt2) {
    die("Error: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt2, "s", $id_transaksi);
mysqli_stmt_execute($stmt2);
$detail_result = mysqli_stmt_get_result($stmt2);

// Tentukan nama pembeli
$nama_pembeli = !empty($t['nama_pembeli']) ? $t['nama_pembeli'] : '-';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi #<?= $id_transaksi; ?></title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
            @page {
                size: 80mm auto;
                margin: 5mm;
            }
        }
        body {
            font-family: 'Courier New', monospace;
            max-width: 80mm;
            margin: 0 auto;
            padding: 10px;
            background: white;
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .header h1 {
            font-size: 18px;
            margin: 5px 0;
            font-weight: bold;
        }
        .header p {
            font-size: 11px;
            margin: 2px 0;
        }
        .info {
            margin: 10px 0;
            font-size: 11px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }
        .info-label {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 11px;
        }
        table th {
            text-align: left;
            border-bottom: 1px dashed #000;
            padding: 5px 0;
        }
        table td {
            padding: 3px 0;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total {
            border-top: 2px dashed #000;
            margin-top: 10px;
            padding-top: 10px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px dashed #000;
            font-size: 10px;
        }
        .print-btn {
            text-align: center;
            margin: 20px 0;
        }
        .print-btn button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .print-btn button:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>NINETYNINECOMP</h1>
        <p>Jl. jalan kemana mana</p>
        <p>Telp: 088kapankapankedupan</p>
    </div>

    <div class="info">
        <div class="info-row">
            <span class="info-label">ID Transaksi:</span>
            <span><?= htmlspecialchars($id_transaksi); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal:</span>
            <span><?= date('d/m/Y H:i', strtotime($t['tanggal'])); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Kasir:</span>
            <span><?= htmlspecialchars($t['nama_karyawan']); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Pembeli:</span>
            <span><?= htmlspecialchars($nama_pembeli); ?></span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            mysqli_data_seek($detail_result, 0);
            while ($d = mysqli_fetch_assoc($detail_result)): 
            ?>
            <tr>
                <td><?= htmlspecialchars($d['nama_produk']); ?></td>
                <td class="text-center"><?= $d['jumlah']; ?></td>
                <td class="text-right"><?= number_format($d['harga_satuan'], 0, ',', '.'); ?></td>
                <td class="text-right"><?= number_format($d['subtotal'], 0, ',', '.'); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="total">
        <div class="total-row">
            <span>TOTAL:</span>
            <span>Rp <?= number_format($t['total'], 0, ',', '.'); ?></span>
        </div>
    </div>

    <div class="footer">
        <p>Terima kasih atas kunjungan Anda</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</p>
        <p><?= date('d/m/Y H:i:s'); ?></p>
    </div>

    <div class="print-btn no-print">
        <button onclick="window.print()">Cetak Struk</button>
    </div>

    <script>
        // Auto print saat halaman dimuat
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>

