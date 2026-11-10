<?php
require_once __DIR__ . '/../config/config.php';
cekLogin();

$bulan = (int)date('m');
$tahun = (int)date('Y');

$nama_bulan = [
    1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
    5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
    9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
];

$total_barang    = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM barang"))[0];
$total_pelanggan = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM customer"))[0];
$total_order     = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM orders WHERE MONTH(tgl_order)=$bulan AND YEAR(tgl_order)=$tahun"))[0];

// ringkasan keuangan
$res_keuangan = mysqli_query($conn, "
    SELECT COALESCE(SUM(p.jumlah_bayar), 0) AS total_pendapatan,
           COUNT(DISTINCT p.id_order)        AS order_terbayar
    FROM pembayaran p
    JOIN orders o ON p.id_order = o.id_order
    WHERE MONTH(o.tgl_order) = $bulan AND YEAR(o.tgl_order) = $tahun
      AND p.status = 'lunas'
");
$keuangan = mysqli_fetch_assoc($res_keuangan);

// ringkasan manajemen proyek
$proyek_aktif  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM orders WHERE status='aktif'"))[0];
$proyek_selesai = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM orders WHERE status='selesai' AND MONTH(tgl_order)=$bulan AND YEAR(tgl_order)=$tahun"))[0];
$proyek_pending = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM orders WHERE status='pending'"))[0];

// order terbaru
$order_terbaru = mysqli_query($conn, "
    SELECT o.id_order, c.nama_lengkap, o.lokasi_proyek, o.tgl_order, o.status
    FROM orders o
    JOIN customer c ON o.id_customer = c.id_customer
    ORDER BY o.tgl_order DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard – ADE Scaffolding</title>
    <link rel="stylesheet" href="../Assets/style.css">
</head>
<body>

<?php include '../login/sidebar.php'; ?>

<div class="main">
    <div class="topbar">
        <h1>Dashboard</h1>
        <a href="../login/logout.php" class="logout">Keluar</a>
    </div>

    <p style="margin-bottom:20px; color:#888; font-size:13px;">
        Selamat datang, <strong><?= $_SESSION['nama'] ?? $_SESSION['user'] ?></strong> &mdash;
        <?= $nama_bulan[$bulan] ?> <?= $tahun ?>
    </p>

    <div class="stats">
        <div class="stat-card">
            <h3><?= $total_barang ?></h3>
            <p>Total Jenis Barang</p>
        </div>
        <div class="stat-card">
            <h3><?= $total_pelanggan ?></h3>
            <p>Total Pelanggan</p>
        </div>
        <div class="stat-card">
            <h3><?= $total_order ?></h3>
            <p>Order Bulan Ini</p>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:24px;">

        <!-- Ringkasan Keuangan -->
        <div class="card">
            <h3 style="color:#1e3a5f; margin-bottom:16px; font-size:15px;">
                Transaksi Keuangan – <?= $nama_bulan[$bulan] ?>
            </h3>
            <table>
                <tr>
                    <td style="color:#888; padding:8px 0;">Total Pendapatan</td>
                    <td style="font-weight:700; color:#2563eb; text-align:right;">
                        Rp <?= number_format($keuangan['total_pendapatan'], 0, ',', '.') ?>
                    </td>
                </tr>
                <tr>
                    <td style="color:#888; padding:8px 0;">Order Terbayar (Lunas)</td>
                    <td style="font-weight:700; text-align:right;"><?= $keuangan['order_terbayar'] ?></td>
                </tr>
                <tr>
                    <td style="color:#888; padding:8px 0;">Total Order Bulan Ini</td>
                    <td style="font-weight:700; text-align:right;"><?= $total_order ?></td>
                </tr>
            </table>
            <a href="laporan.php" style="display:inline-block; margin-top:12px; font-size:13px; color:#2563eb;">
                Lihat laporan lengkap &rarr;
            </a>
        </div>

        <!-- Ringkasan Manajemen Proyek -->
        <div class="card">
            <h3 style="color:#1e3a5f; margin-bottom:16px; font-size:15px;">
                Manajemen Proyek
            </h3>
            <table>
                <tr>
                    <td style="color:#888; padding:8px 0;">Proyek Aktif</td>
                    <td style="text-align:right;">
                        <span style="background:#dcfce7; color:#16a34a; padding:2px 10px; border-radius:20px; font-size:13px; font-weight:600;">
                            <?= $proyek_aktif ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="color:#888; padding:8px 0;">Pending</td>
                    <td style="text-align:right;">
                        <span style="background:#fef9c3; color:#ca8a04; padding:2px 10px; border-radius:20px; font-size:13px; font-weight:600;">
                            <?= $proyek_pending ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="color:#888; padding:8px 0;">Selesai Bulan Ini</td>
                    <td style="text-align:right;">
                        <span style="background:#e0e7ff; color:#4338ca; padding:2px 10px; border-radius:20px; font-size:13px; font-weight:600;">
                            <?= $proyek_selesai ?>
                        </span>
                    </td>
                </tr>
            </table>
        </div>

    </div>

    <!-- Order Terbaru -->
    <div class="card">
        <h3 style="color:#1e3a5f; margin-bottom:16px; font-size:15px;">Order Terbaru</h3>
        <table>
            <thead>
                <tr>
                    <th>ID Order</th>
                    <th>Pelanggan</th>
                    <th>Lokasi Proyek</th>
                    <th>Tgl Order</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($order_terbaru)): ?>
                <tr>
                    <td><?= $row['id_order'] ?></td>
                    <td><?= $row['nama_lengkap'] ?></td>
                    <td><?= $row['lokasi_proyek'] ?></td>
                    <td><?= date('d/m/Y', strtotime($row['tgl_order'])) ?></td>
                    <td>
                        <?php
                        $warna = match($row['status']) {
                            'aktif'   => 'background:#dcfce7;color:#16a34a',
                            'pending' => 'background:#fef9c3;color:#ca8a04',
                            'selesai' => 'background:#e0e7ff;color:#4338ca',
                            default   => 'background:#f1f5f9;color:#64748b'
                        };
                        ?>
                        <span style="<?= $warna ?>; padding:2px 10px; border-radius:20px; font-size:12px; font-weight:600;">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
