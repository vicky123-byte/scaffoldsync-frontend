<div class="chips">
    <a class="chip <?= empty($_GET['status']) ? 'active' : '' ?>" href="?page=sewa">Semua</a>
    <?php foreach (['Menunggu Penawaran','Menunggu DP','Menunggu Verifikasi','Dikirim','Disewa','Selesai'] as $status): ?>
        <a class="chip <?= ($_GET['status'] ?? '') === $status ? 'active' : '' ?>" href="?page=sewa&status=<?= urlencode($status) ?>">
            <?= h($status) ?>
        </a>
    <?php endforeach; ?>
</div>
<section class="panel">
    <div class="panel-head">
        <div><h2>Riwayat Penyewaan</h2><p>Daftar pengajuan milik akun yang sedang masuk.</p></div>
        <a href="?page=katalog">＋ Ajukan Sewa Baru</a>
    </div>
    <?php
    $orders = array_values(array_filter(
        array_reverse($store['pesanan']),
        fn($order) => ($order['pelanggan'] ?? '') === current_name()
    ));
    if (!empty($_GET['status'])) {
        $orders = array_values(array_filter($orders, fn($order) => $order['status'] === $_GET['status']));
    }
    ?>
    <?php if (!$orders): ?>
        <div class="empty-state">
            <div>▤</div><h3>Belum ada penyewaan</h3>
            <p>Pesanan yang kamu ajukan akan muncul di sini.</p>
            <a href="?page=katalog" class="btn primary">Jelajahi Katalog</a>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <a class="rental-row" href="?page=detail-sewa&amp;code=<?= urlencode($order['kode']) ?>">
                <div>
                    <b><?= h($order['kode']) ?> · <?= h($order['proyek']) ?></b>
                    <small><?= h($order['tanggal']) ?> · <?= h($order['durasi']) ?></small>
                </div>
                <?= status_badge($order['status']) ?>
                <span>›</span>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
