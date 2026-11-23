# Panduan Menjalankan ScaffoldSync di Windows 11

Folder proyek berisi website Vue 3 dan aplikasi Flutter. Jalankan keduanya dari terminal terpisah di VS Code.

## 1. Website Vue 3

Persyaratan: Node.js dan npm. PowerShell pengguna Windows dapat memakai `npm.cmd`.

```powershell
cd web
npm.cmd install
npm.cmd run dev
```

Buka alamat Vite yang tampil, biasanya `http://localhost:5173`. Untuk membuat build produksi: `npm.cmd run build`.

Akun demo (password `demo123`):

- Admin: `admin@scaffoldsync.local`
- Pemilik: `owner@scaffoldsync.local`
- Pelanggan: `customer@scaffoldsync.local`

Data disimpan lokal di browser. Ini bukan autentikasi produksi.

## 2. Mobile Flutter

Persyaratan: Flutter SDK sudah terpasang. Flutter tidak perlu dijalankan melalui Docker.

```powershell
cd mobile
flutter pub get
flutter analyze
flutter run
```

Untuk mengetahui target yang tersedia: `flutter devices`. Untuk Chrome: `flutter run -d chrome`. Untuk emulator/perangkat Android, pastikan target sudah aktif di Android Studio/USB debugging.

Pada layar login, pilih peran Pemilik, Mandor, atau Pelanggan. Login masih berupa simulasi lokal.

## 3. Docker untuk website

Persyaratan: Docker Desktop aktif. Jalankan dari folder `web`:

```powershell
docker build -t scaffoldsync-web .
docker run --rm -p 8080:80 scaffoldsync-web
```

Buka `http://localhost:8080`. Flutter tetap dijalankan melalui Flutter SDK. Docker Compose gabungan dengan Laravel/database harus mengikuti file Compose dan nama service yang disepakati anggota backend.

## 4. Menghubungkan backend

- Buat `web/.env.local` berdasarkan `.env.example`; isi `VITE_API_BASE_URL` setelah backend memberi base URL resmi.
- Flutter menerima base URL melalui `--dart-define=API_BASE_URL=...`.
- Android emulator biasanya mengakses host laptop melalui `10.0.2.2`, sedangkan HP fisik memakai IP LAN laptop.
- Jangan menebak endpoint, token, field, atau enum status. Gunakan `docs/api-contract/BACKEND-HANDOFF.md` untuk menyepakati kontrak.

## 5. Jika terjadi kendala

- `npm.ps1 cannot be loaded`: gunakan `npm.cmd`.
- `npm install` gagal: periksa koneksi internet dan jalankan lagi dari folder `web`.
- Port 5173/8080 sudah dipakai: hentikan proses lama atau gunakan port alternatif.
- `flutter` tidak dikenali: periksa PATH Flutter SDK lalu jalankan `flutter doctor`.
- API tidak dapat dijangkau: periksa base URL, port yang diekspos Docker, CORS, firewall, dan alamat host perangkat.
