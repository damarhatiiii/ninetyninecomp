<?php
/**
 * Update Profil Proses
 * 
 * File ini HANYA untuk mengupdate profil (nama, username, email, foto_profil)
 * PENTING: File ini TIDAK mengubah password sama sekali untuk mencegah double hashing
 * 
 * Untuk mengubah password, gunakan: ubah_password_proses.php
 */

session_start();
include '../../config/db.php';

// Pastikan user login dulu
if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_PATH . '/auth/login.php');
    exit;
}

// Validasi method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: edit_profil.php?error=invalid_method');
    exit;
}

$id_karyawan = $_SESSION['id_karyawan'];
$nama = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');

// PENTING: Pastikan tidak ada field password yang dikirim dari form
// Jika ada, abaikan untuk mencegah double hashing
unset($_POST['password']);
unset($_POST['password_lama']);
unset($_POST['password_baru']);
unset($_POST['password_konfirmasi']);

// Validasi data tidak kosong
if (empty($nama) || empty($username)) {
    header('Location: edit_profil.php?error=field_kosong');
    exit;
}

// Cek apakah username sudah digunakan oleh user lain
$cek_username = mysqli_prepare($conn, "SELECT id_karyawan FROM karyawan WHERE username = ? AND id_karyawan != ?");
mysqli_stmt_bind_param($cek_username, "ss", $username, $id_karyawan);
mysqli_stmt_execute($cek_username);
$result_cek = mysqli_stmt_get_result($cek_username);

if (mysqli_num_rows($result_cek) > 0) {
    mysqli_stmt_close($cek_username);
    header('Location: edit_profil.php?error=username_ada');
    exit;
}
mysqli_stmt_close($cek_username);

// Cek apakah kolom email sudah ada, jika belum tambahkan
$check_email = mysqli_query($conn, "SHOW COLUMNS FROM karyawan LIKE 'email'");
if (mysqli_num_rows($check_email) == 0) {
    mysqli_query($conn, "ALTER TABLE karyawan ADD COLUMN email VARCHAR(255) DEFAULT NULL");
}

// Cek apakah kolom tanggal_dibuat sudah ada, jika belum tambahkan
$check_tanggal = mysqli_query($conn, "SHOW COLUMNS FROM karyawan LIKE 'tanggal_dibuat'");
if (mysqli_num_rows($check_tanggal) == 0) {
    mysqli_query($conn, "ALTER TABLE karyawan ADD COLUMN tanggal_dibuat DATE DEFAULT NULL");
}

// Cek apakah kolom foto_profil sudah ada, jika belum tambahkan
$check_foto = mysqli_query($conn, "SHOW COLUMNS FROM karyawan LIKE 'foto_profil'");
if (mysqli_num_rows($check_foto) == 0) {
    mysqli_query($conn, "ALTER TABLE karyawan ADD COLUMN foto_profil VARCHAR(255) DEFAULT NULL");
}

// Handle upload foto profil
$foto_profil = null;
$hapus_foto = isset($_POST['hapus_foto']) && $_POST['hapus_foto'] == '1';

// Jika ada file yang diupload
if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == UPLOAD_ERR_OK) {
    $file = $_FILES['foto_profil'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];
    
    // Validasi file
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    if (!in_array($file_extension, $allowed_extensions)) {
        header('Location: edit_profil.php?error=format_file_tidak_sesuai');
        exit;
    }
    
    if ($file_size > 2 * 1024 * 1024) { // 2MB
        header('Location: edit_profil.php?error=ukuran_file_terlalu_besar');
        exit;
    }
    
    // Generate nama file unik
    $new_file_name = $id_karyawan . '_' . time() . '.' . $file_extension;
    $upload_path = '../../assets/profiles/' . $new_file_name;
    
    // Buat folder jika belum ada
    if (!file_exists('../../assets/profiles')) {
        mkdir('../../assets/profiles', 0777, true);
    }
    
    // Hapus foto lama jika ada
    $karyawan_query = mysqli_query($conn, "SELECT foto_profil FROM karyawan WHERE id_karyawan = '$id_karyawan'");
    $karyawan_old = mysqli_fetch_assoc($karyawan_query);
    if (!empty($karyawan_old['foto_profil']) && file_exists('../../assets/profiles/' . $karyawan_old['foto_profil'])) {
        unlink('../../assets/profiles/' . $karyawan_old['foto_profil']);
    }
    
    // Upload file baru
    if (move_uploaded_file($file_tmp, $upload_path)) {
        $foto_profil = $new_file_name;
    } else {
        header('Location: edit_profil.php?error=gagal_upload_file');
        exit;
    }
} elseif ($hapus_foto) {
    // Hapus foto yang ada
    $karyawan_query = mysqli_query($conn, "SELECT foto_profil FROM karyawan WHERE id_karyawan = '$id_karyawan'");
    $karyawan_old = mysqli_fetch_assoc($karyawan_query);
    if (!empty($karyawan_old['foto_profil']) && file_exists('../../assets/profiles/' . $karyawan_old['foto_profil'])) {
        unlink('../../assets/profiles/' . $karyawan_old['foto_profil']);
    }
    $foto_profil = null;
} else {
    // Ambil foto yang sudah ada (tidak diubah)
    $karyawan_query = mysqli_query($conn, "SELECT foto_profil FROM karyawan WHERE id_karyawan = '$id_karyawan'");
    $karyawan_old = mysqli_fetch_assoc($karyawan_query);
    $foto_profil = $karyawan_old['foto_profil'] ?? null;
}

// Update data karyawan
// PENTING: Query ini HANYA mengupdate nama, username, email, dan foto_profil
// Kolom password TIDAK disentuh sama sekali untuk mencegah double hashing
$email_final = !empty($email) ? $email : null;

if ($foto_profil !== null) {
    // Update dengan foto (TIDAK menyentuh password)
    $stmt = mysqli_prepare($conn, "UPDATE karyawan SET nama = ?, username = ?, email = ?, foto_profil = ? WHERE id_karyawan = ?");
    mysqli_stmt_bind_param($stmt, "sssss", $nama, $username, $email_final, $foto_profil, $id_karyawan);
} else {
    // Update tanpa foto (set NULL) - TIDAK menyentuh password
    $stmt = mysqli_prepare($conn, "UPDATE karyawan SET nama = ?, username = ?, email = ?, foto_profil = NULL WHERE id_karyawan = ?");
    mysqli_stmt_bind_param($stmt, "ssss", $nama, $username, $email_final, $id_karyawan);
}

if (mysqli_stmt_execute($stmt)) {
    // Update session
    $_SESSION['nama'] = $nama;
    $_SESSION['username'] = $username;
    
    mysqli_stmt_close($stmt);
    header('Location: edit_profil.php?success=1');
} else {
    mysqli_stmt_close($stmt);
    header('Location: edit_profil.php?error=' . urlencode('Gagal memperbarui profil: ' . mysqli_error($conn)));
}
exit;
?>

