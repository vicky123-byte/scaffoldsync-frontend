# Laporan pemeriksaan prototype

Tanggal pemeriksaan: 10 November 2026.

## Hasil yang sudah diperiksa

| Bagian | Pemeriksaan | Hasil |
|---|---|---|
| Website PHP | `php -l` pada seluruh 28 file PHP | Lulus |
| Website PHP | Login demo Admin dan pembukaan 9 rute Admin | Lulus, HTTP 200 setelah login |
| Mobile | Parsing/transpilasi sintaks pada 31 file `.ts` dan `.tsx` | Tidak ditemukan diagnostik sintaks |
| Mobile | `npm install`, `npm run typecheck`, dan bundling Expo | Belum terverifikasi di lingkungan pembuatan paket karena pemasangan dependensi npm mengalami timeout |

## Arti hasil pemeriksaan

- Pemeriksaan PHP memastikan file PHP dapat diparse, dan uji HTTP memastikan halaman Admin yang diuji dapat dibuka.
- Pemeriksaan sintaks TypeScript/TSX bukan pengganti `tsc --noEmit`. Pemeriksaan tipe penuh masih harus dijalankan setelah dependensi mobile berhasil dipasang.
- Belum ada pengujian integrasi dengan API/backend kelompok atau database MySQL.

## Perintah pemeriksaan di Windows

Dari root repository:

```powershell
Get-ChildItem web -Recurse -Filter *.php | ForEach-Object { php -l $_.FullName }
```

Untuk aplikasi mobile:

```powershell
cd mobile
npm.cmd install
npm.cmd run typecheck
npx.cmd expo start
```
