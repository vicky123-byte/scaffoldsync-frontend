<?php
require_once __DIR__ . '/../config/config.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['id_user'])) { header("Location: ../login/login.php"); exit; }

$id_kontrak = isset($_GET['id_kontrak']) ? $_GET['id_kontrak'] : '';

// Ambil detail data proyek dan kontrak berdasarkan ID kontrak
$q_detail = mysqli_query($conn, "
    SELECT k.*, c.nama_lengkap AS nama_customer, o.lokasi_proyek, o.id_order
    FROM kontrak k
    JOIN customer c ON k.id_customer = c.id_customer
    JOIN orders o ON k.id_order = o.id_order
    WHERE k.id_kontrak = '$id_kontrak' LIMIT 1
");
$data = mysqli_fetch_assoc($q_detail);

if (!$data) { header("Location: proyek.php"); exit; }

$id_order = $data['id_order'];

// Mengambil item/barang yang disewa berdasarkan id_order dari detail_order
$query_barang_sewa = mysqli_query($conn, "
    SELECT d.id_barang, d.jumlah_unit, b.nama_barang, b.harga_sewa
    FROM detail_order d
    JOIN barang b ON d.id_barang = b.id_barang
    WHERE d.id_order = '$id_order'
");

// Ambil semua daftar pegawai dengan peran petugas lapangan
$query_pegawai = mysqli_query($conn, "SELECT id_pegawai, nama_lengkap FROM pegawai WHERE peran = 'petugas_lapangan'");

// Inisialisasi variabel untuk memisahkan string informasi dari kolom lokasi_proyek
$alamat_murni = $data['lokasi_proyek'];
$tgl_mulai_info = '-';
$durasi_info = '-';

// Pecah string lokasi jika mendeteksi format kurung (Mulai: ...) dari checkout
if (strpos($data['lokasi_proyek'], '(Mulai:') !== false) {
    $parts = explode(' (Mulai: ', $data['lokasi_proyek']);
    $alamat_murni = $parts[0];
    
    $waktu_parts = explode(', Jangka: ', $parts[1]);
    $tgl_mulai_info = $waktu_parts[0]; // Ambil tanggal mulai sewa
    $durasi_info = rtrim($waktu_parts[1], ')'); // Ambil durasi bulan
}

// Proses update data
if (isset($_POST['simpan_perubahan'])) {
    $tgl_jatuh_tempo_baru = $_POST['tgl_jatuh_tempo'];
    $id_pegawai_baru = $_POST['id_pegawai'];

    $q_update = "UPDATE kontrak SET tgl_jatuh_tempo = '$tgl_jatuh_tempo_baru', id_pegawai = '$id_pegawai_baru' WHERE id_kontrak = '$id_kontrak'";
    if (mysqli_query($conn, $q_update)) {
        header("Location: proyek.php?status=sukses_update");
        exit;
    }
}

// penyelesaian proyek
if (isset($_POST['selesaikan_proyek'])) {
    
    // Ambil data barang dan jumlah yang disewa
    $q_items = mysqli_query($conn, "SELECT id_barang, jumlah_unit FROM detail_order WHERE id_order = '$id_order'");
    
    // Loop semua item untuk mengembalikan stok ke tabel barang
    while ($item = mysqli_fetch_assoc($q_items)) {
        $id_barang_item = $item['id_barang'];
        $qty = $item['jumlah_unit'];
        
        // Tambahkan stok yang kembali semula
        mysqli_query($conn, "UPDATE barang SET stok = stok + $qty WHERE id_barang = '$id_barang_item'");
    }

    // Hapus kontrak dan ubah status orders menjadi 'Selesai'
    if (mysqli_query($conn, "DELETE FROM kontrak WHERE id_kontrak = '$id_kontrak'")) {
        mysqli_query($conn, "UPDATE orders SET status = 'Selesai' WHERE id_order = '$id_order'");
        header("Location: proyek.php?status=sukses_selesai");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Proyek Konstruksi</title>
    <link rel="stylesheet" href="../Assets/style.css">
</head>
<body>

<?php include '../login/sidebar.php'; ?>

<div class="main">
    <div class="topbar">
        <h1>Detail Otorisasi Proyek #<?= $data['id_kontrak'] ?></h1>
        <a href="proyek.php" style="text-decoration: none; font-weight: bold; color: #2563eb;">← Kembali</a>
    </div>

    <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 24px; align-items: start;">
        
        <div class="card">
            <h3 style="margin-bottom: 20px; color: #1e3a5f; font-size: 16px; font-weight: 700; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
                Informasi & Manajemen Kontrak
            </h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label>ID Kontrak / ID Order</label>
                    <input type="text" value="<?= $data['id_kontrak'] ?> / <?= $data['id_order'] ?>" readonly style="background: #f1f5f9; color: #64748b;">
                </div>
                <div class="form-group">
                    <label>Nama Customer</label>
                    <input type="text" value="<?= htmlspecialchars($data['nama_customer']) ?>" readonly style="background: #f1f5f9; color: #64748b;">
                </div>
                <div class="form-group">
                    <label>Lokasi Proyek / Alamat Pengiriman</label>
                    <input type="text" value="<?= htmlspecialchars($alamat_murni) ?>" readonly style="background: #f1f5f9; color: #64748b;">
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label>Tanggal Mulai Sewa</label>
                        <input type="text" value="<?= htmlspecialchars($tgl_mulai_info) ?>" readonly style="background: #f1f5f9; color: #64748b;">
                    </div>
                    <div class="form-group">
                        <label>Durasi Pemakaian</label>
                        <input type="text" value="<?= htmlspecialchars($durasi_info) ?>" readonly style="background: #f1f5f9; color: #64748b;">
                    </div>
                </div>

                <div class="form-group">
                    <label>Tanggal Pembuatan Kontrak</label>
                    <input type="text" value="<?= date('d F Y', strtotime($data['tgl_kontrak'])) ?>" readonly style="background: #f1f5f9; color: #64748b;">
                </div>
                
                <div class="form-group">
                    <label>Petugas Lapangan Penanggung Jawab</label>
                    <select name="id_pegawai" required>
                        <?php
                        mysqli_data_seek($query_pegawai, 0);
                        while($p = mysqli_fetch_assoc($query_pegawai)): 
                        ?>
                            <option value="<?= $p['id_pegawai'] ?>" <?= ($p['id_pegawai'] == $data['id_pegawai']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['nama_lengkap']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Batas Tanggal Jatuh Tempo (Deadline Proyek)</label>
                    <input type="datetime-local" name="tgl_jatuh_tempo" value="<?= date('Y-m-d\TH:i', strtotime($data['tgl_jatuh_tempo'])) ?>" required>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" name="simpan_perubahan" class="btn btn-primary" style="flex: 1;">
                        Simpan Perubahan
                    </button>
                    <button type="submit" name="selesaikan_proyek" class="btn" style="background: #16a34a; color: #fff; flex: 1;">
                        Selesaikan Proyek ✓
                    </button>
                </div>
            </form>
        </div>

        <div class="card">
            <h3 style="margin-bottom: 16px; color: #1e3a5f; font-size: 16px; font-weight: 700; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
                Daftar Scaffolding / Barang yang Disewa
            </h3>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga Sewa / Unit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (mysqli_num_rows($query_barang_sewa) > 0):
                        while ($barang = mysqli_fetch_assoc($query_barang_sewa)): 
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= htmlspecialchars($barang['nama_barang']) ?></strong></td>
                            <td><?= $barang['jumlah_unit'] ?> Unit</td>
                            <td style="color: #16a34a; font-weight: 600;">Rp <?= number_format($barang['harga_sewa'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #888; font-style: italic; padding: 20px;">
                                Tidak ada rincian log barang untuk order ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
</body>
</html>