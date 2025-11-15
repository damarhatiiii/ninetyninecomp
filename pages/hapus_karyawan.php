<?php
include 'config/db.php';

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM karyawan WHERE id_karyawan = '$id'");

header("Location: karyawan.php?deleted=1");
exit;
?>