<?php
require_once __DIR__ . '/../config/config.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login/login.php");
    exit;
}

$id_customer = $_SESSION['id_user'];

// Ambil data profil customer
$cust_query = mysqli_query($conn, "SELECT * FROM customer WHERE id_customer='$id_customer'");
$cust = mysqli_fetch_assoc($cust_query);

// Hitung akumulasi total harga dasar sewa dari keranjang per bulan
$query_cart = mysqli_query($conn, "SELECT k.*, b.harga_sewa FROM keranjang k JOIN barang b ON k.id_barang = b.id_barang WHERE k.id_customer='$id_customer'");
$total_per_bulan = 0;
while ($item = mysqli_fetch_assoc($query_cart)) {
    $total_per_bulan += ($item['harga_sewa'] * $item['jml_barang']);
}

if ($total_per_bulan == 0) {
    header("Location: index.php");
    exit;
}

// Proses submit pembuatan order baru
if (isset($_POST['proses_order'])) {
    $opsi_alamat = $_POST['opsi_alamat'];

    if ($opsi_alamat === 'profil') {
        $lokasi_proyek = mysqli_real_escape_string($conn, $cust['alamat']);
    } else {
        $lokasi_proyek = mysqli_real_escape_string($conn, $_POST['lokasi_proyek_baru']);
    }

    $tgl_mulai = $_POST['tgl_mulai'];
    $durasi_bulan = (int) $_POST['durasi_sewa'];

    if ($opsi_alamat === 'lain' && empty(trim($lokasi_proyek))) {
        header("Location: checkout.php?error=alamat_kosong");
        exit;
    }

    // Set default timezone sistem ke Waktu Indonesia Barat
    date_default_timezone_set('Asia/Jakarta');

    $id_order = 'ORD' . rand(100, 999);
    $tgl_order = date('Y-m-d H:i:s');
    $status_order = 'Pending';
    $info_proyek_lengkap = $lokasi_proyek . " (Mulai: " . $tgl_mulai . ", Jangka: " . $durasi_bulan . " Bulan)";

    // tambah data baru ke tabel orders
    $q_order = "INSERT INTO orders (id_order, id_customer, tgl_order, lokasi_proyek, status) VALUES ('$id_order', '$id_customer', '$tgl_order', '$info_proyek_lengkap', '$status_order')";

    if (mysqli_query($conn, $q_order)) {
        $total_bayar_keseluruhan = $total_per_bulan * $durasi_bulan;
        $id_pembayaran = 'BYR' . rand(100, 999);

        // Tambah data baru ke tabel pembayaran
        $q_pembayaran = "INSERT INTO pembayaran (id_pembayaran, id_order, jumlah_bayar, metode_bayar, status, tgl_bayar, bukti_bayar) 
                         VALUES ('$id_pembayaran', '$id_order', '$total_bayar_keseluruhan', 'Belum Pilih', 'Pending', '$tgl_order', NULL)";
        mysqli_query($conn, $q_pembayaran);

        // memindahkan seluruh list item keranjang ke detail_order
        mysqli_data_seek($query_cart, 0);
        while ($cart = mysqli_fetch_assoc($query_cart)) {
            $id_detail = 'DTL' . rand(1000, 9999);
            $id_barang = $cart['id_barang'];
            $jml = $cart['jml_barang'] * $durasi_bulan;

            mysqli_query($conn, "INSERT INTO detail_order (id_detail_order, id_order, id_barang, jumlah_unit) VALUES ('$id_detail', '$id_order', '$id_barang', '$jml')");
        }

        // Hapus data keranjang milik customer karena checkout selesai
        mysqli_query($conn, "DELETE FROM keranjang WHERE id_customer='$id_customer'");

        header("Location: kontrak.php?id_order=" . $id_order . "&id_pembayaran=" . $id_pembayaran . "&durasi=" . $durasi_bulan . "&tgl=" . $tgl_mulai);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Checkout Pengajuan Sewa</title>
    <link rel="stylesheet" href="../Assets/styleCustomer.css">
</head>

<body>
    <div class="container">
        <p><a href="customer_cart.php" class="back-link">← Kembali ke Keranjang</a></p><br>
        <h2>Form Verifikasi Pemesanan</h2>

        <div class="card">
            <div class="summary-box">
                <p><strong>Estimasi Dasar:</strong> Rp <?php echo number_format($total_per_bulan, 0, ',', '.'); ?> / Bulan</p>
            </div>

            <?php if (isset($_GET['error']) && $_GET['error'] === 'alamat_kosong'): ?>
                <div class="alert-error">Peringatan: Alamat proyek baru wajib diisi jika Anda memilih opsi Alamat Lain!</div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Nama Pemohon</label>
                    <input type="text" value="<?= htmlspecialchars($cust['nama_lengkap']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Nomor Telepon Akun</label>
                    <input type="text" value="<?= htmlspecialchars($cust['no_telp']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Lokasi Proyek / Alamat Pengiriman</label>
                    <div class="alamat-box-pilihan">
                        <div class="radio-option">
                            <input type="radio" name="opsi_alamat" id="alamat_profil" value="profil" checked>
                            <label untuk="alamat_profil">Gunakan Alamat Profil Saya</label>
                            <div class="alamat-preview">
                                <?= htmlspecialchars($cust['alamat']); ?>
                            </div>
                        </div>

                        <hr class="hr-divider">

                        <div class="radio-option">
                            <input type="radio" name="opsi_alamat" id="alamat_lain" value="lain">
                            <label untuk="alamat_lain">Gunakan Alamat Proyek Lain</label>
                            <p class="help-text">*Jika memilih opsi ini, isi kotak di bawah:</p>
                            <textarea name="lokasi_proyek_baru" rows="3" class="textarea-alamat" placeholder="Tuliskan alamat lengkap lokasi proyek baru di sini..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tanggal Mulai Sewa</label>
                    <input type="date" name="tgl_mulai" value="<?= date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group">
                    <label>Jangka Waktu Pemakaian (Bulan)</label>
                    <input type="number" name="durasi_sewa" value="1" min="1" required>
                    <small class="warning-text">* Kebijakan perusahaan: Durasi sewa scaffolding minimal 1 bulan.</small>
                </div>

                <button type="submit" name="proses_order" class="btn">Proses Pembuatan Dokumen Kontrak →</button>
            </form>
        </div>
    </div>
</body>

</html>