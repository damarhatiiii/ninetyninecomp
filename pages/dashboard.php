<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Ambil total transaksi per hari selama 7 hari terakhir
$query = "
    SELECT 
        DATE(tanggal) AS tgl,
        COUNT(*) AS total
    FROM transaksi
    WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(tanggal)
    ORDER BY DATE(tanggal)
";

$result = mysqli_query($conn, $query);

$labels = [];
$values = [];

while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['tgl'];
    $values[] = $row['total'];
}

// Query untuk statistik transaksi bulanan
$monthly_revenue = [];
$monthly_transactions = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $month_name = date('M Y', strtotime("-$i months"));
    
    $revenue_query = mysqli_query($conn, "SELECT COALESCE(SUM(total), 0) as total FROM transaksi WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$month'");
    $revenue_data = mysqli_fetch_assoc($revenue_query);
    $monthly_revenue[$month_name] = (float)$revenue_data['total'];
    
    $trans_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM transaksi WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$month'");
    $trans_data = mysqli_fetch_assoc($trans_query);
    $monthly_transactions[$month_name] = (int)$trans_data['count'];
}

// Query untuk barang masuk vs keluar (bulan ini)
$current_month = date('Y-m');
$barang_masuk_query = mysqli_query($conn, "SELECT COALESCE(SUM(jumlah_masuk), 0) as total FROM barang_masuk WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$current_month'");
$barang_masuk_data = mysqli_fetch_assoc($barang_masuk_query);
$total_masuk = (int)$barang_masuk_data['total'];

$barang_keluar_query = mysqli_query($conn, "SELECT COALESCE(SUM(jumlah_keluar), 0) as total FROM barang_keluar WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$current_month'");
$barang_keluar_data = mysqli_fetch_assoc($barang_keluar_query);
$total_keluar = (int)$barang_keluar_data['total'];

// Query untuk produk terlaris (top 5)
$top_products_query = mysqli_query($conn, "
    SELECT p.nama_produk, COALESCE(SUM(dt.jumlah), 0) as total_terjual 
    FROM produk p 
    LEFT JOIN detail_transaksi dt ON p.id_produk = dt.id_produk 
    GROUP BY p.id_produk, p.nama_produk 
    ORDER BY total_terjual DESC 
    LIMIT 5
");
$top_products = [];
$top_products_labels = [];
$top_products_data = [];
while ($row = mysqli_fetch_assoc($top_products_query)) {
    $top_products_labels[] = $row['nama_produk'];
    $top_products_data[] = (int)$row['total_terjual'];
}

// Query untuk statistik umum
$total_produk_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM produk");
$total_produk = mysqli_fetch_assoc($total_produk_query)['count'];

$total_transaksi_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM transaksi");
$total_transaksi = mysqli_fetch_assoc($total_transaksi_query)['count'];

$total_revenue_query = mysqli_query($conn, "SELECT COALESCE(SUM(total), 0) as total FROM transaksi");
$total_revenue = mysqli_fetch_assoc($total_revenue_query)['total'];

$low_stock_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM produk WHERE stok < 10");
$low_stock = mysqli_fetch_assoc($low_stock_query)['count'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900">

    <?php include '../includes/navbar.php'; ?>

    <main class="p-6 min-h-[calc(100vh-80px)]">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Halo, <?= htmlspecialchars($_SESSION['nama']); ?> 👋</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Selamat datang di dashboard toko komputer</p>
            </div>

            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Total Pendapatan</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white mt-2">
                                Rp <?= number_format($total_revenue, 0, ',', '.'); ?>
                            </p>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Total Transaksi</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white mt-2">
                                <?= number_format($total_transaksi, 0, ',', '.'); ?>
                            </p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Total Produk</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white mt-2">
                                <?= number_format($total_produk, 0, ',', '.'); ?>
                            </p>
                        </div>
                        <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                            <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Stok Rendah</p>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-2">
                                <?= number_format($low_stock, 0, ',', '.'); ?>
                            </p>
                        </div>
                        <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full">
                            <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Chart Pendapatan Bulanan -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 max-w-xl w-full mx-auto mb-6">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Pendapatan Bulanan</h3>
                    <canvas id="revenueChart" height="200"></canvas>
                </div>

                <!-- Chart Jumlah Transaksi -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Jumlah Transaksi Bulanan</h3>
                    <canvas id="transactionChart" height="300"></canvas>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 max-w-xl w-full mx-auto mb-6">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Penjualan Produk</h3>
                    <canvas id="productChart" height="200"></canvas>
                </div>

                <!-- === CARD: Transaksi Harian === -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 max-w-xl w-full mx-auto mb-6">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Transaksi Harian</h3>
                    <canvas id="dailyTransactionChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </main>

    <?php include '../includes/footbar.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    // ===== CHART 1 =====
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Pendapatan',
                data: [12000000, 15000000, 18000000, 22000000, 20000000, 25000000],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2
        }
    });

    // ===== CHART 2 =====
    new Chart(document.getElementById('productChart'), {
        type: 'bar',
        data: {
            labels: ['Laptop', 'SSD', 'RAM', 'Keyboard', 'Mouse'],
            datasets: [{
                label: 'Jumlah Terjual',
                data: [12, 19, 7, 14, 10],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2
        }
    });

    // ===== CHART 3 =====
    new Chart(document.getElementById('transactionChart'), {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                label: 'Transaksi',
                data: [10, 15, 7, 20, 18, 12, 9],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2
        }
    });
    new Chart(document.getElementById('dailyTransactionChart'), {
    type: 'line',
    data: {
        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        datasets: [{
            label: 'Transaksi Harian',
            data: [12, 18, 9, 20, 22, 15, 10], // contoh data
            borderWidth: 2,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2,
        plugins: {
            legend: {
                display: true
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
    });
    </script>
</body>
</html>
