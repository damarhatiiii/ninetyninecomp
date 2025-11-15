<?php
session_start();
include '../config/db.php';

// Pastikan user login dulu
if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit;
}

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM karyawan WHERE id_karyawan = '$id'");

header("Location: karyawan.php?deleted=1");
exit;
?>