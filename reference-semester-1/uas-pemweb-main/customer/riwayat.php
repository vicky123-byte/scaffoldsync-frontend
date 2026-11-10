<?php
require_once __DIR__ . '/../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login/login.php");
    exit;
}

$id_customer = $_SESSION['id_user'];

// Proses upload bukti transfer pembayaran
if (isset($_POST['submit_bukti'])) {
    $id_order = $_POST['id_order'];
    $id_pembayaran = $_POST['id_pembayaran'];
    $metode_bayar = mysqli_real_escape_string($conn, $_POST['metode_bayar']);

    if (isset($_FILES['bukti_foto']) && $_FILES['bukti_foto']['error'] === 0) {
        $nama_file = $_FILES['bukti_foto']['name'];
        $tmp_nama = $_FILES['bukti_foto']['tmp_name'];
        $folder_target = "../uploads/";
        
        if (!is_dir($folder_target)) {
            mkdir($folder_target, 0777, true);
        }

        $nama_file_baru = time() . "_" . str_replace(' ', '_', $nama_file);
        $jalur_simpan = $folder_target . $nama_file_baru;

        if (move_uploaded_file($tmp_nama, $jalur_simpan)) {
            $tgl_sekarang = date('Y-m-d H:i:s');
            // Reset status pembayaran menjadi Pending lagi agar admin bisa verifikasi ulang
            mysqli_query($conn, "UPDATE pembayaran SET metode_bayar='$metode_bayar', bukti_bayar='$jalur_simpan', tgl_bayar='$tgl_sekarang', status='Pending' WHERE id_pembayaran='$id_pembayaran'");

            header("Location: riwayat.php?status=sukses_upload");
            exit;
        }
    }
}

// Ambil data riwayat order kecuali dengan status batal
$query_riwayat = mysqli_query($conn, "
    SELECT o.id_order, o.tgl_order, o.lokasi_proyek, o.status AS status_order,
           p.id_pembayaran, p.jumlah_bayar, p.metode_bayar, p.status AS status_bayar, p.bukti_bayar,
           peg.nama_lengkap AS nama_petugas, peg.no_telp AS telp_petugas
    FROM orders o
    LEFT JOIN pembayaran p ON o.id_order = p.id_order
    LEFT JOIN kontrak k ON o.id_order = k.id_order
    LEFT JOIN pegawai peg ON k.id_pegawai = peg.id_pegawai
    WHERE o.id_customer = '$id_customer' AND o.status != 'Batal'
    ORDER BY o.tgl_order DESC
");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Order Anda</title>
    <link class="sheet" rel="stylesheet" href="../Assets/styleCustomer.css">
</head>

<body>
    <div class="container-riwayat">
        <header class="header-riwayat">
            <h1>Riwayat Penyewaan & Status Invoice</h1>
            <a href="index.php">← Kembali ke Katalog</a>
        </header>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'sukses_upload'): ?>
            <div class="alert">✓ Struk pembayaran berhasil di-upload! Admin akan segera memeriksa mutasi bank.</div>
        <?php endif; ?>

        <div class="card-riwayat">
            <table class="table-riwayat">
                <thead>
                    <tr>
                        <th>ID Order</th>
                        <th>Tanggal Order</th>
                        <th>Lokasi Detail Proyek</th>
                        <th>Total Tagihan</th>
                        <th>Petugas Lapangan</th>
                        <th>Status Verifikasi</th>
                        <th>Aksi Pengiriman Bukti Transfer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($query_riwayat) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query_riwayat)): ?>
                            <tr>
                                <td><strong><?= $row['id_order']; ?></strong></td>
                                <td><?= date('d/m/Y H:i', strtotime($row['tgl_order'])); ?></td>
                                <td><?= htmlspecialchars($row['lokasi_proyek']); ?></td>
                                <td class="td-tagihan">Rp <?= number_format($row['jumlah_bayar'], 0, ',', '.'); ?></td>

                                <td>
                                    <?php if (!empty($row['nama_petugas'])): ?>
                                        <div class="info-petugas">
                                            <strong>🙋‍♂️ <?= htmlspecialchars($row['nama_petugas']); ?></strong>
                                            <span>📞 <?= htmlspecialchars($row['telp_petugas']); ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-belum-jadwal">Belum dijadwalkan</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if ($row['status_bayar'] == 'Lunas'): ?>
                                        <span class="badge badge-lunas">Lunas</span>
                                    <?php elseif ($row['status_bayar'] == 'Ditolak'): ?>
                                        <span class="badge badge-batal">Ditolak / Salah</span>
                                    <?php elseif ($row['status_bayar'] == 'Pending' && !empty($row['bukti_bayar'])): ?>
                                        <span class="badge badge-pending">Menunggu Validasi</span>
                                    <?php else: ?>
                                        <span class="badge badge-batal">Belum Upload Bukti</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php /* Tampilkan form jika bukti kosong ATAU statusnya ditolak oleh admin */ ?>
                                    <?php if (empty($row['bukti_bayar']) || $row['status_bayar'] == 'Ditolak'): ?>
                                        <form method="POST" action="" enctype="multipart/form-data" class="form-upload">
                                            <input type="hidden" name="id_order" value="<?= $row['id_order']; ?>">
                                            <input type="hidden" name="id_pembayaran" value="<?= $row['id_pembayaran']; ?>">

                                            <select name="metode_bayar" class="select-metode" required>
                                                <option value="Transfer BCA">Transfer M-Banking</option>
                                                <option value="Transfer Mandiri">Tunai</option>
                                            </select>
                                            <input type="file" name="bukti_foto" accept="image/*" class="input-file" required>
                                            <button type="submit" name="submit_bukti" class="btn-upload">Upload Ulang</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-success-upload">✓ Bukti Sudah Diterima</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="td-empty-riwayat">Anda belum memiliki riwayat pesanan sewa.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>