<?php
require '../config/config.php';
cekLogin();

$pesan = '';

// Generate ID otomatis format PGW001
function generateId($conn)
{
    $res = mysqli_query($conn, "SELECT id_pegawai FROM pegawai ORDER BY id_pegawai DESC LIMIT 1");
    $row = mysqli_fetch_assoc($res);
    if (!$row)
        return 'PGW001';
    $num = (int) substr($row['id_pegawai'], 3) + 1;
    return 'PGW' . str_pad($num, 3, '0', STR_PAD_LEFT);
}

// ── TAMBAH ──
if (isset($_POST['aksi']) && $_POST['aksi'] === 'tambah') {
    $id = generateId($conn);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $peran = mysqli_real_escape_string($conn, $_POST['peran']);

    mysqli_query($conn, "INSERT INTO pegawai (id_pegawai, nama_lengkap, username, password, alamat, no_telp, peran)
                         VALUES ('$id', '$nama', '$username', '$password', '$alamat', '$no_telp', '$peran')");
    $pesan = ['tipe' => 'success', 'teks' => 'Pegawai berhasil ditambahkan.'];
}

// ── HAPUS ──
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    mysqli_query($conn, "DELETE FROM pegawai WHERE id_pegawai = '$id'");
    $pesan = ['tipe' => 'success', 'teks' => 'Pegawai berhasil dihapus.'];
}

// ── EDIT (ambil data) ──
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($conn, $_GET['edit']);
    $res = mysqli_query($conn, "SELECT * FROM pegawai WHERE id_pegawai = '$id'");
    $edit_data = mysqli_fetch_assoc($res);
}

// ── UPDATE ──
if (isset($_POST['aksi']) && $_POST['aksi'] === 'update') {
    $id = mysqli_real_escape_string($conn, $_POST['id_pegawai']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $peran = mysqli_real_escape_string($conn, $_POST['peran']);

    mysqli_query($conn, "UPDATE pegawai SET nama_lengkap='$nama', username='$username',
                         alamat='$alamat', no_telp='$no_telp', peran='$peran'
                         WHERE id_pegawai='$id'");
    $pesan = ['tipe' => 'success', 'teks' => 'Data pegawai berhasil diperbarui.'];
    $edit_data = null;
}

// ── DATA ──
$pegawai = mysqli_query($conn, "SELECT * FROM pegawai ORDER BY id_pegawai DESC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Pegawai – ADE Scaffolding</title>
    <link rel="stylesheet" href="../Assets/style.css">
</head>

<body>

    <?php include '../login/sidebar.php'; ?>

    <div class="main">
        <div class="topbar">
            <h1>Data Pegawai</h1>
            <a href="../login/logout.php" class="logout">Keluar</a>
        </div>

        <?php if ($pesan): ?>
            <div class="alert alert-<?= $pesan['tipe'] ?>"><?= $pesan['teks'] ?></div>
        <?php endif; ?>

        <!-- Form Tambah / Edit -->
        <div class="card">
            <h3 style="margin-bottom:16px; color:#1e3a5f;">
                <?= $edit_data ? 'Edit Pegawai' : 'Tambah Pegawai' ?>
            </h3>
            <form method="POST">
                <input type="hidden" name="aksi" value="<?= $edit_data ? 'update' : 'tambah' ?>">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id_pegawai" value="<?= $edit_data['id_pegawai'] ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="<?= $edit_data['nama_lengkap'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?= $edit_data['username'] ?? '' ?>" required>
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
                <div class="form-group">
                    <label>Peran</label>
                    <select name="peran">
                        <option value="admin" <?= ($edit_data['peran'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin
                        </option>
                        <option value="petugas_lapangan" <?= ($edit_data['peran'] ?? '') === 'petugas_lapangan' ? 'selected' : '' ?>>Petugas Lapangan</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <?= $edit_data ? 'Simpan Perubahan' : 'Tambah Pegawai' ?>
                </button>
                <?php if ($edit_data): ?>
                    <a href="data_pegawai.php" class="btn btn-warning" style="margin-left:8px;">Batal</a>
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
                        <th>Peran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($pegawai)): ?>
                        <tr>
                            <td><?= $row['id_pegawai'] ?></td>
                            <td><?= $row['nama_lengkap'] ?></td>
                            <td><?= $row['username'] ?></td>
                            <td><?= $row['no_telp'] ?></td>
                            <td><?= $row['alamat'] ?></td>
                            <td><?= $row['peran'] ?></td>
                            <td>
                                <a href="data_pegawai.php?edit=<?= $row['id_pegawai'] ?>"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <a href="data_pegawai.php?hapus=<?= $row['id_pegawai'] ?>"
                                    class="btn btn-danger btn-sm">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>