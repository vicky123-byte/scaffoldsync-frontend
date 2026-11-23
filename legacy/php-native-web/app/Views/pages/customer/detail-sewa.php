<?php
$selectedCode = (string)($_GET['code'] ?? ($_SESSION['last_order']['kode'] ?? ''));
$order = null;
foreach ($store['pesanan'] as $candidate) {
    if (($candidate['kode'] ?? '') === $selectedCode && ($candidate['pelanggan'] ?? '') === current_name()) {
        $order = $candidate;
        break;
    }
}
?>
<?php if (!$order): ?>
    <section class="panel empty-state">
        <div>▤</div><h3>Pesanan tidak ditemukan</h3>
        <p>Pesanan ini tidak tersedia pada akun kamu.</p>
        <a class="btn primary" href="?page=sewa">Kembali ke Pesanan Saya</a>
    </section>
<?php else: ?>
    <?php
    $days = max(1, (int)filter_var($order['durasi'], FILTER_SANITIZE_NUMBER_INT));
    $estimate = (int)($order['subtotal_per_hari'] ?? 0) * $days;
    $status = $order['status'];
    $showPayment = in_array($status, ['Menunggu DP','Belum Lunas'], true);
    ?>
    <section class="panel">
        <div class="panel-head">
            <div><h2><?= h($order['kode']) ?> · <?= h($order['proyek']) ?></h2><p><?= h($order['pelanggan']) ?> · Detail status penyewaan</p></div>
            <?= status_badge($status) ?>
        </div>
        <div class="stepper">
            <div class="step done">Pengajuan<small>Diterima</small></div>
            <div class="step <?= in_array($status,['Menunggu DP','Menunggu Verifikasi','Dikirim','Disewa','Selesai'],true)?'done':'' ?>">Penawaran<small><?= in_array($status,['Menunggu Penawaran'],true)?'Menunggu Admin':'Diproses' ?></small></div>
            <div class="step <?= in_array($status,['Menunggu Verifikasi','Dikirim','Disewa','Selesai'],true)?'done':'' ?>">Pembayaran<small><?= $status==='Menunggu Verifikasi'?'Menunggu verifikasi':'Menunggu' ?></small></div>
            <div class="step <?= in_array($status,['Dikirim','Disewa','Selesai'],true)?'done':'' ?>">Pengiriman<small><?= in_array($status,['Dikirim','Disewa','Selesai'],true)?'Diproses':'Menunggu' ?></small></div>
            <div class="step <?= $status==='Selesai'?'done':'' ?>">Selesai<small><?= $status==='Selesai'?'Selesai':'Belum' ?></small></div>
        </div>
        <div class="info-grid">
            <div><span>Tanggal Mulai</span><b><?= h($order['mulai'] ?? '-') ?></b></div>
            <div><span>Tanggal Selesai</span><b><?= h($order['selesai'] ?? '-') ?></b></div>
            <div><span>Durasi</span><b><?= h($order['durasi']) ?></b></div>
            <div><span>Estimasi Total</span><b><?= rupiah($estimate) ?></b></div>
        </div>
        <?php if (!empty($order['items'])): ?>
            <h3>Barang yang Diajukan</h3>
            <?php foreach ($order['items'] as $item): ?>
                <div class="cart-row">
                    <div class="grow"><b><?= h($item['nama']) ?></b><small><?= h($item['kode']) ?> · <?= (int)$item['jumlah'] ?> unit</small></div>
                    <b><?= rupiah((int)$item['subtotal_per_hari']) ?>/hari</b>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if (!empty($order['alamat'])): ?><p class="muted">Alamat/Lokasi: <?= h($order['alamat']) ?></p><?php endif; ?>
        <?php if (!empty($order['catatan'])): ?><p class="muted">Catatan: <?= h($order['catatan']) ?></p><?php endif; ?>
        <div class="detail-actions">
            <?php if ($status === 'Menunggu Penawaran'): ?>
                <form method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="accept_quote">
                    <input type="hidden" name="kode" value="<?= h($order['kode']) ?>">
                    <button class="btn primary">Setujui Penawaran (demo)</button>
                </form>
            <?php endif; ?>
            <button class="btn light" onclick="window.print()">Lihat / Cetak Ringkasan</button>
            <?php if ($showPayment): ?>
                <button class="btn primary" onclick="openModal('modalPayment')">Upload Bukti Bayar</button>
            <?php elseif ($status === 'Menunggu Verifikasi'): ?>
                <span class="note-box">Bukti pembayaran sudah dicatat dan menunggu verifikasi Admin.</span>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>
