<?php
session_start();
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $q = mysqli_query($conn, "SELECT * FROM karyawan WHERE username='$username'");
    $data = mysqli_fetch_assoc($q);

    if ($data && password_verify($password, $data['password'])) {

        $_SESSION['username'] = $data['username'];
        $_SESSION['nama']     = $data['nama'];
        $_SESSION['role']     = $data['role'];

        header("Location: pages/dashboard.php");
        exit;

    } else {
        header("Location: auth/login.php?error=1");
        exit;
    }
}
?>
