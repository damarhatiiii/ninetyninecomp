<?php
session_start();

// Proses login jika ada POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    include 'config/db.php';
    
    $login_input = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!empty($login_input) && !empty($password)) {
        // Cek apakah input adalah angka (untuk id_karyawan)
        $is_numeric = is_numeric($login_input);
        
        // Gunakan prepared statement untuk mencari berdasarkan id_karyawan, username, atau nama
        if ($is_numeric) {
            // Jika numeric, cari berdasarkan id_karyawan (integer) atau username/nama (string)
            $stmt = mysqli_prepare($conn, "SELECT * FROM karyawan WHERE id_karyawan = ? OR username = ? OR nama = ?");
            if ($stmt) {
                // Convert ke integer untuk id_karyawan, string untuk yang lain
                $id_karyawan = (int)$login_input;
                mysqli_stmt_bind_param($stmt, "iss", $id_karyawan, $login_input, $login_input);
            }
        } else {
            // Jika bukan numeric, hanya cari berdasarkan username atau nama
            $stmt = mysqli_prepare($conn, "SELECT * FROM karyawan WHERE username = ? OR nama = ?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ss", $login_input, $login_input);
            }
        }
        
        if ($stmt) {
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $data = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            
            if ($data && password_verify($password, $data['password'])) {
                $_SESSION['username'] = $data['username'];
                $_SESSION['nama'] = $data['nama'];
                $_SESSION['role'] = $data['role'];
                $_SESSION['id_karyawan'] = $data['id_karyawan'];
                
                header("Location: pages/dashboard.php");
                exit;
            }
        }
    }
    
    // Jika login gagal
    $error = "ID Karyawan, Username, Nama, atau password salah!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Toko Komputer</title>
    <link href="assets/css/output.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <?php if (isset($_GET['success'])): ?>
    <div role="alert" class="alert alert-success mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Data berhasil disimpan!</span>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) || isset($error)): ?>
    <div role="alert" class="alert alert-error mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2 2m0 0l2-2m-2 2V10m11 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span><?= isset($error) ? $error : 'ID Karyawan, Username, Nama, atau password salah!'; ?></span>
    </div>
    <?php endif; ?>

    <div class="flex min-h-full flex-col justify-center px-12 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="mt-15 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Login Karyawan</h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form action="" method="POST" class="space-y-6">
    <div>
        <label for="username" class="block text-sm/6 font-medium text-gray-900">ID Karyawan / Username / Nama</label>
        <div class="mt-2">
        <input name="username" type="text" required autocomplete="username"
            placeholder="Masukkan ID, Username, atau Nama"
            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
        </div>
        <p class="mt-1 text-xs text-gray-500">Anda bisa login menggunakan ID Karyawan, Username, atau Nama</p>
    </div>

    <div>
        <label for="password" class="block text-sm/6 font-medium text-gray-900">Password</label>
        <div class="mt-2">
        <input name="password" type="password" required autocomplete="current-password"
            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
        </div>
    </div>

    <div>
        <button name="login" type="submit"
        class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
        Sign in
        </button>
    </div>
    </form>
    </div>
    </div>
<?php if (!empty($error)) echo "<p style='color:red'>$error</p>"; ?>
</body>
</html>

