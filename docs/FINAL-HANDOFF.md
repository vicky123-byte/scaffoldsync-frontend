# ScaffoldSync Frontend — Paket Handoff

## Tujuan paket

Paket ini berisi frontend website PHP Native dan aplikasi mobile Expo/React Native untuk proyek ScaffoldSync semester 3. Struktur disiapkan agar frontend dapat dikembangkan dan diintegrasikan dengan backend kelompok melalui REST API.

## Peran aplikasi

- Website: Admin, Pemilik/Owner, dan Pelanggan.
- Mobile: Pelanggan dan Mandor.

## Acuan

1. BPMN semester 3 untuk urutan proses bisnis.
2. Use Case semester 3 untuk aktor dan fungsi.
3. ERD semester 3 untuk istilah dan struktur data.
4. PDF/render Figma untuk bahasa visual website.
5. Project semester 1 hanya sebagai referensi historis; bukan skema database semester 3.

## Menjalankan website di Windows

Pastikan PHP tersedia, lalu dari folder root hasil ekstrak:

```powershell
php -v
php -S localhost:8000 -t web
```

Buka `http://localhost:8000`.

Website menggunakan data demo berbasis session browser. Perubahan demo tidak disimpan ke database MySQL.

## Menjalankan aplikasi mobile di Windows

Buka terminal kedua:

```powershell
cd mobile
npm.cmd install
npm.cmd run typecheck
npx.cmd expo start
```

Buka Expo Go pada ponsel dan pindai QR code. Untuk emulator Android, gunakan `npm.cmd run android` setelah emulator siap.

## Akun demo website

Kata sandi demo: `demo123`.

- `admin@scaffoldsync.local`
- `owner@scaffoldsync.local`
- `customer@scaffoldsync.local`

Akun ini hanya untuk demonstrasi lokal, bukan akun produksi.

## Status dan batasan yang wajib diketahui

- Halaman, navigasi, komponen, dan aksi demo frontend telah disediakan untuk alur utama website dan mobile.
- Data website dan mobile masih data demo/lokal.
- Integrasi REST API dengan backend kelompok belum dilakukan karena kontrak endpoint, response JSON, autentikasi, dan skema final backend belum diberikan.
- GPS/Maps, kamera/galeri native, tanda tangan digital berbasis canvas, penyimpanan offline, dan sinkronisasi mobile masih memerlukan integrasi native/perangkat dan backend.
- Upload bukti pada mode demo tidak boleh dianggap sebagai penyimpanan bukti pembayaran produksi.
- Paket ini bukan backend dan tidak menjalankan transaksi nyata.

## Checklist sebelum integrasi backend

Backend dan frontend perlu menyepakati:

1. Base URL per lingkungan (lokal, staging, produksi).
2. Endpoint dan HTTP method untuk setiap fitur.
3. Bentuk response sukses dan error JSON yang konsisten.
4. Mekanisme autentikasi, token/session, refresh, dan logout.
5. Nama role dan izin akses Admin, Owner, Customer, Mandor.
6. Field dan status proses untuk pesanan, penawaran, pembayaran, jadwal, inspeksi, serta BAST.
7. Upload multipart: format file, batas ukuran, dan aturan penyimpanan.
8. CORS untuk website dan alamat host yang dapat dijangkau perangkat mobile.

Setelah kontrak API disepakati, adaptor di `mobile/src/services/api.ts` dan handler website di `web/app/Http/actions.php` perlu dihubungkan ke endpoint backend. Jangan mengubah nama field atau status berdasarkan asumsi.

## Pemeriksaan yang tersedia

Lihat `docs/TEST-REPORT.md` untuk pemeriksaan yang sudah dilakukan dan pemeriksaan yang masih perlu dijalankan di komputer pengembang. Lulus `php -l` tidak berarti seluruh alur bisnis sudah bebas bug; uji integrasi dan uji perangkat tetap wajib.
