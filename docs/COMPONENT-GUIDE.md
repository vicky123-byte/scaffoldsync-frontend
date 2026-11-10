# Panduan Komponen dan Konvensi Kode

## Website PHP

- Satu halaman memiliki satu file view di `web/app/Views/pages/`.
- Layout global diletakkan di `Views/layouts/`; sidebar, modal, dan fragmen umum di `Views/partials/`.
- Handler `POST` berada di `app/Http/actions.php`; hindari menulis proses form di tengah HTML halaman.
- Helper format, CSRF, role, metadata, dan view map berada di `app/Support/helpers.php`.
- Selalu escape data yang ditampilkan menggunakan `h()`.
- Setiap form yang mengubah data demo harus menyertakan `csrf_field()`.
- Jangan menambahkan password, token, atau kredensial nyata ke source code.

## Mobile React Native

- Satu layar berada dalam satu file di `src/screens/{aktor}/`.
- Komponen yang dipakai ulang ditaruh di `src/components/`.
- Warna, radius, dan jarak gunakan token dari `src/theme.ts`.
- Tipe navigasi berada di `src/types/navigation.ts`.
- Data contoh berada di `src/data/`; jangan mencampur data mock dengan request API final.
- Request jaringan diletakkan di `src/services/`, bukan di komponen tampilan.

## Desain

Gunakan warna biru ScaffoldSync, navy untuk teks utama, latar abu-abu muda, kartu putih, dan oranye sebagai aksen CTA sesuai PDF Figma. Buat state loading, kosong, error, dan berhasil untuk semua proses yang kelak terhubung ke API.
