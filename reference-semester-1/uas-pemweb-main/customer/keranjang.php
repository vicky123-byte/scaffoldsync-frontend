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

// hapus item
if (isset($_GET['hapus'])) {
    $id_krj = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM keranjang WHERE id_keranjang='$id_krj' AND id_customer='$id_customer'");
    header("Location: keranjang.php");
    exit;
}

// Tambah dan Kurang Jumlah item
if (isset($_POST['id_keranjang']) && isset($_POST['aksi'])) {
    $id_krj = $_POST['id_keranjang'];
    $aksi = $_POST['aksi'];

    if ($aksi === 'tambah') {
        mysqli_query($conn, "UPDATE keranjang SET jml_barang = jml_barang + 1 WHERE id_keranjang='$id_krj' AND id_customer='$id_customer'");
    } elseif ($aksi === 'kurang') {
        // Ambil jumlah barang saat ini
        $cek = mysqli_query($conn, "SELECT jml_barang FROM keranjang WHERE id_keranjang='$id_krj'");
        $data = mysqli_fetch_assoc($cek);

        if ($data['jml_barang'] <= 1) {
            // Jika jumlahnya tinggal 1 lalu dikurangi, hapus item dari keranjang
            mysqli_query($conn, "DELETE FROM keranjang WHERE id_keranjang='$id_krj' AND id_customer='$id_customer'");
        } else {
            // Jika lebih dari 1, kurangi 1
            mysqli_query($conn, "UPDATE keranjang SET jml_barang = jml_barang - 1 WHERE id_keranjang='$id_krj' AND id_customer='$id_customer'");
        }
    }
    header("Location: keranjang.php");
    exit;
}

$query_cart = mysqli_query($conn, "SELECT k.*, b.nama_barang, b.harga_sewa FROM keranjang k JOIN barang b ON k.id_barang = b.id_barang WHERE k.id_customer='$id_customer'");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="../Assets/styleCustomer.css">
</head>

<body>
    <div class="container-cart">
        <p><a href="index.php" class="link-back">← Kembali ke Katalog</a></p><br>
        <h2>Keranjang Belanja Anda</h2>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Harga Sewa / Bulan</th>
                        <th>Jumlah Unit</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    if (mysqli_num_rows($query_cart) > 0):
                        while ($item = mysqli_fetch_assoc($query_cart)):
                            $subtotal = $item['harga_sewa'] * $item['jml_barang'];
                            $total += $subtotal;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['nama_barang']); ?></td>
                                <td>Rp <?php echo number_format($item['harga_sewa'], 0, ',', '.'); ?></td>
                                <td>
                                    <div class="qty-container">
                                        <form method="POST" action="" class="form-inline-cart">
                                            <input type="hidden" name="id_keranjang"
                                                value="<?php echo $item['id_keranjang']; ?>">
                                            <button type="submit" name="aksi" value="kurang" class="btn-qty">-</button>
                                        </form>

                                        <span class="qty-text"><?php echo $item['jml_barang']; ?></span>

                                        <form method="POST" action="" class="form-inline-cart">
                                            <input type="hidden" name="id_keranjang"
                                                value="<?php echo $item['id_keranjang']; ?>">
                                            <button type="submit" name="aksi" value="tambah" class="btn-qty">+</button>
                                        </form>
                                    </div>
                                </td>
                                <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                                <td><a href="keranjang.php?hapus=<?php echo $item['id_keranjang']; ?>"
                                        class="btn btn-danger">Hapus</a></td>
                            </tr>
                            <?php
                        endwhile;
                    else:
                        ?>
                        <tr>
                            <td colspan="5" class="empty-cart">Keranjang kosong.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if ($total > 0): ?>
                <div class="checkout-footer">
                    <h3>Total Sewa: Rp <?php echo number_format($total, 0, ',', '.'); ?> / bulan</h3>
                    <a href="checkout.php" class="btn btn-primary">Lanjut ke Checkout →</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>