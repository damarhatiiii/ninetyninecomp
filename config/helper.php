<?php
// Helper functions untuk generate ID

function generateId($conn, $prefix, $table, $id_column) {
    // Ambil ID terakhir
    $query = "SELECT $id_column FROM $table ORDER BY $id_column DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $last = mysqli_fetch_assoc($result);
        $last_id = $last[$id_column];
        
        // Extract number dari ID terakhir
        if (preg_match('/(\d+)$/', $last_id, $matches)) {
            $num = (int)$matches[1] + 1;
        } else {
            $num = 1;
        }
    } else {
        $num = 1;
    }
    
    // Format: PREFIX + 3 digit number (contoh: TRX001, CUS001)
    return $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);
}

// Generate ID untuk transaksi
function generateIdTransaksi($conn) {
    return generateId($conn, 'TRX', 'transaksi', 'id_transaksi');
}

// Generate ID untuk detail transaksi
function generateIdDetail($conn) {
    return generateId($conn, 'DTL', 'detail_transaksi', 'id_detail');
}

// Generate ID untuk customer
function generateIdCustomer($conn) {
    return generateId($conn, 'CUS', 'customer', 'id_customer');
}

// Generate ID untuk supplier
function generateIdSupplier($conn) {
    return generateId($conn, 'SUP', 'supplier', 'id_supplier');
}

// Generate ID untuk barang masuk
function generateIdMasuk($conn) {
    return generateId($conn, 'BM', 'barang_masuk', 'id_masuk');
}

// Generate ID untuk barang keluar
function generateIdKeluar($conn) {
    return generateId($conn, 'BK', 'barang_keluar', 'id_keluar');
}

?>

