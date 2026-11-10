# ScaffoldSync Web

Website PHP Native untuk prototype peran Admin, Pemilik, dan Pelanggan.

## Jalankan

Dari root repository:

```powershell
php -S localhost:8000 -t web
```

Atau dari folder ini:

```powershell
php -S localhost:8000 -t .
```

Buka `http://localhost:8000`. PHP 8.1+ diperlukan.

## Struktur

- `index.php`: entry point dan pemilihan halaman.
- `app/bootstrap.php`: inisialisasi session, seed demo, dan dependensi aplikasi.
- `app/Http/actions.php`: handler submit form dan aksi demo.
- `app/Support/helpers.php`: helper format, CSRF, hak akses, metadata, dan pemetaan view.
- `app/Views/layouts/`: layout guest serta layout setelah login.
- `app/Views/partials/`: sidebar dan modal yang dipakai ulang.
- `app/Views/pages/`: halaman dipisahkan berdasarkan fitur/peran.
- `data/demo.php`: data seed demo.
- `assets/`: CSS, JavaScript, dan logo.

## Akun demo

Password semua akun: `demo123`.

- `admin@scaffoldsync.local`
- `owner@scaffoldsync.local`
- `customer@scaffoldsync.local`

## Catatan

Semua perubahan data hanya disimpan pada session browser. Form upload hanya memvalidasi pemilihan file di sisi browser dan tidak menyimpan file permanen. Hubungkan handler dengan API/backend sebelum deployment.
