# Arsitektur Frontend ScaffoldSync — Vue + Flutter

## Platform dan role

| Aktor | Website Vue | Mobile Flutter |
|---|:---:|:---:|
| Admin | Ya | Tidak |
| Pemilik | Ya | Ya |
| Mandor | Tidak | Ya |
| Pelanggan | Ya | Ya |

## Batas modul website

- `src/router`: route SPA dan guard akses berdasarkan role.
- `src/layouts/AppShell.vue`: navigasi, topbar, identitas, notifikasi.
- `src/views/LoginView.vue`: login demo.
- `src/views/DashboardView.vue`: ringkasan per role.
- `src/views/ModuleView.vue`: modul inventaris, pelanggan, pesanan, proyek, jadwal, dokumen, laporan, pengguna, pembayaran, katalog, keranjang, pesanan pelanggan, invoice, profil.
- `src/stores`: state login dan data mock dengan Pinia.
- `src/services/api.js`: satu pintu request HTTP Axios.

## Batas modul mobile

- `lib/core`: theme, konfigurasi dan batas API.
- `lib/features/auth`: layar masuk dan pemilihan role demo (khusus prototype).
- `lib/features/shell`: navigasi dan workspace Pemilik, Mandor, Pelanggan.
- `assets`: logo horizontal dan gambar produk.

## Alur bisnis yang harus dijaga

1. Pelanggan mendaftar/login dan mengajukan permintaan sewa untuk barang, jumlah, proyek/alamat, serta rentang tanggal.
2. Admin memproses pengajuan, memeriksa ketersediaan barang terhadap rentang sewa, dan menyesuaikan atau membatalkan jika stok tidak cukup.
3. Admin membuat dan menerbitkan penawaran. Pelanggan meninjau lalu menyetujui/menolak penawaran.
4. Setelah disetujui, Admin mengatur jadwal pengiriman dan pengambilan serta menugaskan Mandor.
5. Sistem harus menolak jadwal bentrok barang atau Mandor; validasi final berada di backend.
6. Admin menerbitkan surat jalan dan invoice, serta mencatat pembayaran/verifikasi.
7. Mandor melihat tugas, memperbarui status, menginspeksi barang, mencatat kondisi baik/rusak/hilang, melampirkan bukti, tanda tangan penerima, dan BAST.
8. Pemilik memantau dashboard, omzet, laporan, pembayaran, kondisi dan stok melalui website maupun mobile.

## Batas tanggung jawab

- Vue/Flutter mengelola presentasi, navigasi, validasi input awal, loading/error/empty states dan pengiriman request.
- Backend menjadi sumber kebenaran untuk autentikasi, otorisasi, ketersediaan stok, bentrok jadwal, nilai tagihan, perubahan status dan penyimpanan permanen.
- Data contoh tidak boleh diperlakukan sebagai data produksi.
- Dokumen BPMN, ERD, Use Case dan Project Charter semester 3 yang disertakan tetap menjadi acuan proses. Perubahan visual tidak boleh mengubah scope atau aturan proses.
