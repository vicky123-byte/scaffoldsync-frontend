<?php
// koneksi Database
require_once __DIR__ . '/../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// memastikan session ID user ada dan bukan pegawai
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login/login.php");
    exit;
}

// Ambil ID Customer langsung dari session login
$id_customer = $_SESSION['id_user'];

// Tambah ke Keranjang
if (isset($_POST['tambah_keranjang'])) {
    $id_barang = $_POST['id_barang'];
    $jml_barang = 1;
    $id_keranjang = 'KRJ' . rand(100, 999); // Membuat ID Keranjang

    // Cek apakah barang yang sama sudah ada di keranjang
    $cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_customer='$id_customer' AND id_barang='$id_barang'");

    if (mysqli_num_rows($cek) > 0) {
        // tambah jumlah unit jika sudah ada di keranjang
        mysqli_query($conn, "UPDATE keranjang SET jml_barang = jml_barang + 1 WHERE id_customer='$id_customer' AND id_barang='$id_barang'");
    } else {
        // masukkan data baru ke tabel keranjang jika blm ada
        mysqli_query($conn, "INSERT INTO keranjang (id_keranjang, id_barang, id_customer, jml_barang) VALUES ('$id_keranjang', '$id_barang', '$id_customer', '$jml_barang')");
    }

    // kembali ke halaman utama dengan status sukses
    header("Location: index.php?status=sukses_keranjang");
    exit;
}

// mengambil Data Barang dari Database yang Stoknya Masih Ada
$query_barang = mysqli_query($conn, "SELECT * FROM barang WHERE stok > 0");

mysqli_query($conn, "ALTER TABLE barang ADD COLUMN IF NOT EXISTS gambar VARCHAR(255) NULL");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Customer - ADE Scaffolding</title>
    <link rel="stylesheet" href="../Assets/styleCustomer.css">
</head>

<body>

    <div class="container">
        <header>
            <h1>ADE Scaffolding</h1>
            <div class="nav-links">
                <a href="keranjang.php">Keranjang Belanja 🛒</a>
                <a href="riwayat.php" class="btn-history">Riwayat Order 📄</a>
                <a href="../login/logout.php" class="logout">Logout</a>
            </div>
        </header>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'sukses_keranjang'): ?>
            <div class="alert">✓ Barang berhasil dimasukkan ke dalam keranjang belanja Anda!</div>
        <?php endif; ?>

        <div class="welcome-text">
            <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['nama']); ?>!</h2>
            <p>Silakan pilih komponen scaffolding kualitas terbaik untuk mendukung proyek Anda.</p>
        </div>

        <div class="grid-produk">
            <?php if (mysqli_num_rows($query_barang) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($query_barang)): ?>
                    <div class="card-produk">
                        <div>
                            <?php if (!empty($row['gambar'])): ?>
                                <img src="<?php echo (strpos($row['gambar'], '../') !== false) ? $row['gambar'] : '../uploads/' . $row['gambar']; ?>"
                                    alt="Komponen Scaffolding">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/250x180?text=No+Image+Scaffolding" alt="No Image Available">
                            <?php endif; ?>

                            <h3><?php echo htmlspecialchars($row['nama_barang']); ?></h3>
                            <p class="jenis"><?php echo htmlspecialchars($row['jenis']); ?></p>
                        </div>

                        <div class="mt-auto">
                            <div class="harga">Rp <?php echo number_format($row['harga_sewa'], 0, ',', '.'); ?> <span
                                    class="harga-unit">/ unit</span></div>
                            <div class="stok">Stok Tersedia: <?php echo $row['stok']; ?> unit</div>

                            <form method="POST" action="">
                                <input type="hidden" name="id_barang" value="<?php echo $row['id_barang']; ?>">
                                <button type="submit" name="tambah_keranjang" class="btn-beli">Tambah ke Keranjang</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="empty-state">Saat ini tidak ada alat penyewaan yang tersedia.</p>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>