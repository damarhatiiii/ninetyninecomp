<?php
session_start();
include '../config/db.php';

// Pastikan user login dulu
if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit;
}

$nama = $_POST['nama'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];

$query = "INSERT INTO karyawan (nama, username, password, role) 
            VALUES ('$nama', '$username', '$password', '$role')";

mysqli_query($conn, $query);

header("Location: karyawan.php?msg=sukses");
exit;
