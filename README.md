# ScaffoldSync — Frontend Semester 3

Frontend website dan aplikasi mobile untuk sistem manajemen operasional serta penyewaan scaffolding. Repository ini disusun agar kode website, aplikasi mobile, dokumentasi perancangan, dan arsip project semester 1 terpisah dengan jelas.

> **Status:** paket frontend lengkap untuk demonstrasi/prototype dan handoff integrasi. Website dan mobile memiliki halaman serta interaksi demo untuk alur utama. Integrasi ke backend/API semester 3, database produksi, dan fitur native perangkat belum dilakukan. Baca `docs/FINAL-HANDOFF.md` dan `docs/TEST-REPORT.md` sebelum menjalankan atau mengintegrasikan proyek.

## Struktur repository

```text
scaffoldsync-frontend/
├── web/                         # Website PHP Native
│   ├── app/
│   │   ├── Http/                # Handler aksi form
│   │   ├── Support/             # Helper, route, akses peran
│   │   └── Views/
│   │       ├── layouts/         # Layout guest dan aplikasi
│   │       ├── partials/        # Sidebar dan modal bersama
│   │       └── pages/           # Halaman per aktor/fitur
│   ├── assets/                  # CSS, JavaScript, logo
│   ├── data/                    # Seed data demo
│   └── index.php                # Entry point website
├── mobile/                      # Expo + React Native + TypeScript
│   ├── src/
│   │   ├── components/          # Komponen UI bersama
│   │   ├── data/                # Data mock
│   │   ├── navigation/          # Navigasi prototype
│   │   ├── screens/
│   │   │   ├── auth/
│   │   │   ├── customer/        # Layar Pelanggan
│   │   │   └── mandor/          # Layar Mandor
│   │   ├── services/            # Adaptor API
│   │   └── types/               # Tipe navigasi
│   ├── App.tsx
│   └── package.json
├── docs/                        # Peta fitur, arsitektur, skema SQL
├── reference-semester-1/        # Arsip project semester 1
├── .github/workflows/           # Pemeriksaan otomatis GitHub Actions
└── README.md
```

## Peran dan area aplikasi

| Aktor | Aplikasi | Area utama |
|---|---|---|
| Admin | Website | Dashboard, inventaris, pelanggan, pesanan, proyek, jadwal, dokumen, laporan, pengguna |
| Pemilik | Website | Dashboard, pemantauan inventaris/pesanan/dokumen, laporan omzet, pengguna |
| Pelanggan | Website dan Mobile | Katalog, keranjang, pengajuan sewa, riwayat/detail sewa, invoice, pembayaran, profil, bantuan |
| Mandor | Mobile | Dashboard tugas, detail tugas, checklist inspeksi, foto bukti, tanda tangan, BAST, laporan kendala, mode offline (UI) |

## Acuan proyek

Urutan prioritas saat mengembangkan fitur:

1. BPMN semester 3 untuk alur proses bisnis.
2. Use Case semester 3 untuk aktor dan tanggung jawab.
3. ERD semester 3 untuk entitas dan relasi data.
4. PDF Figma untuk bahasa visual website. Halaman mobile yang belum ada di Figma mengikuti warna, bentuk komponen, dan tipografi yang sama.
5. Source project semester 1 hanya sebagai referensi fitur dan aset, bukan sumber struktur aplikasi aktif.

Dokumen dan gambar acuan berada di `docs/references/`. Pemetaan fitur berada di `docs/FEATURE-MAP.md`.

## Menjalankan website (Windows 11)

Persyaratan: PHP 8.1 atau lebih baru.

Dari root repository:

```powershell
php -v
php -S localhost:8000 -t web
```

Buka `http://localhost:8000`.

Akun demo (password seluruh akun: `demo123`):

| Peran | Email |
|---|---|
| Admin | `admin@scaffoldsync.local` |
| Pemilik | `owner@scaffoldsync.local` |
| Pelanggan | `customer@scaffoldsync.local` |

Alternatif: masuk ke folder `web`, lalu jalankan `php -S localhost:8000 -t .`.

## Menjalankan aplikasi mobile

Persyaratan: Node.js LTS, npm, dan Expo Go.

```powershell
cd mobile
npm.cmd install
npx.cmd expo start
```

Scan QR menggunakan Expo Go di HP. Laptop dan HP sebaiknya berada pada jaringan Wi-Fi yang sama. Untuk emulator Android gunakan `npm.cmd run android`.

## Pemeriksaan sebelum commit

Website:

```powershell
Get-ChildItem web -Recurse -Filter *.php | ForEach-Object { php -l $_.FullName }
```

Mobile:

```powershell
cd mobile
npm.cmd run typecheck
```

Lihat [`docs/TEST-REPORT.md`](docs/TEST-REPORT.md) untuk catatan pemeriksaan yang sudah dijalankan dan bagian yang belum terverifikasi.

## Batasan prototype dan pekerjaan integrasi

- Login web dan perubahan data saat ini menggunakan session PHP serta seed demo; belum memakai autentikasi server kelompok.
- Mobile memakai data/state lokal. `mobile/src/services/api.ts` adalah adaptor awal yang menunggu kontrak API backend final.
- Skema SQL di `docs/schema-semester-3.sql` adalah draft implementasi berdasar ERD dan perlu dicocokkan oleh tim backend sebelum migrasi.
- Kamera, galeri, GPS/Maps, tanda tangan canvas, unggah permanen, WhatsApp resmi, dan sinkronisasi offline memerlukan integrasi perangkat/API.
- Nilai, nama, status, akun demo, alamat, dan nominal yang tampil pada prototype adalah data contoh.

## Git workflow

Periksa perubahan sebelum commit, terutama bila repository lokal sudah berisi pekerjaan tim lain:

```powershell
git status
git add .
git commit -m "feat: build scaffoldsync semester 3 frontend"
git push origin main
```

Jika bekerja pada branch fitur, ganti `main` dengan branch yang sedang digunakan dan ikuti aturan Pull Request kelompok.
