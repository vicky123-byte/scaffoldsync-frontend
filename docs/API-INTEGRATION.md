# Catatan Integrasi API Frontend

Dokumen ini mencatat titik integrasi frontend. Endpoint final, nama field, bentuk response, dan autentikasi **belum dianggap disepakati** sampai kontrak backend kelompok disediakan.

## Mobile adapter

File awal adaptor ada di `mobile/src/services/api.ts`. Atur `EXPO_PUBLIC_API_BASE_URL` sesuai alamat backend pengembangan. Untuk Android emulator, `localhost` biasanya mengacu ke emulator, bukan laptop; gunakan alamat host yang dapat dijangkau dari emulator/perangkat.

## Modul yang menunggu API

| Modul | Kebutuhan integrasi |
|---|---|
| Auth | Login, logout, registrasi, reset password, token/session |
| Katalog | Daftar barang, kategori, stok tersedia, harga |
| Keranjang/pengajuan | Simpan item, rentang sewa, proyek, alamat, catatan akses |
| Penawaran | Detail penawaran dan persetujuan/penolakan pelanggan |
| Pembayaran | Upload bukti transfer, status verifikasi, invoice |
| Jadwal/mandor | Daftar tugas, detail tugas, data penugasan |
| Inspeksi/BAST | Checklist, foto, tanda tangan penerima, laporan kendala |
| Owner | Ringkasan omzet, transaksi, stok, dan pengguna |

## Prinsip integrasi

1. Minta kontrak API resmi dari backend sebelum menetapkan endpoint atau field.
2. Pisahkan request API dari komponen layar melalui `services/`.
3. Tampilkan state loading, kosong, sukses, dan error pada tiap proses async.
4. Simpan token dengan mekanisme penyimpanan aman yang disepakati tim; jangan simpan password plaintext.
5. Validasi ulang hak akses dan semua perubahan data di backend. Validasi frontend bukan pengamanan server.
6. Untuk upload, sepakati batas ukuran, tipe file, dan endpoint multipart dengan backend.
