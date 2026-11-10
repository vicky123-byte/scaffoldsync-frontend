<?php
require_once __DIR__ . '/../config/config.php';

if (function_exists('cekLogin')) {
    cekLogin(); 
} else {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    if (!isset($_SESSION['id_user'])) { header("Location: ../login/login.php"); exit; }
}

if (isset($_POST['konfirmasi_bayar'])) {
    $id_bayar = $_POST['id_pembayaran'];
    $status_baru = $_POST['status_pembayaran'];

    $cek_status_skrg = mysqli_query($conn, "SELECT id_order, status FROM pembayaran WHERE id_pembayaran = '$id_bayar'");
    $data_pembayaran = mysqli_fetch_assoc($cek_status_skrg);

    if ($data_pembayaran) {
        $id_order = $data_pembayaran['id_order'];
        $status_lama = $data_pembayaran['status'];

        // Ambil id_customer dari order terkait
        $cek_order = mysqli_query($conn, "SELECT id_customer, status FROM orders WHERE id_order = '$id_order'");
        $data_order = mysqli_fetch_assoc($cek_order);
        $id_customer = $data_order['id_customer'];

        // Logika Potong / Kembalikan Stok Barang
        if ($status_lama !== 'Lunas' && $status_baru === 'Lunas') {
            $query_detail = mysqli_query($conn, "SELECT id_barang, jumlah_unit FROM detail_order WHERE id_order = '$id_order'");
            while ($item = mysqli_fetch_assoc($query_detail)) {
                $id_barang = $item['id_barang'];
                $jml_potong = $item['jumlah_unit'];
                mysqli_query($conn, "UPDATE barang SET stok = stok - $jml_potong WHERE id_barang = '$id_barang'");
            }
        }

        if ($status_lama === 'Lunas' && $status_baru === 'Batal') {
            $query_detail = mysqli_query($conn, "SELECT id_barang, jumlah_unit FROM detail_order WHERE id_order = '$id_order'");
            while ($item = mysqli_fetch_assoc($query_detail)) {
                $id_barang = $item['id_barang'];
                $jml_kembali = $item['jumlah_unit'];
                mysqli_query($conn, "UPDATE barang SET stok = stok + $jml_kembali WHERE id_barang = '$id_barang'");
            }
        }
        
        // Sinkronisasi Status Order & Pembuatan Kontrak Otomatis
        if ($status_baru === 'Batal') {
            mysqli_query($conn, "UPDATE orders SET status = 'Batal' WHERE id_order = '$id_order'");
        } 
        elseif ($status_baru === 'Lunas') {
            // Ubah status order ke 'Proses Proyek'
            mysqli_query($conn, "UPDATE orders SET status = 'Proses Proyek' WHERE id_order = '$id_order'"); 

            // Ambil semua petugas lapangan yang tersedia
            $res_pegawai = mysqli_query($conn, "SELECT id_pegawai FROM pegawai WHERE peran = 'petugas_lapangan'");
            $list_pegawai = [];
            while ($peg = mysqli_fetch_assoc($res_pegawai)) {
                $list_pegawai[] = $peg['id_pegawai'];
            }
            
            // Pilih satu petugas secara acak
            $id_peg_random = (!empty($list_pegawai)) ? $list_pegawai[array_rand($list_pegawai)] : '';

            $id_kontrak_baru = 'KTK' . rand(100, 999);
            $tgl_kontrak = date('Y-m-d');
            $tgl_jatuh_tempo = date('Y-m-d H:i:s', strtotime('+30 days'));

            // Insert ke tabel kontrak saat transaksi menjadi Lunas
            mysqli_query($conn, "INSERT INTO kontrak (id_kontrak, id_order, id_customer, id_pegawai, tgl_kontrak, tgl_jatuh_tempo) 
                                 VALUES ('$id_kontrak_baru', '$id_order', '$id_customer', '$id_peg_random', '$tgl_kontrak', '$tgl_jatuh_tempo')");
        } 
        else {
            mysqli_query($conn, "UPDATE orders SET status = '$status_baru' WHERE id_order = '$id_order'");
        }
    }

    mysqli_query($conn, "UPDATE pembayaran SET status = '$status_baru' WHERE id_pembayaran = '$id_bayar'");
    header("Location: transaksi.php?status=update_sukses");
    exit;
}

// Mengambil kolom c.no_telp dari relasi tabel customer
$query_pembayaran = mysqli_query($conn, "
    SELECT p.*, o.lokasi_proyek, c.nama_lengkap, c.no_telp 
    FROM pembayaran p 
    JOIN orders o ON p.id_order = o.id_order
    JOIN customer c ON o.id_customer = c.id_customer
    ORDER BY p.tgl_bayar DESC
");

$hitung_total = mysqli_query($conn, "SELECT SUM(jumlah_bayar) as total FROM pembayaran WHERE status = 'Lunas'");
$data_total = mysqli_fetch_assoc($hitung_total);
$total_omset = $data_total['total'] ?? 0;

$hitung_pending = mysqli_query($conn, "SELECT COUNT(*) as jml FROM pembayaran WHERE status = 'Pending'");
$data_pending = mysqli_fetch_assoc($hitung_pending);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manajemen Keuangan</title>
    <link rel="stylesheet" href="../Assets/style.css">
</head>
<body>

    <?php include '../login/sidebar.php'; ?>

    <div class="main">
        <div class="topbar">
            <h1>Laporan & Manajemen Transaksi Keuangan</h1>
            <a href="../login/logout.php" class="logout">Keluar</a>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'update_sukses'): ?>
            <div class="alert-success">Status verifikasi pembayaran berhasil diperbarui dan kontrak kerja telah diterbitkan!</div>
        <?php endif; ?>

        <div class="stats">
            <div class="stat-card">
                <h3>Rp <?php echo number_format($total_omset, 0, ',', '.'); ?></h3>
                <p>Total Pendapatan Bersih (Status: Lunas)</p>
            </div>
            <div class="stat-card stat-warning">
                <h3 class="text-warning"><?php echo $data_pending['jml']; ?></h3>
                <p>Upload Struk Baru Masuk Menunggu Konfirmasi</p>
            </div>
        </div>

        <div class="card">
            <h3 class="title-section">Catatan Arus Pembayaran Proyek</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID Bayar</th>
                        <th>ID Order</th>
                        <th>Nama Pelanggan</th>
                        <th>No. Telp</th>
                        <th>Lokasi Proyek</th>
                        <th>Metode</th>
                        <th>Jumlah Tagihan</th>
                        <th>Tanggal Upload</th>
                        <th>Struk Bukti</th>
                        <th>Status</th>
                        <th>Aksi Konfirmasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($query_pembayaran) > 0): ?>
                        <?php while ($trx = mysqli_fetch_assoc($query_pembayaran)): 
                            $alamat_murni = $trx['lokasi_proyek'];
                            if (strpos($trx['lokasi_proyek'], ' (Mulai:') !== false) {
                                $parts = explode(' (Mulai:', $trx['lokasi_proyek']);
                                $alamat_murni = $parts[0]; 
                            }
                        ?>
                            <tr>
                                <td><strong class="text-slate"><?php echo $trx['id_pembayaran']; ?></strong></td>
                                <td><strong><?php echo $trx['id_order']; ?></strong></td>
                                <td><?php echo htmlspecialchars($trx['nama_lengkap']); ?></td>
                                <td><a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $trx['no_telp']); ?>" target="_blank" style="color: #10b981; font-weight: bold; text-decoration: none;">📞 <?php echo htmlspecialchars($trx['no_telp']); ?></a></td>
                                <td><?php echo htmlspecialchars($alamat_murni); ?></td>
                                <td><?php echo htmlspecialchars($trx['metode_bayar']); ?></td>
                                <td class="text-success-bold">Rp <?php echo number_format($trx['jumlah_bayar'], 0, ',', '.'); ?></td>
                                <td><?php echo ($trx['tgl_bayar'] != '0000-00-00' && !empty($trx['tgl_bayar'])) ? date('d/m/Y', strtotime($trx['tgl_bayar'])) : '-'; ?></td>
                                <td>
                                    <?php if (!empty($trx['bukti_bayar'])): ?>
                                        <a href="<?php echo $trx['bukti_bayar']; ?>" target="_blank" class="link-struk">👁 Lihat Struk</a>
                                    <?php else: ?>
                                        <span class="text-muted-italic">Belum upload</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($trx['status'] == 'Lunas'): ?>
                                        <span class="badge badge-lunas">Lunas</span>
                                    <?php elseif ($trx['status'] == 'Pending'): ?>
                                        <span class="badge badge-pending">Pending</span>
                                    <?php elseif ($trx['status'] == 'Ditolak'): ?>
                                        <span class="badge badge-batal">Ditolak</span>
                                    <?php else: ?>
                                        <span class="badge badge-batal">Batal</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="POST" action="" class="form-action">
                                        <input type="hidden" name="id_pembayaran" value="<?php echo $trx['id_pembayaran']; ?>">
                                        <select name="status_pembayaran">
                                            <option value="Pending" <?php if ($trx['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                            <option value="Lunas" <?php if ($trx['status'] == 'Lunas') echo 'selected'; ?>>Lunas</option>
                                            <option value="Ditolak" <?php if ($trx['status'] == 'Ditolak') echo 'selected'; ?>>Ditolak</option>
                                            <option value="Batal" <?php if ($trx['status'] == 'Batal') echo 'selected'; ?>>Batal</option>
                                        </select>
                                        <button type="submit" name="konfirmasi_bayar" class="btn-sm">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="11" class="td-empty-large">Belum ada riwayat transaksi keuangan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>