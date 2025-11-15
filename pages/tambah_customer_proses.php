<?php
session_start();
include '../config/db.php';
include '../config/helper.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit;
}

$id_customer = generateIdCustomer($conn);
$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$email = mysqli_real_escape_string($conn, $_POST['email']);

$query = "INSERT INTO customer (id_customer, nama, email) 
          VALUES ('$id_customer', '$nama', '$email')";

if (mysqli_query($conn, $query)) {
    header("Location: customer.php?success=1");
} else {
    header("Location: customer.php?error=1");
}
exit;
?>

