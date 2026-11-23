# ScaffoldSync — Frontend Rebuild (Vue 3 + Flutter)

ScaffoldSync adalah platform manajemen operasional dan rental scaffolding. Repository ini adalah hasil migrasi frontend dari PHP Native + Expo/React Native menjadi **Vue 3 untuk website** dan **Flutter untuk mobile**.

> **Status:** fondasi migrasi frontend dan prototipe interaktif. API produksi belum dihubungkan karena kontrak backend kelompok belum tersedia. Nilai yang tampil pada saat ini adalah data demo lokal.

## Keputusan teknologi

| Bagian | Teknologi | Tanggung jawab |
|---|---|---|
| Website | Vue 3, Vite, JavaScript, Vue Router, Pinia, Axios | Admin, Pemilik, Pelanggan |
| Mobile | Flutter SDK, Dart, Material 3 | Pemilik, Mandor, Pelanggan |
| API/backend | Laravel (dikerjakan anggota backend) | Autentikasi, otorisasi, aturan bisnis, penyimpanan, validasi |
| Container | Docker untuk website Vue dan integrasi bersama backend | Lingkungan deployment dan pengujian gabungan |

Flutter **tidak dijalankan melalui Docker**. Jalankan Flutter langsung dari Flutter SDK yang sudah dipasang pada laptop.

## Pembagian akses

| Aktor | Website | Mobile |
|---|:---:|:---:|
| Admin | Ya | Tidak |
| Pemilik | Ya | Ya |
| Mandor | Tidak | Ya |
| Pelanggan | Ya | Ya |

## Struktur repository

```text
ScaffoldSync-Rebuild-Vue-Flutter/
├── web/                         # Vue 3 + Vite
│   ├── public/assets/            # Logo dan gambar barang
│   ├── src/components/           # Komponen bersama
│   ├── src/data/                 # Data demo dan definisi navigasi
│   ├── src/layouts/              # Shell website per peran
│   ├── src/router/               # Vue Router dan pembatasan route per peran
│   ├── src/services/             # Adaptor REST API
│   ├── src/stores/               # Pinia state
│   └── src/views/                # Login, Dashboard, modul operasional
├── mobile/                      # Flutter + Dart
│   ├── assets/                   # Logo dan gambar produk
│   └── lib/                      # Theme, API boundary, auth, role shell
├── docs/                         # Acuan proyek dan handoff backend
└── legacy/                       # Arsip parsial file legacy yang tersedia di ZIP sumber
```

## Menjalankan website Vue di Windows

Persyaratan: Node.js LTS/npm.

```powershell
cd web
npm.cmd install
npm.cmd run dev
```

Buka URL Vite yang tampil di terminal, biasanya `http://localhost:5173`.

Akun demo (kata sandi semua `demo123`):

| Peran | Email |
|---|---|
| Admin | `admin@scaffoldsync.local` |
| Pemilik | `owner@scaffoldsync.local` |
| Pelanggan | `customer@scaffoldsync.local` |

Data demo disimpan di `localStorage` browser. Login demo bukan autentikasi produksi.

## Menjalankan mobile Flutter di Windows

Persyaratan: Flutter SDK sudah terpasang dan `flutter doctor` tidak menunjukkan blocker untuk target yang ingin digunakan.

```powershell
cd mobile
flutter pub get
flutter run
```

Untuk memilih target:

```powershell
flutter devices
flutter run -d chrome
```

Atau pilih emulator/perangkat Android yang tersedia. Mandor, Pemilik, dan Pelanggan tersedia di aplikasi mobile. Pemilihan role pada layar login hanya untuk demonstrasi; pada integrasi produksi, role harus diberikan dan diverifikasi oleh backend.

## Docker untuk website dan backend

Website Vue memiliki `Dockerfile` yang membangun aset statis lalu menyajikannya dengan Nginx. Jalankan dari folder `web`:

```powershell
docker build -t scaffoldsync-web .
docker run --rm -p 8080:80 scaffoldsync-web
```

Buka `http://localhost:8080`.

Backend memiliki Docker Compose sendiri sesuai kesepakatan tim. Jangan membuat Compose duplikat atau mengganti nama service/port database tanpa koordinasi dengan backend. Contoh penggabungan service terdapat di `docs/setup/DOCKER-INTEGRATION.md`.

## Konfigurasi API

Salin `web/.env.example` menjadi `web/.env.local`, lalu ubah `VITE_API_BASE_URL` setelah endpoint backend disepakati. Pada Flutter, base URL dapat diberikan menggunakan `--dart-define=API_BASE_URL=...`.

- Browser di laptop biasanya dapat memakai `http://localhost:<port-backend>`.
- Android emulator biasanya perlu alamat host emulator seperti `http://10.0.2.2:<port-backend>`.
- HP fisik harus memakai alamat IP laptop di jaringan lokal, serta laptop/firewall mengizinkan koneksi.

Kontrak endpoint, response, field, token, upload, dan status bisnis wajib disepakati bersama backend. Frontend tidak boleh menebak kontrak produksi.

## Pemeriksaan

Website:

```powershell
cd web
npm.cmd run build
```

Mobile:

```powershell
cd mobile
flutter analyze
flutter test
```

Keterbatasan environment pembuatan paket: Flutter SDK tidak tersedia di lingkungan build ini, jadi `flutter analyze`, `flutter test`, dan build Android harus dijalankan pada laptop pengembang.

## Rollback / pemulihan

- ZIP sumber yang diberikan pengguna tetap menjadi backup eksternal yang tidak diubah.
- ZIP sumber yang kamu unggah tetap menjadi snapshot rollback paling lengkap dan tidak diubah.
- Catatan penting hasil audit: ZIP sumber saat ini hanya memuat view/CSS/JS/aset web lama dan aset produk mobile. ZIP tersebut **tidak memuat** front controller/router PHP (`index.php`), controller/action PHP, maupun source aplikasi Expo (misalnya `App.tsx`, `package.json`).
- Karena itu folder `legacy/` hanya mengarsipkan file lama yang memang tersedia; folder tersebut **bukan aplikasi lama yang bisa dijalankan sendiri**. Untuk pemulihan file sumber lama secara paling lengkap, simpan ZIP asli yang kamu unggah.
- Rebuild ini dibuat di folder proyek terpisah dan tidak menimpa folder kerja lama atau repository Git lama. Sebelum memasukkan ke GitHub, buat branch dan commit checkpoint baru; jangan langsung mengganti `main`.

Lihat `docs/MIGRATION-LOG.md`, `docs/FEATURE-MAP.md`, `docs/ROLLBACK-AND-GIT.md`, dan `docs/api-contract/BACKEND-HANDOFF.md`.
