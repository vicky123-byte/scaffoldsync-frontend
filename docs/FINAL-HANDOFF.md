# ScaffoldSync Frontend — Handoff Migrasi Vue + Flutter

## Isi paket

- `web/`: website Vue 3 + Vite + JavaScript. Aktor: Admin, Pemilik dan Pelanggan.
- `mobile/`: aplikasi Flutter + Dart. Aktor: Pemilik, Mandor dan Pelanggan. Jalankan langsung memakai Flutter SDK.
- `docs/`: arsitektur, peta fitur, alur bisnis, integrasi API dan panduan setup.
- `legacy/`: arsip parsial view/aset PHP dan aset produk mobile dari ZIP sumber; bukan salinan aplikasi lama yang runnable.

## Prinsip yang dipertahankan

Nama ScaffoldSync, logo, warna/tema, aktor, scope, fitur dan alur bisnis mengikuti dokumen semester 3. Desain antarmuka boleh dirancang ulang; Figma lama tidak menjadi acuan visual. Dokumen BPMN, ERD, Use Case dan Project Charter tetap menjadi patokan.

## Menjalankan

Website Windows (PowerShell):

```powershell
cd web
npm.cmd install
npm.cmd run dev
```

Mobile Windows: buka terminal lain, kemudian:

```powershell
cd mobile
flutter pub get
flutter analyze
flutter run
```

Docker website: dari folder `web`, jalankan `docker build -t scaffoldsync-web .` lalu `docker run --rm -p 8080:80 scaffoldsync-web`. Flutter tidak dijalankan melalui Docker.

## Akun demo website

Password demo: `demo123`.

- `admin@scaffoldsync.local`
- `owner@scaffoldsync.local`
- `customer@scaffoldsync.local`

Akun tersebut hanya untuk prototipe lokal. Mobile menyediakan pilihan peran Pemilik, Mandor dan Pelanggan untuk simulasi UI.

## Batasan status paket

Paket ini adalah fondasi migrasi dan prototipe UI interaktif, bukan aplikasi produksi yang telah terintegrasi. Data web tersimpan lokal di browser; data mobile memakai state lokal. Auth, transaksi, stok, ketersediaan, bentrok jadwal, nominal, dokumen PDF, upload bukti, kamera/galeri, GPS, tanda tangan digital dan penyimpanan permanen perlu integrasi API/perangkat sesuai kontrak backend.

API belum disambungkan karena kontrak final backend belum diterima. Jangan menebak field/endpoint/status. Mulai dari `docs/api-contract/BACKEND-HANDOFF.md`.

## Backup dan rollback

ZIP sumber yang dikirim pengguna tidak diubah. Hasil audit menunjukkan ZIP hanya berisi beberapa view dan aset website lama serta aset produk mobile; tidak ada `web/index.php`/router/controller/action PHP dan tidak ada source Expo seperti `App.tsx` atau `package.json`. Karena itu folder `legacy/` hanya mengarsipkan file yang benar-benar tersedia, bukan aplikasi lama yang dapat dijalankan. Simpan ZIP sumber asli sebagai backup lengkap. Pengembangan baru berada di folder berbeda agar sumber asli tetap aman. Sebelum memindahkan hasil ke repo Git utama, buat branch dan commit checkpoint lalu review `git status`.
