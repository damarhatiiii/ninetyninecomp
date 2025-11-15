<?php
session_start();
include '../config/db.php';
include '../config/helper.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit;
}

$id_supplier = generateIdSupplier($conn);
$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
$telepon = (int)$_POST['telepon'];
$email = mysqli_real_escape_string($conn, $_POST['email']);

$query = "INSERT INTO supplier (id_supplier, nama, alamat, email, telepon) 
          VALUES ('$id_supplier', '$nama', '$alamat', '$email', $telepon)";

if (mysqli_query($conn, $query)) {
    header("Location: supplier.php?success=1");
} else {
    header("Location: supplier.php?error=1");
}
exit;
?>

