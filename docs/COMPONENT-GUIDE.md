# Panduan Komponen dan Konvensi Kode

## Website Vue 3

- `src/views/` untuk halaman/fitur utama; `src/components/` untuk komponen lintas halaman.
- `src/layouts/` untuk kerangka navigasi per peran.
- `src/router/` untuk navigasi SPA dan route guard berbasis role. Route guard hanya membantu UX; backend tetap wajib mengotorisasi request.
- `src/stores/` untuk state global Pinia.
- `src/services/` untuk komunikasi HTTP melalui Axios; jangan menaruh request langsung di komponen halaman.
- `src/data/` hanya untuk mock/demo dan metadata menu; jangan dianggap sebagai database produksi.
- Gunakan komponen dan token warna/spacing yang konsisten. Pastikan setiap alur memiliki loading, error, empty, dan success state saat API sudah terhubung.

## Mobile Flutter

- `lib/core/` untuk tema, konfigurasi dan adaptor API.
- `lib/features/auth/` untuk autentikasi dan registrasi.
- `lib/features/shell/` untuk navigasi dan area kerja per peran.
- Pecah layar besar menjadi widget/folder fitur ketika modul bertambah; hindari menempatkan semua logika bisnis dalam widget.
- Data contoh harus dipisahkan dari request API final.
- Jalankan `dart format .` dan `flutter analyze` setelah perubahan.

## Identitas visual

Pertahankan nama ScaffoldSync, logo resmi, warna navy/biru/oranye dan karakter konstruksi. Desain baru tidak terikat Figma lama. Komponen boleh diperbaiki secara visual, tetapi tidak boleh mengubah aktor, scope, proses BPMN, relasi ERD, atau aturan Use Case.

## Integrasi dan keamanan

- Jangan menyimpan password plaintext atau kredensial backend di source control.
- Backend merupakan sumber kebenaran untuk role/izin, ketersediaan stok, bentrok jadwal, nominal transaksi, dan transisi status.
- Jangan menampilkan data milik pelanggan lain. Filter frontend membantu tampilan, tetapi backend wajib membatasi hasil berdasarkan identitas pengguna.
