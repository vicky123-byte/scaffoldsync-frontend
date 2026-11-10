<?php
require_once __DIR__ . '/../config/config.php';
cekLogin();

// hapus order
if (isset($_GET['aksi']) && $_GET['aksi'] === 'hapus' && isset($_GET['id'])) {
    $id_order_hapus = mysqli_real_escape_string($conn, $_GET['id']);
    
    // menghapus data kontrak yang berelasi dengan id_order
    mysqli_query($conn, "DELETE FROM kontrak WHERE id_order = '$id_order_hapus'");
    
    // menghapus data pembayaran yang berelasi dengan id_order
    mysqli_query($conn, "DELETE FROM pembayaran WHERE id_order = '$id_order_hapus'");
    
    // menghapus Hapus data detail order jika ada
    mysqli_query($conn, "DELETE FROM detail_order WHERE id_order = '$id_order_hapus'");
    
    // menghapus data order utama
    $hapus = mysqli_query($conn, "DELETE FROM orders WHERE id_order = '$id_order_hapus'");
    
    if ($hapus) {
        $b = isset($_GET['bulan']) ? (int)$_GET['bulan'] : date('m');
        $t = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');
        header("Location: laporan.php?bulan=$b&tahun=$t&status=hapus_sukses");
        exit;
    }
}

$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');

$nama_bulan = [
    1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
    5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
    9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
];

// Mengambil log transaksi order berdasarkan bulan dan tahun yang dipilih
$sql = "SELECT o.id_order, c.nama_lengkap, o.lokasi_proyek,
               o.tgl_order, o.status,
               SUM(IF(p.status = 'Lunas', p.jumlah_bayar, 0)) AS total_bayar
        FROM orders o
        JOIN customer   c ON o.id_customer = c.id_customer
        LEFT JOIN pembayaran p ON o.id_order = p.id_order
        WHERE MONTH(o.tgl_order) = $bulan
          AND YEAR(o.tgl_order)  = $tahun
        GROUP BY o.id_order
        ORDER BY o.tgl_order ASC";

$result = mysqli_query($conn, $sql);

$grand_total = 0;
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $grand_total += $row['total_bayar'];
    $rows[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Periodik – ADE Scaffolding</title>
    <link rel="stylesheet" href="../Assets/style.css">
</head>
<body>

<?php include '../login/sidebar.php'; ?>

<div class="main">
    <div class="topbar">
        <h1>Laporan Periodik</h1>
        <a href="../login/logout.php" class="logout">Keluar</a>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'hapus_sukses'): ?>
        <div class="alert alert-success">Data transaksi berhasil dihapus dari sistem.</div>
    <?php endif; ?>

    <div class="card">
        <form method="GET" style="display:flex; gap:16px; align-items:flex-end; flex-wrap:wrap;">
            <div class="form-group" style="margin-bottom:0;">
                <label>Bulan</label>
                <select name="bulan">
                    <?php foreach ($nama_bulan as $num => $nama): ?>
                        <option value="<?= $num ?>" <?= $num === $bulan ? 'selected' : '' ?>><?= $nama ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label>Tahun</label>
                <select name="tahun">
                    <?php for ($y = date('Y'); $y >= date('Y') - 4; $y--): ?>
                        <option value="<?= $y ?>" <?= $y === $tahun ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h3 style="margin-bottom:16px; color:#1e3a5f; font-size:16px; font-weight:700;">
            Rekap Order – <?= $nama_bulan[$bulan] ?> <?= $tahun ?>
        </h3>

        <?php if (empty($rows)): ?>
            <p style="color:#888; font-size:14px;">Tidak ada transaksi pada bulan ini.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Order</th>
                        <th>Pelanggan</th>
                        <th>Lokasi Proyek</th>
                        <th>Tgl Order</th>
                        <th>Status</th>
                        <th>Total Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $no => $row): 
                        $alamat_murni = $row['lokasi_proyek'];
                        if (strpos($row['lokasi_proyek'], ' (Mulai:') !== false) {
                            $parts = explode(' (Mulai:', $row['lokasi_proyek']);
                            $alamat_murni = $parts[0];
                        }
                    ?>
                    <tr>
                        <td><?= $no + 1 ?></td>
                        <td><?= $row['id_order'] ?></td>
                        <td><?= $row['nama_lengkap'] ?></td>
                        <td><?= htmlspecialchars($alamat_murni) ?></td>
                        <td><?= date('d/m/Y', strtotime($row['tgl_order'])) ?></td>
                        <td><?= $row['status'] ?></td>
                        <td>Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                        <td>
                            <a href="laporan.php?aksi=hapus&id=<?= $row['id_order'] ?>&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>" 
                               class="btn btn-danger btn-sm">
                               Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" style="text-align:right; font-weight:700; padding:12px;">Total Pendapatan</td>
                        <td style="font-weight:700; color:#2563eb; padding:12px;">
                            Rp <?= number_format($grand_total, 0, ',', '.') ?>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        <?php endif; ?>
    </div>
</div>

</body>
</html>