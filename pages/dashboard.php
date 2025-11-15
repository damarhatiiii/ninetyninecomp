<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
    
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">

    <?php include '../includes/navbar.php'; ?>

    <main class="flex flex-col items-center justify-center min-h-[calc(100vh-80px)]">
        <h1 class="text-4xl font-bold">Halo, <?= $_SESSION['nama']; ?> 👋</h1>
    </main>

    <?php include '../includes/footbar.php'; ?>
</body>
</html>
