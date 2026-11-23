# ScaffoldSync Mobile — Flutter

Aplikasi mobile untuk Pemilik, Mandor, dan Pelanggan. Admin menggunakan website.

## Menjalankan

```powershell
flutter doctor
flutter pub get
flutter run
```

Pilih perangkat menggunakan `flutter devices`, lalu `flutter run -d <device-id>`.

## Folder

- `lib/core`: theme dan API boundary.
- `lib/features/auth`: login/demo role selector.
- `lib/features/shell`: navigasi, dashboard Pemilik, alur Mandor, dan alur Pelanggan.
- `assets`: logo dan gambar produk lokal.

## API

Endpoint belum disambungkan. Base URL demo dapat diubah saat run:

```powershell
flutter run --dart-define=API_BASE_URL=http://10.0.2.2:8000/api/v1 --dart-define=USE_MOCKS=true
```

URL dan endpoint harus mengikuti kontrak resmi backend. Pilihan role pada login saat ini hanya untuk demo; otorisasi produksi ditentukan backend.
