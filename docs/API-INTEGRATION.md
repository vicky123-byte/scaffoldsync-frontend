# Catatan Integrasi API Frontend

Endpoint final, nama field, bentuk response, dan autentikasi belum dianggap disepakati sampai kontrak backend kelompok disediakan.

## Website Vue

- HTTP client: `web/src/services/api.js` (Axios).
- Environment: salin `web/.env.example` menjadi `web/.env.local`, lalu isi `VITE_API_BASE_URL`.
- Token demo tidak digunakan sebagai token produksi. Sesuaikan interceptor setelah backend menentukan skema autentikasi.

## Mobile Flutter

- Titik konfigurasi: `mobile/lib/core/api_client.dart`.
- Base URL diberikan saat run/build melalui `--dart-define=API_BASE_URL=...`.
- Adapter saat ini adalah batas awal/placeholder; request produksi belum diimplementasikan karena kontrak backend belum tersedia.

## Modul yang menunggu API

| Modul | Kebutuhan integrasi |
|---|---|
| Auth | Login, logout, registrasi pelanggan, reset password, profil/sesi aktif |
| Pengguna/RBAC | Informasi pengguna dan peran; pengelolaan akun oleh Admin sesuai izin |
| Inventaris | Daftar/detail barang, kategori, status dan jumlah stok; tambah/ubah untuk Admin |
| Pelanggan | Daftar/detail/tambah/ubah pelanggan untuk Admin |
| Pesanan | Pengajuan Pelanggan; proses, penyesuaian dan pembatalan Admin; status milik Pelanggan |
| Ketersediaan | Pemeriksaan stok berdasarkan item dan rentang tanggal |
| Penawaran | Pembuatan/penerbitan Admin; detail dan persetujuan/penolakan Pelanggan |
| Jadwal | Pengiriman/pengambilan, penugasan Mandor, validasi bentrok |
| Surat jalan | Penerbitan, detail, unduh/cetak PDF |
| Invoice | Penerbitan, detail, daftar milik Pelanggan, unduh/cetak PDF |
| Pembayaran | Upload bukti, pencatatan dan verifikasi Admin, status pembayaran |
| Tugas Mandor | Daftar/detail, pembaruan status, inspeksi, bukti, tanda tangan, BAST, kendala |
| Dashboard/laporan | Ringkasan Admin/Pemilik, omzet, stok, proyek, tagihan, barang populer |

## Hal yang wajib disepakati

1. Base URL lokal, staging, produksi; port dan nama service Docker.
2. Prefix/versioning, HTTP method dan endpoint setiap modul.
3. Bentuk JSON sukses/error, pagination, filter dan sorting.
4. Mekanisme autentikasi, penyimpanan token, refresh dan logout.
5. Nama role final: Admin, Pemilik, Mandor dan Pelanggan serta matriks izin.
6. Field wajib dan enum status pesanan, penawaran, jadwal, invoice, pembayaran dan inspeksi.
7. Upload multipart, jenis/ukuran file, dan URL/ID berkas yang dikembalikan.
8. CORS, hostname Docker, port API dan akses dari browser/emulator/HP.
9. Validasi backend untuk stok, bentrok jadwal, total transaksi dan perubahan status.

## Alamat API per perangkat

- Browser laptop memakai hostname/port yang dipublikasikan backend.
- Android emulator umumnya memakai `10.0.2.2` untuk mencapai host laptop.
- HP fisik memakai IP LAN laptop atau alamat staging. `localhost` pada HP menunjuk ke HP itu sendiri.

Frontend hanya melakukan validasi awal dan menampilkan hasil. Backend wajib memvalidasi ulang izin, kepemilikan data, stok, nominal, status dan seluruh transaksi.
