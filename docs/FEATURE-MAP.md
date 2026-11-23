# Pemetaan Fitur ScaffoldSync Semester 3

Dokumen ini menjadi checklist cakupan frontend setelah migrasi teknologi. Proses bisnis wajib mengikuti BPMN dan Use Case semester 3. Desain antarmuka dirancang ulang dengan tema ScaffoldSync, logo horizontal resmi, warna navy/biru/oranye, hierarki konsisten, aksesibilitas, dan responsivitas. Figma lama tidak dijadikan batas desain.

## Website Vue

| Aktor | Modul | Fitur yang harus tersedia |
|---|---|---|
| Admin | Dashboard | Ringkasan inventaris, barang disewa, proyek berjalan, pesanan terbaru, jadwal, tagihan dan peringatan operasional |
| Admin | Inventaris | Daftar/cari/filter barang; tambah, ubah, lihat detail; status tersedia/disewa/rusak; stok total dan riwayat |
| Admin | Pelanggan | Daftar, cari, detail, tambah dan ubah data pelanggan/perusahaan |
| Admin | Pesanan & Proyek | Lihat pengajuan, buat/proses pesanan proyek, detail item, rentang sewa, penyesuaian/pembatalan, riwayat |
| Admin | Ketersediaan | Periksa stok untuk rentang tanggal sewa; hasil final harus dari backend |
| Admin | Jadwal & Mandor | Jadwal pengiriman/pengambilan, penugasan Mandor, peringatan dan penolakan bentrok barang/mandor |
| Admin | Dokumen | Membuat/menerbitkan penawaran, surat jalan, invoice; pratinjau, unduh atau cetak PDF |
| Admin | Pembayaran | Mencatat pembayaran dan memverifikasi bukti pembayaran sesuai proses backend |
| Admin | Laporan | Omzet, proyek, item paling sering disewa, utilisasi barang, status pembayaran |
| Admin | Pengguna | Kelola akun, status aktif, peran dan reset kata sandi sesuai kewenangan |
| Pemilik | Dashboard | Ringkasan real-time omzet, proyek, pesanan, kondisi stok dan pembayaran |
| Pemilik | Inventaris | Memantau status dan jumlah barang |
| Pemilik | Pembayaran | Memantau tagihan, pembayaran masuk dan tunggakan |
| Pemilik | Laporan | Tren omzet, penggunaan barang, proyek dan performa operasional |
| Pelanggan | Akun | Registrasi, login, reset kata sandi, profil dan bantuan |
| Pelanggan | Katalog | Pencarian/filter kategori, detail barang, ketersediaan dan harga |
| Pelanggan | Keranjang & Pengajuan | Beberapa item, jumlah, proyek, alamat, tanggal mulai/akhir, kebutuhan akses/catatan |
| Pelanggan | Penawaran | Melihat rincian, menyetujui atau menolak penawaran |
| Pelanggan | Pesanan | Status dan timeline pesanan milik sendiri |
| Pelanggan | Invoice & Pembayaran | Melihat invoice, jatuh tempo, pembayaran, mengunggah bukti dan melihat status verifikasi |
| Pelanggan | Jadwal | Melihat jadwal pengiriman/pengambilan proyek sendiri |

## Mobile Flutter — Pemilik

- Dashboard bisnis dan ringkasan omzet/proyek.
- Pantau kondisi serta stok barang.
- Pantau pembayaran dan tunggakan.
- Laporan tren omzet dan barang populer.
- Profil, pengaturan akun, notifikasi, bantuan, keluar.

## Mobile Flutter — Mandor

- Daftar tugas pengiriman/pengambilan dan riwayat.
- Detail tugas, lokasi, waktu, pelanggan, proyek dan daftar barang.
- Pembaruan status tugas.
- Checklist/inspeksi kondisi barang baik, rusak dan hilang.
- Foto bukti/lokasi, tanda tangan penerima, BAST digital.
- Pelaporan kendala dan penanganan koneksi/offline sesuai kebutuhan teknis.

## Mobile Flutter — Pelanggan

- Registrasi/login/reset kata sandi.
- Beranda, katalog, pencarian, filter, detail produk dan favorit.
- Keranjang multi-item dan perubahan kuantitas.
- Form pengajuan sewa (proyek, alamat, rentang tanggal, jumlah dan catatan).
- Persetujuan/penolakan penawaran dan status pesanan.
- Invoice, pembayaran dan unggah bukti pembayaran.
- Jadwal pengiriman/pengambilan.
- Profil perusahaan, preferensi, notifikasi dan bantuan.

## Batas implementasi saat ini

Paket hasil migrasi ini menyediakan fondasi UI dan beberapa aksi demo lokal. Bukan seluruh flow produksi. Kamera/galeri, GPS, upload permanen, tanda tangan canvas, PDF final, pembayaran, offline queue dan validasi stok/bentrok jadwal harus dihubungkan ke plugin/perangkat dan backend. Setiap bagian yang belum tersambung ditandai jelas, tidak boleh mengklaim transaksi berhasil secara nyata.
