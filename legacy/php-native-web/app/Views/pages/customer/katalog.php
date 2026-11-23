
<form class="filter-row" method="get">
    <input type="hidden" name="page" value="katalog">

    <input
        class="field search-field"
        name="q"
        placeholder="Cari barang atau kode..."
        value="<?= h($_GET['q'] ?? '') ?>"
    >

    <select class="field search-field" name="kategori">
        <option value="">Semua kategori</option>

        <?php foreach (['Scaffolding', 'Jackbase', 'Clamp', 'Accessories'] as $cat): ?>
            <option
                value="<?= h($cat) ?>"
                <?= ($_GET['kategori'] ?? '') === $cat ? 'selected' : '' ?>
            >
                <?= h($cat) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button class="btn light" type="submit">
        Cari Barang
    </button>
</form>

<div class="catalog-grid">

    <?php foreach ($store['barang'] as $b): ?>

        <?php
        // Filter pencarian nama atau kode barang
        if (
            !empty($_GET['q']) &&
            stripos(
                $b['kode'] . $b['nama'],
                (string) $_GET['q']
            ) === false
        ) {
            continue;
        }

        // Filter berdasarkan kategori
        if (
            !empty($_GET['kategori']) &&
            $b['kategori'] !== $_GET['kategori']
        ) {
            continue;
        }
        ?>

        <article class="product-card">

            <!-- Foto barang -->
            <div class="product-visual">

                <?php if (!empty($b['foto'])): ?>

                    <img
                        src="<?= h($b['foto']) ?>"
                        alt="<?= h($b['nama']) ?>"
                    >

                <?php else: ?>

                    <span>▦</span>

                <?php endif; ?>

            </div>

            <!-- Informasi barang -->
            <div class="product-info">

                <span class="eyebrow">
                    <?= h($b['kategori']) ?>
                </span>

                <h3>
                    <?= h($b['nama']) ?>
                </h3>

                <p>
                    <?= h($b['kode']) ?>
                    · Stok tersedia <?= (int) $b['tersedia'] ?>
                </p>

                <strong>
                    <?= rupiah($b['harga']) ?>
                    <small>/unit/hari</small>
                </strong>

                <!-- Tombol tambah ke keranjang -->
                <a
                    class="btn primary full"
                    href="/?page=keranjang&add=<?= urlencode($b['kode']) ?>"
                >
                    ＋ Tambah ke Keranjang
                </a>

            </div>

        </article>

    <?php endforeach; ?>

</div>

<div class="note-box">
    Harga pada katalog adalah data contoh. Harga final ditetapkan melalui
    penawaran setelah Admin memeriksa stok dan periode penyewaan.
</div>
