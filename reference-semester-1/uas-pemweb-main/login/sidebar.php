<div class="sidebar">
    <div class="brand">ADE Scaffolding</div>
    <a href="../Admin/dashboard.php" <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'class="active"' : '' ?>>Dashboard</a>
    <a href="../Admin/barang.php" <?= basename($_SERVER['PHP_SELF']) === 'barang.php' ? 'class="active"' : '' ?>>Data
        Barang</a>
    <a href="../Admin/data_customer.php" <?= basename($_SERVER['PHP_SELF']) === 'data_customer.php' ? 'class="active"' : '' ?>>Data Pelanggan</a>
    <a href="../Admin/data_pegawai.php" <?= basename($_SERVER['PHP_SELF']) === 'data_pegawai.php' ? 'class="active"' : '' ?>>Data Pegawai</a>
    <a href="../Admin/laporan.php" <?= basename($_SERVER['PHP_SELF']) === 'laporan.php' ? 'class="active"' : '' ?>>Laporan
        Periodik</a>
    <a href="../Admin/proyek.php" <?= basename($_SERVER['PHP_SELF']) === 'proyek.php' ? 'class="active"' : '' ?>>Manajemen 
        Proyek</a>
    <a href="../Admin/transaksi.php" <?= basename($_SERVER['PHP_SELF']) === 'transaksi.php' ? 'class="active"' : '' ?>>Manajemen 
        Transaksi</a>
</div>