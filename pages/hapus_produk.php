<?php
session_start();
include '../config/db.php';

// Pastikan user login dulu
if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit;
}

$id = $_GET['id'];

$delete = mysqli_query($conn, "DELETE FROM produk WHERE id_produk='$id'");

if ($delete) {
    header("Location: produk.php?msg=deleted");
} else {
    echo "Gagal menghapus: " . mysqli_error($conn);
}
?>