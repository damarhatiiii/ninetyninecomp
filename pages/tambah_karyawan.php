<?php
session_start();
include '../config/db.php';

// Cek login
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Karyawan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">

<?php include '../includes/navbar.php'; ?>

<div class="max-w-md mx-auto mt-20 bg-gray-800 p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-bold mb-4">Tambah Karyawan</h2>

    <form action="tambah_karyawan_proses.php" method="POST">
        <label class="block mb-2">Nama</label>
        <input type="text" name="nama" required
            class="w-full p-2 mb-4 rounded bg-gray-700 text-white">

        <label class="block mb-2">Username</label>
        <input type="text" name="username" required
            class="w-full p-2 mb-4 rounded bg-gray-700 text-white">

        <label class="block mb-2">Password</label>
        <input type="password" name="password" required
            class="w-full p-2 mb-4 rounded bg-gray-700 text-white">

        <label class="block mb-2">Role</label>
        <select name="role" required class="w-full p-2 mb-4 rounded bg-gray-700 text-white">
            <option value="admin">Admin</option>
            <option value="staf">Staf</option>
        </select>

        <button class="bg-blue-700 hover:bg-blue-800 w-full py-2 rounded-lg font-semibold">
            Simpan
        </button>
    </form>
</div>

<?php include '../includes/footbar.php'; ?>

</body>
</html>
