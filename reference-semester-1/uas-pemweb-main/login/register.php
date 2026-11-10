<?php
require_once __DIR__ . '/../config/config.php';

// memastikan session sudah berjalan jika belum, di mulai di config.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $no_telp      = trim($_POST['no_telp']);
    $alamat       = trim($_POST['alamat']);
    $username     = trim($_POST['username']);
    $password     = trim($_POST['password']);

    // semua field wajib terisi
    if (empty($nama_lengkap) || empty($no_telp) || empty($alamat) || empty($username) || empty($password)) {
        $error = "Semua field wajib diisi.";
    } else {
        $nama_lengkap = mysqli_real_escape_string($conn, $nama_lengkap);
        $no_telp      = mysqli_real_escape_string($conn, $no_telp);
        $alamat       = mysqli_real_escape_string($conn, $alamat);
        $username     = mysqli_real_escape_string($conn, $username);
        $password     = mysqli_real_escape_string($conn, $password);

        //Cek apakah username sudah digunakan di tabel pegawai atau customer
        $cek_pegawai  = mysqli_query($conn, "SELECT id_pegawai FROM pegawai WHERE username = '$username' LIMIT 1");
        $cek_customer = mysqli_query($conn, "SELECT id_customer FROM customer WHERE username = '$username' LIMIT 1");

        if (mysqli_num_rows($cek_pegawai) > 0 || mysqli_num_rows($cek_customer) > 0) {
            $error = "Username sudah terdaftar di sistem. Gunakan username lain.";
        } else {
            // Buat ID Customer
            $id_customer = 'CUST' . rand(1000, 9999);

            // 3. Insert ke database
            $sql_register = "INSERT INTO customer (id_customer, nama_lengkap, no_telp, alamat, username, password) 
                             VALUES ('$id_customer', '$nama_lengkap', '$no_telp', '$alamat', '$username', '$password')";

            if (mysqli_query($conn, $sql_register)) {
                $success = "Pendaftaran berhasil! Anda akan dialihkan ke halaman login dalam 2 detik...";
                
                // otomatis pindah ke halaman login jika berhasil
                header("refresh:2;url=login.php");
            } else {
                // tetap di halaman register dan menampilkan error jika gagal
                $error = "Gagal melakukan pendaftaran. Silakan coba lagi.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun – ADE Scaffolding</title>
    <link rel="stylesheet" href="../Assets/style.css">
    <style>
        textarea.input-inline {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
        }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-box" style="margin-top: 40px; margin-bottom: 40px;">
        <h2>ADE Scaffolding</h2>
        <p class="sub">Pendaftaran Akun Customer Baru</p>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success" style="background: #dcfce7; color: #16a34a; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; border-left: 4px solid #16a34a;"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap Anda" value="<?= isset($_POST['nama_lengkap']) ? htmlspecialchars($_POST['nama_lengkap']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label>Nomor Telepon / WhatsApp</label>
                <input type="tel" name="no_telp" placeholder="Contoh: 08123456789" value="<?= isset($_POST['no_telp']) ? htmlspecialchars($_POST['no_telp']) : '' ?>" required>
            </div>
            
            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" rows="3" class="input-inline" placeholder="Masukkan alamat lengkap pengiriman/proyek" required><?= isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : '' ?></textarea>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Buat username baru" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Buat password aman" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; margin-top:12px;">
                Daftar Akun Baru
            </button>
        </form>

        <p style="text-align: center; margin-top: 20px; font-size: 13px; color: #64748b;">
            Sudah punya akun? <a href="login.php" style="color: #2563eb; text-decoration: none; font-weight: bold;">Masuk di sini</a>
        </p>
    </div>
</div>
</body>
</html>