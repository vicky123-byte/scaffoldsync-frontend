# Laporan pemeriksaan paket migrasi ScaffoldSync

Tanggal: 19 November 2026.

## Hasil yang dapat diverifikasi di lingkungan pembuatan paket

| Bagian | Pemeriksaan | Hasil |
|---|---|---|
| Website Vue | `node --check` seluruh berkas `.js` di `web/src` | Lulus |
| Website Vue | Ekstraksi dan `node --check` blok `<script>` seluruh komponen `.vue` | Lulus |
| Website Vue | Pemeriksaan dasar keseimbangan tag template Vue | Lulus (pemeriksaan struktur, bukan compiler Vue) |
| Website Vue | `npm install` / `npm run build` | Belum terverifikasi; pemasangan dependency mengalami timeout saat mengakses registry npm |
| Mobile Flutter | Pemeriksaan keseimbangan delimiter pada seluruh berkas Dart | Lulus sebagai pemeriksaan statis dasar, bukan pengganti analyzer |
| Mobile Flutter | Verifikasi path gambar pada `Image.asset` dan `pubspec.yaml` | Lulus |
| Mobile Flutter | `flutter analyze`, `flutter test`, `flutter run` | Belum dijalankan; Flutter SDK tidak tersedia di lingkungan pembuatan paket |
| Docker | `docker build` | Belum dijalankan; Docker CLI tidak tersedia di lingkungan pembuatan paket |
| Integrasi API | Request ke backend kelompok | Belum dilakukan; kontrak endpoint dan server belum disediakan |

## Pemeriksaan wajib di laptop pengembang

Website:

```powershell
cd web
npm.cmd install
npm.cmd run build
npm.cmd run dev
```

Mobile:

```powershell
cd mobile
flutter pub get
dart format .
flutter analyze
flutter run
```

Docker website (setelah Docker Desktop aktif):

```powershell
cd web
docker build -t scaffoldsync-web .
docker run --rm -p 8080:80 scaffoldsync-web
```

## Batasan pengujian

- Pemeriksaan sintaks JavaScript tidak menjamin template Vue berhasil dikompilasi; lakukan `npm run build` setelah dependency berhasil dipasang.
- Pemeriksaan delimiter Dart bukan pengganti `dart format`, `flutter analyze`, `flutter test` maupun pengujian pada perangkat/emulator.
- Belum ada pengujian visual manual lintas ukuran layar.
- Data dan aksi demo lokal tidak boleh dianggap sebagai transaksi produksi atau tersimpan ke database.
- Validasi ketersediaan stok, pencegahan bentrok, perhitungan nominal, otorisasi dan transisi status harus dilakukan backend.
