<?php
include 'config/db.php';

$id = $_GET['id'];

$delete = mysqli_query($conn, "DELETE FROM produk WHERE id_produk='$id'");

if ($delete) {
    header("Location: produk.php?msg=deleted");
} else {
    echo "Gagal menghapus: " . mysqli_error($conn);
}
?>