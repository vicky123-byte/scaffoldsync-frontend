# Catatan Revisi ScaffoldSync — 12 November 2026

## Perbaikan website

- Memperbaiki tujuan tombol **+ Pesanan Baru**. Sebelumnya tombol menuju `?page=ajukan`, yang hanya diizinkan untuk Pelanggan sehingga Admin diarahkan kembali ke Dashboard.
- Menambahkan halaman `pesanan-baru` khusus Admin.
- Form pesanan mendukung pemilihan pelanggan, nama/lokasi proyek, alamat pengiriman, periode, beberapa jenis barang, jumlah unit, catatan, dan estimasi biaya.
- Validasi server memeriksa pelanggan, tanggal, barang, jumlah, dan stok sebelum menyimpan pesanan demo.
- Pesanan baru ditampilkan pada tabel dan memiliki rincian barang.
- Memperbaiki alur Pelanggan: isi keranjang → ajukan sewa → lihat pesanan sendiri → setujui penawaran → unggah/catat bukti pembayaran demo.
- Riwayat sewa Pelanggan difilter berdasarkan akun yang sedang masuk. Detail pesanan memakai nomor pesanan, bukan selalu data contoh yang sama.
- Menambahkan gambar produk lokal untuk scaffolding, jackbase, clamp fix, dan clamp swivel. Foto unggahan Admin tetap dapat menggantikan gambar default.

## Perbaikan mobile

- Menambahkan pilihan peran **Pemilik** di halaman login mobile bersama Pelanggan dan Mandor.
- Menambahkan halaman Pemilik: Dashboard, Stok, Pembayaran, Laporan, dan Profil.
- Menambahkan navigasi bawah sesuai pembagian peran: Pemilik (Beranda/Stok/Pembayaran/Laporan/Profil), Mandor (Tugas/Riwayat/Profil), Pelanggan (Katalog/Pesanan/Jadwal/Profil).
- Menambahkan halaman Jadwal Pelanggan dan Riwayat Tugas Mandor.
- Menampilkan gambar produk yang sama pada katalog, detail barang, keranjang, favorit, dan stok Pemilik.
- Mengganti tombol yang sebelumnya tidak melakukan apa-apa dengan aksi demo atau pesan batasan integrasi.
- Form pengajuan mobile memvalidasi data wajib dan rentang tanggal, lalu menampilkan pesanan baru pada daftar pesanan demo.

## Pemeriksaan

- Seluruh 29 file PHP: `php -l` lulus.
- JavaScript pada form Pesanan Baru: `node --check` lulus.
- Mobile TypeScript: `npm run typecheck` lulus.
- Expo Android bundle: berhasil dibuat dengan 4 aset produk.
- Smoke test HTTP: halaman Admin Pesanan Baru, pembuatan pesanan Admin, pembuatan pesanan Pelanggan, persetujuan penawaran, dan status menunggu verifikasi berhasil diuji.
- CSS dan seluruh gambar produk default merespons HTTP 200.

## Batasan yang tetap ada

- Website menggunakan session PHP; mobile memakai state/data demo. Belum ada database/API produksi.
- Upload bukti pembayaran hanya mencatat simulasi, belum menyimpan file ke server.
- Kamera, galeri, GPS/Maps, tanda tangan digital canvas, dan WhatsApp resmi masih memerlukan integrasi.
- Expo Web membutuhkan dependensi `react-dom` dan `react-native-web`; jalankan `npm.cmd install` setelah menyiapkan dependensi web.
- Ilustrasi produk yang disertakan adalah aset visual lokal, bukan foto dokumentasi barang asli.
