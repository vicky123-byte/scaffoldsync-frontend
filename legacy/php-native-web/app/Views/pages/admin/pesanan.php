<div class="chips">
    <?php foreach (['Semua','Menunggu Penawaran','Diproses','Dikirim','Disewa','Selesai','Belum Lunas'] as $status): ?>
        <a
            class="chip <?= ($_GET['status'] ?? '') === $status ? 'active' : '' ?>"
            href="?page=pesanan<?= $status === 'Semua' ? '' : '&status=' . urlencode($status) ?>"
        >
            <?= h($status) ?>
        </a>
    <?php endforeach; ?>
</div>

<section class="panel">
    <div class="panel-head">
        <div>
            <h2>Daftar Pesanan</h2>
            <p>Perbarui status sesuai proses operasional penyewaan.</p>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Proyek</th>
                    <th>Tgl Pengajuan</th>
                    <th>Durasi</th>
                    <th>Status</th>
                    <th>Ubah Status</th>
                    <th>Rincian</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($store['pesanan'] as $index => $order): ?>
                    <?php if (!empty($_GET['status']) && $order['status'] !== $_GET['status']) continue; ?>
                    <tr>
                        <td><b><?= h($order['kode']) ?></b></td>
                        <td><?= h($order['pelanggan']) ?></td>
                        <td><?= h($order['proyek']) ?></td>
                        <td><?= h($order['tanggal']) ?></td>
                        <td><?= h($order['durasi']) ?></td>
                        <td><?= status_badge($order['status']) ?></td>
                        <td>
                            <?php if (current_role() === 'admin'): ?>
                                <form method="post" class="inline-form">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="index" value="<?= $index ?>">
                                    <select class="field compact" name="status" aria-label="Status pesanan">
                                        <option value="<?= h($order['status']) ?>"><?= h($order['status']) ?></option>
                                        <?php foreach (['Menunggu Penawaran','Diproses','Menunggu DP','Dikirim','Disewa','Selesai','Belum Lunas','Dibatalkan'] as $next): ?>
                                            <?php if ($next === $order['status']) continue; ?>
                                            <option value="<?= h($next) ?>"><?= h($next) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="icon-btn" title="Simpan status" aria-label="Simpan status">✓</button>
                                </form>
                            <?php else: ?>
                                <?= status_badge($order['status']) ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($order['items'])): ?>
                                <details class="order-detail-popover">
                                    <summary class="btn light compact">Lihat</summary>
                                    <div class="order-detail-content">
                                        <b>Barang pesanan</b>
                                        <?php foreach ($order['items'] as $item): ?>
                                            <p><?= h($item['nama']) ?> · <?= (int)$item['jumlah'] ?> unit</p>
                                        <?php endforeach; ?>
                                        <?php if (!empty($order['alamat'])): ?>
                                            <small>Alamat: <?= h($order['alamat']) ?></small>
                                        <?php endif; ?>
                                        <?php if (!empty($order['subtotal_per_hari'])): ?>
                                            <p><b>Subtotal/hari: <?= rupiah($order['subtotal_per_hari']) ?></b></p>
                                        <?php endif; ?>
                                        <?php if (!empty($order['catatan'])): ?>
                                            <small>Catatan: <?= h($order['catatan']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </details>
                            <?php else: ?>
                                <span class="muted">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
