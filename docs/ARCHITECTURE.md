# Arsitektur Frontend ScaffoldSync

## Aplikasi

| Bagian | Teknologi | Aktor utama | Fungsi |
|---|---|---|---|
| Website | PHP Native, HTML, CSS, JavaScript | Admin, Pemilik, Pelanggan | Operasional, master data, penyewaan, dokumen, laporan |
| Mobile | Expo, React Native, TypeScript | Pelanggan, Mandor | Pengajuan melalui HP dan pekerjaan lapangan |
| Backend | Menunggu kontrak tim | Semua aktor | Autentikasi, transaksi, penyimpanan, validasi dan API |

## Batas modul website

- `index.php`: entry point.
- `app/bootstrap.php`: session dan seed data.
- `app/Http/actions.php`: aksi form dan alur demo.
- `app/Support/helpers.php`: route, akses peran, helper output, metadata.
- `app/Views/layouts/`: layout aplikasi/guest.
- `app/Views/partials/`: sidebar dan modal umum.
- `app/Views/pages/{admin,owner,customer,auth}/`: halaman terpisah.

## Batas modul mobile

- `App.tsx`: entry point minimal.
- `src/navigation/`: pengaturan alur screen prototype.
- `src/screens/`: komponen halaman berdasarkan aktor dan fitur.
- `src/components/`: komponen UI reusable.
- `src/data/`: mock data.
- `src/services/`: adaptor API.
- `src/types/`: tipe navigasi dan data bersama.

## Alur bisnis tingkat tinggi

1. Pelanggan membuat akun dan mengajukan kebutuhan sewa.
2. Admin memeriksa stok dan jadwal, kemudian memproses penawaran.
3. Pelanggan meninjau penawaran dan proses pembayaran/invoice.
4. Admin menyiapkan jadwal, mandor, dan dokumen operasional.
5. Mandor melaksanakan pengiriman/pengambilan, inspeksi, foto bukti, dan serah terima.
6. Admin memantau dokumen dan transaksi; Pemilik memantau dashboard/laporan.

Ini ringkasan tingkat tinggi. Keputusan alur untuk kondisi stok tidak tersedia, penolakan penawaran, pembayaran gagal, dan jadwal bentrok harus mengikuti BPMN final.

## Prinsip

- Tidak meng-include source semester 1 ke aplikasi aktif.
- Tidak menaruh komponen website dan mobile dalam folder/dependensi yang sama.
- UI tidak menjadi sumber kebenaran transaksi; backend harus memvalidasi stok, hak akses, status, dan pembayaran.
- Semua data di prototype ditandai sebagai demo.
