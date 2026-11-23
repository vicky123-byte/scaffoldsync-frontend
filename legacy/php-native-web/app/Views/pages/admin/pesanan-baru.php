<section class="panel order-form-panel">

    <div class="panel-head">

        <div>
            <h2>Formulir Pesanan Penyewaan</h2>
            <p>
                Isi data pelanggan, proyek, periode, dan barang
                yang dibutuhkan.
            </p>
        </div>

        <a
            class="btn light"
            href="/index.php?page=pesanan"
        >
            ← Kembali ke Pesanan
        </a>

    </div>

    <form
        method="post"
        action="/index.php?page=pesanan-baru"
        id="adminOrderForm"
        class="order-form"
    >

        <?= csrf_field() ?>

        <input
            type="hidden"
            name="action"
            value="create_admin_order"
        >

        <!-- DATA PELANGGAN DAN PROYEK -->
        <div class="form-grid">

            <label>
                Nama Pelanggan

                <select
                    class="field"
                    name="pelanggan"
                    required
                >

                    <option value="">
                        Pilih pelanggan
                    </option>

                    <?php foreach ($store['pelanggan'] as $customer): ?>

                        <option value="<?= h($customer['nama']) ?>">

                            <?= h($customer['nama']) ?>

                            <?php if (!empty($customer['perusahaan'])): ?>
                                — <?= h($customer['perusahaan']) ?>
                            <?php endif; ?>

                        </option>

                    <?php endforeach; ?>

                </select>
            </label>

            <label>
                Nama Proyek

                <input
                    class="field"
                    type="text"
                    name="proyek"
                    placeholder="Contoh: Proyek A"
                    required
                >
            </label>

            <label>
                Lokasi/Kota Proyek

                <input
                    class="field"
                    type="text"
                    name="lokasi"
                    placeholder="Contoh: Jakarta Pusat"
                    required
                >
            </label>

            <label>
                Alamat Pengiriman

                <input
                    class="field"
                    type="text"
                    name="alamat"
                    placeholder="Alamat lengkap dan patokan lokasi"
                    required
                >
            </label>

            <label>
                Tanggal Mulai

                <input
                    class="field"
                    type="date"
                    name="mulai"
                    required
                >
            </label>

            <label>
                Tanggal Selesai

                <input
                    class="field"
                    type="date"
                    name="selesai"
                    required
                >
            </label>

        </div>

        <!-- BARANG -->
        <div class="panel-head order-items-heading">

            <div>
                <h2>Barang yang Disewa</h2>

                <p>
                    Pilih barang dan jumlah unit.
                    Stok akan diperiksa sebelum disimpan.
                </p>
            </div>

            <button
                class="btn light"
                type="button"
                id="addOrderItem"
            >
                + Tambah Barang
            </button>

        </div>

        <div id="orderItems">

            <div class="order-item-row">

                <label>
                    Barang

                    <select
                        class="field"
                        name="barang[]"
                        required
                    >

                        <option value="">
                            Pilih barang
                        </option>

                        <?php foreach ($store['barang'] as $item): ?>

                            <option
                                value="<?= h($item['kode']) ?>"
                                data-stock="<?= (int) $item['tersedia'] ?>"
                                data-price="<?= (int) $item['harga'] ?>"
                            >

                                <?= h($item['nama']) ?>

                                (tersedia <?= (int) $item['tersedia'] ?>)

                            </option>

                        <?php endforeach; ?>

                    </select>

                </label>

                <label>
                    Jumlah

                    <input
                        class="field"
                        type="number"
                        name="jumlah[]"
                        min="1"
                        value="1"
                        required
                    >
                </label>

                <button
                    class="icon-btn remove-order-item"
                    type="button"
                    title="Hapus barang"
                    aria-label="Hapus barang"
                >
                    ×
                </button>

            </div>

        </div>

        <!-- ESTIMASI -->
        <section class="order-estimate">

            <div>
                <span>Durasi penyewaan</span>
                <strong id="estimateDays">0 hari</strong>
            </div>

            <div>
                <span>Subtotal per hari</span>
                <strong id="estimateDaily">Rp 0</strong>
            </div>

            <div>
                <span>Total estimasi</span>
                <strong id="estimateTotal">Rp 0</strong>
            </div>

            <small>
                Estimasi menggunakan harga harian demo dan belum
                termasuk biaya pengiriman atau penawaran final.
            </small>

        </section>

        <!-- CATATAN -->
        <label>
            Catatan Admin (opsional)

            <textarea
                class="field"
                name="catatan"
                rows="3"
                placeholder="Catatan kebutuhan, akses truk, atau informasi tambahan"
            ></textarea>
        </label>

        <!-- ACTION -->
        <div class="form-actions">

            <a
                class="btn light"
                href="/index.php?page=pesanan"
            >
                Batal
            </a>

            <button
                class="btn primary"
                type="submit"
            >
                Simpan Pesanan
            </button>

        </div>

    </form>

</section>

<script>
(() => {
    const container = document.getElementById('orderItems');
    const addButton = document.getElementById('addOrderItem');

    if (!container || !addButton) {
        return;
    }

    const formatRupiah = (value) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(value);
    };

    function recalculate() {
        let daily = 0;

        container.querySelectorAll('.order-item-row').forEach(row => {
            const select = row.querySelector('select[name="barang[]"]');
            const qty = row.querySelector('input[name="jumlah[]"]');

            const option = select?.selectedOptions?.[0];

            const price = Number(option?.dataset.price || 0);
            const stock = Number(option?.dataset.stock || 0);

            if (qty) {
                qty.max = stock > 0 ? String(stock) : '1';
            }

            daily += price * Math.max(0, Number(qty?.value || 0));
        });

        const start = document.querySelector('[name="mulai"]').value;
        const end = document.querySelector('[name="selesai"]').value;

        let days = 0;

        if (start && end) {
            const a = new Date(start + 'T00:00:00');
            const b = new Date(end + 'T00:00:00');

            days = Math.max(
                0,
                Math.round((b - a) / 86400000) + 1
            );
        }

        document.getElementById('estimateDays').textContent =
            days + ' hari';

        document.getElementById('estimateDaily').textContent =
            formatRupiah(daily);

        document.getElementById('estimateTotal').textContent =
            formatRupiah(daily * days);
    }

    function bindRow(row) {
        row.querySelector('.remove-order-item')
            ?.addEventListener('click', () => {

                if (container.children.length > 1) {
                    row.remove();
                    recalculate();
                }
            });

        row.querySelectorAll('select, input').forEach(input => {
            input.addEventListener('input', recalculate);
            input.addEventListener('change', recalculate);
        });
    }

    container.querySelectorAll('.order-item-row').forEach(bindRow);

    document.querySelectorAll('[name="mulai"], [name="selesai"]')
        .forEach(input => {
            input.addEventListener('change', recalculate);
        });

    recalculate();

    addButton.addEventListener('click', () => {
        const first = container.querySelector('.order-item-row');

        if (!first) {
            return;
        }

        const clone = first.cloneNode(true);

        clone.querySelectorAll('select').forEach(select => {
            select.selectedIndex = 0;
        });

        clone.querySelectorAll('input').forEach(input => {
            input.value = '1';
        });

        container.appendChild(clone);

        bindRow(clone);
        recalculate();
    });
})();
</script>
