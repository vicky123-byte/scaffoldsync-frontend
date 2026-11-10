<?php
require_once __DIR__ . '/../config/config.php';

if (function_exists('cekLogin')) {
    cekLogin();
} else {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['id_user'])) {
        header("Location: ../login/login.php");
        exit;
    }
}

// pembuatan kontrak otomatis dan penunjukan petugas lapangan
$cek_lunas = mysqli_query($conn, "
    SELECT o.*, p.status AS status_bayar 
    FROM orders o
    JOIN pembayaran p ON o.id_order = p.id_order
    WHERE p.status = 'Lunas' AND o.status = 'Pending'
");

if (mysqli_num_rows($cek_lunas) > 0) {
    // Ambil semua ID petugas lapangan yang tersedia untuk diacak
    $res_pegawai = mysqli_query($conn, "SELECT id_pegawai FROM pegawai WHERE peran = 'petugas_lapangan'");
    $list_pegawai = [];
    while ($peg = mysqli_fetch_assoc($res_pegawai)) {
        $list_pegawai[] = $peg['id_pegawai'];
    }

    // Jalankan perulangan untuk setiap orderan yang statusnya baru saja Lunas
    while ($siap = mysqli_fetch_assoc($cek_lunas)) {
        $id_order_siap = $siap['id_order'];
        $id_cust_siap = $siap['id_customer'];

        // Pilih satu id_pegawai secara acak dari array, jika tidak ada petugas default ke NULL
        $id_peg_random = (!empty($list_pegawai)) ? $list_pegawai[array_rand($list_pegawai)] : '';

        $id_kontrak_baru = 'KTK' . rand(100, 999);
        $tgl_kontrak = date('Y-m-d');
        $tgl_jatuh_tempo = date('Y-m-d H:i:s', strtotime('+30 days'));

        // Memasukkan data ke dalam tabel kontrak dengan petugas acak
        $q_auto_kontrak = "INSERT INTO kontrak (id_kontrak, id_order, id_customer, id_pegawai, tgl_kontrak, tgl_jatuh_tempo) 
                           VALUES ('$id_kontrak_baru', '$id_order_siap', '$id_cust_siap', '$id_peg_random', '$tgl_kontrak', '$tgl_jatuh_tempo')";

        if (mysqli_query($conn, $q_auto_kontrak)) {
            // Ubah status order utama menjadi 'Proses Proyek'
            mysqli_query($conn, "UPDATE orders SET status = 'Proses Proyek' WHERE id_order = '$id_order_siap'");
        }
    }

    // Refresh halaman
    header("Location: proyek.php");
    exit;
}

// reject antrean
if (isset($_POST['reject_order'])) {
    $id_order = $_POST['id_order'];

    // Kembalikan status pembayaran ke Ditolak agar form upload di sisi customer terbuka lagi
    $q_reject = "UPDATE pembayaran SET status = 'Ditolak' WHERE id_order = '$id_order'";
    if (mysqli_query($conn, $q_reject)) {
        header("Location: proyek.php?status=sukses_reject");
        exit;
    }
}

// read antrean
$query_order = mysqli_query($conn, "
    SELECT o.*, c.nama_lengkap, p.bukti_bayar, p.status AS status_bayar 
    FROM orders o 
    JOIN customer c ON o.id_customer = c.id_customer 
    JOIN pembayaran p ON o.id_order = p.id_order
    WHERE o.status = 'Pending' AND p.bukti_bayar IS NOT NULL AND p.status != 'Ditolak'
    ORDER BY o.tgl_order DESC
");

$query_kontrak_aktif = mysqli_query($conn, "
    SELECT k.*, c.nama_lengkap AS nama_customer, p.nama_lengkap AS nama_pegawai, o.lokasi_proyek
    FROM kontrak k
    JOIN customer c ON k.id_customer = c.id_customer
    JOIN pegawai p ON k.id_pegawai = p.id_pegawai
    JOIN orders o ON k.id_order = o.id_order
    ORDER BY k.tgl_kontrak DESC
");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin - Manajemen Proyek & Kontrak</title>
    <link rel="stylesheet" href="../Assets/style.css">
</head>

<body>

    <?php include '../login/sidebar.php'; ?>

    <div class="main">
        <div class="topbar">
            <h1>Manajemen Proyek & Pembuatan Kontrak</h1>
            <a href="../login/logout.php" class="logout">Keluar</a>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success">
                <?php
                if ($_GET['status'] == 'sukses_reject')
                    echo "Bukti pembayaran ditolak! Menunggu customer upload ulang.";
                if ($_GET['status'] == 'sukses_update')
                    echo "Detail proyek berhasil diperbarui!";
                if ($_GET['status'] == 'sukses_selesai')
                    echo "Proyek telah ditandai selesai dan ditutup!";
                ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <h3 style="margin-bottom: 15px; color: #1e3a5f;">Antrean Order Masuk (Menunggu Verifikasi Keuangan)</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID Order</th>
                        <th>Customer</th>
                        <th>Lokasi Proyek</th>
                        <th>Status Pembayaran</th>
                        <th>Aksi Validasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($query_order) > 0): ?>
                        <?php while ($order = mysqli_fetch_assoc($query_order)): ?>
                            <tr>
                                <td><strong><?php echo $order['id_order']; ?></strong></td>
                                <td><?php echo htmlspecialchars($order['nama_lengkap']); ?></td>
                                <td><?php echo htmlspecialchars($order['lokasi_proyek']); ?></td>
                                <td>
                                    <span style="color: #f59e0b; font-weight: bold;">⏳ Menunggu Validasi di Keuangan</span>
                                </td>
                                <td>
                                    <form method="POST" action="" style="margin:0;">
                                        <input type="hidden" name="id_order" value="<?php echo $order['id_order']; ?>">
                                        <button type="submit" name="reject_order" class="btn btn-danger btn-sm">Reject
                                            Bukti</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #888; padding: 15px;">Tidak ada antrean order
                                proyek baru saat ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h3 style="margin-bottom: 15px; color: #1e3a5f;">Data Kontrak Berjalan (Manajemen Konstruksi)</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID Kontrak</th>
                        <th>Customer</th>
                        <th>Lokasi</th>
                        <th>Tgl Kontrak</th>
                        <th>Jatuh Tempo</th>
                        <th>Petugas Lapangan (Sistem Random)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($query_kontrak_aktif) > 0): ?>
                        <?php while ($kontrak = mysqli_fetch_assoc($query_kontrak_aktif)): ?>
                            <tr>
                                <td><strong style="color:#2563eb;"><?php echo $kontrak['id_kontrak']; ?></strong></td>
                                <td><?php echo htmlspecialchars($kontrak['nama_customer']); ?></td>
                                <td><?php echo htmlspecialchars($kontrak['lokasi_proyek']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($kontrak['tgl_kontrak'])); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($kontrak['tgl_jatuh_tempo'])); ?></td>
                                <td><span style="color: #475569; font-weight: 500;">👷‍♂️
                                        <?php echo htmlspecialchars($kontrak['nama_pegawai']); ?></span></td>
                                <td>
                                    <a href="editProyek.php?id_kontrak=<?php echo $kontrak['id_kontrak']; ?>"
                                        class="btn btn-warning btn-sm">Edit</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: #888; padding: 15px;">Belum ada kontrak kerja
                                yang diterbitkan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>