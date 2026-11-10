<?php
require_once __DIR__ . '/../config/config.php';
cekLogin();

$pesan = '';

// Generate ID otomatis format CST001
function generateId($conn)
{
    $res = mysqli_query($conn, "SELECT id_customer FROM customer ORDER BY id_customer DESC LIMIT 1");
    $row = mysqli_fetch_assoc($res);
    if (!$row)
        return 'CST001'; //jika tidak ada pelanggan samsek pakyg pertama pake cst001
    $num = (int) substr($row['id_customer'], 3) + 1; // jadi ini kalo misal cst005 ini diambil cst
    return 'CST' . str_pad($num, 3, '0', STR_PAD_LEFT); // terus disini dibalikin
}

// ── TAMBAH ──
if (isset($_POST['aksi']) && $_POST['aksi'] === 'tambah') {
    $id = generateId($conn);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']); //mysqli tuh buat karakter menjadi teks
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    mysqli_query($conn, "INSERT INTO customer (id_customer, nama_lengkap, username, alamat, password, no_telp)
                         VALUES ('$id', '$nama', '$username', '$alamat', '$password', '$no_telp')");
    $pesan = ['tipe' => 'success', 'teks' => 'Pelanggan berhasil ditambahkan.'];
}

// ── HAPUS ──
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    mysqli_query($conn, "DELETE FROM customer WHERE id_customer = '$id'");
    $pesan = ['tipe' => 'success', 'teks' => 'Pelanggan berhasil dihapus.'];
}

// ── EDIT (ambil data) ──
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($conn, $_GET['edit']);
    $res = mysqli_query($conn, "SELECT * FROM customer WHERE id_customer = '$id'");
    $edit_data = mysqli_fetch_assoc($res);
}

// ── UPDATE ──
if (isset($_POST['aksi']) && $_POST['aksi'] === 'update') {
    $id = mysqli_real_escape_string($conn, $_POST['id_customer']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);

    mysqli_query($conn, "UPDATE customer SET nama_lengkap='$nama', no_telp='$no_telp',
                         alamat='$alamat', username='$username' WHERE id_customer='$id'");
    $pesan = ['tipe' => 'success', 'teks' => 'Data pelanggan berhasil diperbarui.'];
    $edit_data = null;
}

// ── DATA ──
$pelanggan = mysqli_query($conn, "SELECT * FROM customer ORDER BY id_customer DESC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Pelanggan – ADE Scaffolding</title>
    <link rel="stylesheet" href="../Assets/style.css">
</head>

<body>

    <?php include '../login/sidebar.php'; ?>

    <div class="main">
        <div class="topbar">
            <h1>Data Pelanggan</h1>
            <a href="../login/logout.php" class="logout">Keluar</a>
        </div>

        <?php if ($pesan): ?>
            <div class="alert alert-<?= $pesan['tipe'] ?>"><?= $pesan['teks'] ?></div>
        <?php endif; ?>

        <!-- Form Tambah / Edit -->
        <div class="card">
            <h3 style="margin-bottom:16px; color:#1e3a5f;">
                <?= $edit_data ? 'Edit Pelanggan' : 'Tambah Pelanggan' ?>
            </h3>
            <form method="POST">
                <input type="hidden" name="aksi" value="<?= $edit_data ? 'update' : 'tambah' ?>">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id_customer" value="<?= $edit_data['id_customer'] ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="<?= $edit_data['nama_lengkap'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?= $edit_data['username'] ?? '' ?>">
                </div>
                <?php if (!$edit_data): ?>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="text" name="password" placeholder="Isi password awal" required>
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <label>No. Telp</label>
                    <input type="text" name="no_telp" value="<?= $edit_data['no_telp'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" name="alamat" value="<?= $edit_data['alamat'] ?? '' ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <?= $edit_data ? 'Simpan Perubahan' : 'Tambah Pelanggan' ?>
                </button>
                <?php if ($edit_data): ?>
                    <a href="data_customer.php" class="btn btn-warning" style="margin-left:8px;">Batal</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabel -->
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>No. Telp</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($pelanggan)): ?>
                        <tr>
                            <td><?= $row['id_customer'] ?></td>
                            <td><?= $row['nama_lengkap'] ?></td>
                            <td><?= $row['username'] ?></td>
                            <td><?= $row['no_telp'] ?></td>
                            <td><?= $row['alamat'] ?></td>
                            <td>
                                <a href="data_customer.php?edit=<?= $row['id_customer'] ?>"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <a href="data_customer.php?hapus=<?= $row['id_customer'] ?>" class="btn btn-danger btn-sm">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>