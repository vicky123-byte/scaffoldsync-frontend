<?php
require_once __DIR__ . '/../config/config.php';

// memastikan session sudah berjalan jika belum, di mulai di config.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // melakukan cek ke tabel PEGAWAI
    $sql_pegawai = "SELECT * FROM pegawai WHERE username = '$username' LIMIT 1";
    $res_pegawai = mysqli_query($conn, $sql_pegawai);
    $user_pegawai = mysqli_fetch_assoc($res_pegawai);

    if ($user_pegawai && $password === $user_pegawai['password']) {
        // Jika ketemu di pegawai, simpan session pegawai
        $_SESSION['role'] = 'pegawai';
        $_SESSION['peran'] = $user_pegawai['peran'];
        $_SESSION['user'] = $user_pegawai['username'];
        $_SESSION['id_user'] = $user_pegawai['id_pegawai'];
        $_SESSION['nama'] = $user_pegawai['nama_lengkap'];
        
        // Arahkan ke dashboard admin
        header("Location: ../Admin/dashboard.php");
        exit;
    } 
    
    // Jika tidak ada di pegawai, cek ke tabel customer
    $sql_customer = "SELECT * FROM customer WHERE username = '$username' LIMIT 1";
    $res_customer = mysqli_query($conn, $sql_customer);
    $user_customer = mysqli_fetch_assoc($res_customer);

    if ($user_customer && $password === $user_customer['password']) {
        $_SESSION['role'] = 'customer';
        $_SESSION['user'] = $user_customer['username'];
        $_SESSION['id_user'] = $user_customer['id_customer'];
        $_SESSION['nama'] = $user_customer['nama_lengkap'];
        
        // Arahkan ke dashboard customer
        header("Location: ../customer/index.php");
        exit;
    }

    // Jika kedua pengecekan gagal
    $error = "Username atau password salah.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login – ADE Scaffolding</title>
    <link rel="stylesheet" href="../Assets/style.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-box">
        <h2>ADE Scaffolding</h2>
        <p class="sub">Sistem Manajemen Penyewaan</p>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; margin-top:8px;">
                Masuk
            </button>
        </form>

        <p style="text-align: center; margin-top: 25px; font-size: 13px; color: #64748b;">
            Belum punya akun? <a href="register.php" style="color: #2563eb; text-decoration: none; font-weight: bold;">Daftar Sekarang</a>
        </p>
    </div>
</div>
</body>
</html>