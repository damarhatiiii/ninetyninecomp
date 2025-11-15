<?php
include 'config/db.php';

$nama = $_POST['nama'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];

$query = "INSERT INTO karyawan (nama, username, password, role) 
            VALUES ('$nama', '$username', '$password', '$role')";

mysqli_query($conn, $query);

header("Location: karyawan.php?msg=sukses");
exit;
