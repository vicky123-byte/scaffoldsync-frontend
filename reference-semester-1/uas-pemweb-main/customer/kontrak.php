<?php
require_once __DIR__ . '/../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login/login.php");
    exit;
}

$id_order = isset($_GET['id_order']) ? $_GET['id_order'] : '';
$durasi = isset($_GET['durasi']) ? (int) $_GET['durasi'] : 1;
$tgl_mulai = isset($_GET['tgl']) ? $_GET['tgl'] : date('Y-m-d');

// Konversi tanggal deadline ke format Indonesia menggunakan array bulan
$bln_indo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$timestamp = strtotime("+$durasi month", strtotime($tgl_mulai));
$deadline = date('d', $timestamp) . ' ' . $bln_indo[date('n', $timestamp) - 1] . ' ' . date('Y', $timestamp);

$cust_query = mysqli_query($conn, "SELECT * FROM customer WHERE id_customer='$_SESSION[id_user]'");
$cust = mysqli_fetch_assoc($cust_query);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pernyataan Lembar Kontrak</title>
    <link rel="stylesheet" href="../Assets/styleCustomer.css">
</head>

<body>
    <div class="container-kontrak">
        <div class="card-kontrak">
            <h2 class="title-kontrak">SURAT PERNYATAAN KONTRAK SEWA</h2>
            <p class="sub-title">Nomor Dokumen Pengajuan: Pengesahan Atas Kode <?= htmlspecialchars($id_order); ?></p>

            <div class="identitas-box">
                <p><strong>Nama Pihak Kedua (Penyewa):</strong> <?= htmlspecialchars($cust['nama_lengkap']); ?></p>
                <p><strong>Nomor Kontak Aktif:</strong> <?= htmlspecialchars($cust['no_telp']); ?></p>
                <p><strong>Batas Akhir Pemakaian Alat (Deadline):</strong> <span class="text-deadline"><?= $deadline; ?></span></p>
            </div>

            <div class="kontrak-text">
                Saya yang tercantum di atas menyatakan <strong>setuju dan bertanggung jawab penuh</strong> atas komponen
                scaffolding yang disewa dari pihak <strong>ADE Scaffolding</strong>. Saya bersedia memelihara kondisi
                fisik unit material, memastikan keamanan di area lokasi proyek, serta sanggup menyelesaikan segala
                kewajiban retribusi pembayaran sewa bulanan paling lambat pada tanggal jatuh tempo batas akhir
                pengembalian yang tertera di atas. Jika terjadi kehilangan barang atau keterlambatan konfirmasi
                pengembalian di luar koordinasi Petugas Lapangan, saya bersedia menerima sanksi administrasi denda
                sesuai ketentuan hukum operasional usaha yang berlaku.
            </div>

            <a href="index.php?status=sukses_order" class="btn-finish">Saya Mengerti & Selesaikan Transaksi ✓</a>
        </div>
    </div>
</body>

</html>