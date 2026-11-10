<?php
session_start();

$host   = "localhost";
$user   = "root";
$pass   = "";
$db     = "db_Scafolding";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Fungsi cek login
function cekLogin() {
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit;
    }
}
?>
