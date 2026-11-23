<form class="filter-row" method="get">
    <input type="hidden" name="page" value="barang">

    <input
        class="field search-field"
        name="q"
        placeholder="Cari kode atau nama barang..."
        value="<?= h($_GET['q'] ?? '') ?>"
    >

    <select class="field search-field" name="kategori">
        <option value="">Semua kategori</option>
        <?php foreach (['Scaffolding', 'Jackbase', 'Clamp', 'Accessories'] as $cat): ?>
            <option <?= ($_GET['kategori'] ?? '') === $cat ? 'selected' : '' ?>>
                <?= h($cat) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button class="btn light">Terapkan Filter</button>
</form>

<section class="panel">
    <div class="panel-head">
        <div>
            <h2>Daftar Barang</h2>
            <p><?= count($store['barang']) ?> item dalam inventaris</p>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Total</th>
                    <th>Tersedia</th>
                    <th>Disewa</th>
                    <th>Rusak</th>
                    <th>Harga/Hari</th>
                    <?php if (current_role() === 'admin'): ?>
                        <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($store['barang'] as $b): ?>
                    <?php
                    if (
                        !empty($_GET['q']) &&
                        stripos($b['kode'] . $b['nama'], (string)$_GET['q']) === false
                    ) {
                        continue;
                    }

                    if (
                        !empty($_GET['kategori']) &&
                        $b['kategori'] !== $_GET['kategori']
                    ) {
                        continue;
                    }
                    ?>

                    <tr>
                        <td style="width: 75px;">
                            <?php if (!empty($b['foto'])): ?>
                                <img
                                    src="<?= h($b['foto']) ?>"
                                    alt="<?= h($b['nama']) ?>"
                                    style="width:55px;height:55px;object-fit:contain;border-radius:8px;background:#eaf0f8;padding:3px;"
                                >
                            <?php else: ?>
                                <span style="width:55px;height:55px;display:flex;align-items:center;justify-content:center;background:#eaf0f8;color:#2563eb;border-radius:8px;font-weight:bold;">
                                    <?= h(strtoupper(substr($b['nama'], 0, 1))) ?>
                                </span>
                            <?php endif; ?>
                        </td>

                        <td><b><?= h($b['kode']) ?></b></td>
                        <td><?= h($b['nama']) ?></td>
                        <td><?= h($b['kategori']) ?></td>
                        <td><?= (int)$b['total'] ?></td>
                        <td><?= (int)$b['tersedia'] ?></td>
                        <td><?= (int)$b['disewa'] ?></td>
                        <td><?= (int)$b['rusak'] ?></td>
                        <td><?= rupiah($b['harga']) ?></td>

                        <?php if (current_role() === 'admin'): ?>
                            <td>
                                <details class="inline-edit">
                                    <summary class="icon-btn">✎</summary>

                                    <form
                                        method="post"
                                        enctype="multipart/form-data"
                                        class="edit-popover"
                                    >
                                        <?= csrf_field() ?>

                                        <input type="hidden" name="action" value="update_barang">
                                        <input type="hidden" name="kode" value="<?= h($b['kode']) ?>">

                                        <label>Nama Barang</label>
                                        <input
                                            class="field"
                                            name="nama"
                                            value="<?= h($b['nama']) ?>"
                                            required
                                        >

                                        <label>Kategori</label>
                                        <select class="field" name="kategori">
                                            <?php foreach (['Scaffolding', 'Jackbase', 'Clamp', 'Accessories'] as $category): ?>
                                                <option <?= $b['kategori'] === $category ? 'selected' : '' ?>>
                                                    <?= h($category) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>

                                        <label>Stok Total</label>
                                        <input
                                            class="field"
                                            name="total"
                                            type="number"
                                            min="<?= (int)$b['disewa'] + (int)$b['rusak'] ?>"
                                            value="<?= (int)$b['total'] ?>"
                                        >

                                        <label>Harga / Hari</label>
                                        <input
                                            class="field"
                                            name="harga"
                                            type="number"
                                            min="0"
                                            value="<?= (int)$b['harga'] ?>"
                                        >

                                        <label>Harga / Minggu</label>
                                        <input
                                            class="field"
                                            name="mingguan"
                                            type="number"
                                            min="0"
                                            value="<?= (int)$b['mingguan'] ?>"
                                        >

                                        <label>Ganti Foto Barang</label>
                                        <input
                                            class="field"
                                            type="file"
                                            name="foto"
                                            accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                                        >
                                        <small class="muted">
                                            Kosongkan jika tidak ingin mengganti foto.
                                        </small>

                                        <button class="btn primary full">
                                            Simpan Perubahan
                                        </button>
                                    </form>
                                </details>

                                <form
                                    method="post"
                                    class="inline-delete"
                                    onsubmit="return confirm('Hapus barang ini dari data demo?')"
                                >
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="delete_barang">
                                    <input type="hidden" name="kode" value="<?= h($b['kode']) ?>">
                                    <button class="icon-btn danger" title="Hapus barang">×</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
