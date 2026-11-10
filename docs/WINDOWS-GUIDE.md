# Panduan Menjalankan di Windows 11

## 1. Ekstrak dan buka project

Ekstrak ZIP, lalu buka folder `scaffoldsync-frontend` (folder yang berisi `web`, `mobile`, dan `docs`) melalui VS Code.

## 2. Website PHP Native

Di terminal PowerShell pada root project:

```powershell
php -v
php -S localhost:8000 -t web
```

Buka `http://localhost:8000`. Hentikan server dengan `Ctrl + C`.

Jika terminal sudah berada di folder `web`, jalankan:

```powershell
php -S localhost:8000 -t .
```

Gunakan akun demo pada README utama. Password semua akun adalah `demo123`.

## 3. Aplikasi Mobile Expo

Buka terminal VS Code kedua:

```powershell
cd mobile
npm.cmd install
npx.cmd expo start
```

Scan QR dari aplikasi Expo Go. Laptop dan HP sebaiknya terhubung ke Wi-Fi yang sama. Untuk emulator Android gunakan `npm.cmd run android`.

## 4. Memasukkan ke GitHub

Jika repository lokal `scaffoldsync-frontend` sudah ada, salin **isi** folder hasil ekstrak ke root repository lokal tersebut. Jangan membuat folder bersarang `scaffoldsync-frontend/scaffoldsync-frontend`.

Periksa perubahan lebih dahulu:

```powershell
cd C:\Users\Hp\scaffoldsync-frontend
git status
```

Jika tidak ada file teman yang ikut terhapus/terubah tanpa sengaja:

```powershell
git add .
git commit -m "feat: build scaffoldsync semester 3 frontend"
git push origin main
```

Jika kelompok memakai branch fitur dan Pull Request, gunakan branch tersebut, bukan langsung `main`.

## 5. Jika muncul masalah

- `404 GET /`: pastikan PHP server dijalankan dengan document root `web`, bukan root yang tidak memiliki `index.php`.
- `Address already in use`: hentikan server lama dengan `Ctrl + C` atau gunakan port lain.
- `npm.ps1 cannot be loaded`: gunakan `npm.cmd` dan `npx.cmd` di PowerShell.
- Expo tidak menemukan QR: pastikan `npm.cmd install` selesai dan cek jaringan laptop/HP.
