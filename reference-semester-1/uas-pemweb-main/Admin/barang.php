<?php
require_once __DIR__ . '/../config/config.php';
cekLogin();

$pesan = '';

// Generate ID
function generateId($conn)
{
    $res = mysqli_query($conn, "SELECT id_barang FROM barang ORDER BY id_barang DESC LIMIT 1");
    $row = mysqli_fetch_assoc($res);
    if (!$row)
        return 'BRG001'; //kalo misal belom ada id barang samsek nantinya otoatis ke BRG001
    $num = (int) substr($row['id_barang'], 3) + 1; //ini mengambil id barang depan BRG
    return 'BRG' . str_pad($num, 3, '0', STR_PAD_LEFT); // INI ke yang nomor
}

// tambah barang
if (isset($_POST['aksi']) && $_POST['aksi'] === 'tambah') {
    $id = generateId($conn);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $stok = (int) $_POST['stok'];
    $harga = (float) $_POST['harga_sewa'];
    $status = 'tersedia';

    mysqli_query($conn, "INSERT INTO barang (id_barang, nama_barang, jenis, stok, harga_sewa, status)
                         VALUES ('$id', '$nama', '$jenis', $stok, $harga, '$status')");
    $pesan = ['tipe' => 'success', 'teks' => 'Barang berhasil ditambahkan.'];
}

// menghapus barang
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    mysqli_query($conn, "DELETE FROM barang WHERE id_barang = '$id'");
    $pesan = ['tipe' => 'success', 'teks' => 'Barang berhasil dihapus.'];
}

// edit dan mengambil data barang
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($conn, $_GET['edit']);// ini supaya mengubah tanda petik menjadi tanda biasa bukan perintah
    $res = mysqli_query($conn, "SELECT * FROM barang WHERE id_barang = '$id'");
    $edit_data = mysqli_fetch_assoc($res);
}

// mengupdate data barang
if (isset($_POST['aksi']) && $_POST['aksi'] === 'update') {
    $id = mysqli_real_escape_string($conn, $_POST['id_barang']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $stok = (int) $_POST['stok'];
    $harga = (float) $_POST['harga_sewa'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    mysqli_query($conn, "UPDATE barang SET nama_barang='$nama', jenis='$jenis',
                         stok=$stok, harga_sewa=$harga, status='$status'
                         WHERE id_barang='$id'");
    $pesan = ['tipe' => 'success', 'teks' => 'Barang berhasil diperbarui.'];
    $edit_data = null;
}

// keseluruhan barang
$barang = mysqli_query($conn, "SELECT * FROM barang ORDER BY id_barang DESC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Barang – ADE Scaffolding</title>
    <link rel="stylesheet" href="../Assets/style.css">
</head>

<body>
    <?php include '../login/sidebar.php'; ?>

    <div class="main">
        <div class="topbar">
            <h1>Data Barang</h1>
            <a href="../login/logout.php" class="logout">Keluar</a>
        </div>

        <?php if ($pesan): ?>
            <div class="alert alert-<?= $pesan['tipe'] ?>"><?= $pesan['teks'] ?></div>
        <?php endif; ?>

        <!-- Form Tambah / Edit -->
        <div class="card">
            <h3 style="margin-bottom:16px; color:#1e3a5f;">
                <?= $edit_data ? 'Edit Barang' : 'Tambah Barang' ?>
            </h3>
            <form method="POST">
                <input type="hidden" name="aksi" value="<?= $edit_data ? 'update' : 'tambah' ?>">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id_barang" value="<?= $edit_data['id_barang'] ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" name="nama_barang" value="<?= $edit_data['nama_barang'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Jenis</label>
                    <input type="text" name="jenis" value="<?= $edit_data['jenis'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" value="<?= $edit_data['stok'] ?? '' ?>" min="0" required>
                </div>
                <div class="form-group">
                    <label>Harga Sewa (Rp/bulan)</label>
                    <input type="number" name="harga_sewa" value="<?= $edit_data['harga_sewa'] ?? '' ?>" min="0"
                        required>
                </div>
                <?php if ($edit_data): ?>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="tersedia" <?= $edit_data['status'] === 'tersedia' ? 'selected' : '' ?>>Tersedia
                            </option>
                            <option value="tidak tersedia" <?= $edit_data['status'] === 'tidak tersedia' ? 'selected' : '' ?>>
                                Tidak Tersedia</option>
                        </select>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary">
                    <?= $edit_data ? 'Simpan Perubahan' : 'Tambah Barang' ?>
                </button>
                <?php if ($edit_data): ?>
                    <a href="barang.php" class="btn btn-warning" style="margin-left:8px;">Batal</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabel -->
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Jenis</th>
                        <th>Stok</th>
                        <th>Harga Sewa/bulan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($barang)): ?>
                        <tr>
                            <td><?= $row['id_barang'] ?></td>
                            <td><?= $row['nama_barang'] ?></td>
                            <td><?= $row['jenis'] ?></td>
                            <td><?= $row['stok'] ?></td>
                            <td>Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?></td>
                            <td><?= $row['status'] ?></td>
                            <td>
                                <a href="barang.php?edit=<?= $row['id_barang'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="barang.php?hapus=<?= $row['id_barang'] ?>" class="btn btn-danger btn-sm">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>