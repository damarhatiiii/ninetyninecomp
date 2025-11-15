<?php
session_start();
include '../config/db.php';

// Pastikan user login dulu
if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit;
}

$id_produk = $_POST['id_produk'];
$stok = (int) $_POST['stok'];

$update = mysqli_query($conn, 
    "UPDATE produk SET stok=$stok WHERE id_produk='$id_produk'"
);

if ($update) {
    header("Location: produk.php?msg=stok_updated");
} else {
    echo "Gagal update stok: " . mysqli_error($conn);
}
?>
