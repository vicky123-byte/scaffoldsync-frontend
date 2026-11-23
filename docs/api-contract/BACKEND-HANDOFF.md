# Handoff Integrasi Frontend ↔ Backend ScaffoldSync

Dokumen ini adalah daftar kebutuhan integrasi, bukan klaim kontrak final. Frontend menunggu endpoint, payload, role, status, autentikasi dan response JSON yang disahkan oleh tim backend.

## Modul dan operasi yang dibutuhkan

| Area | Operasi API yang dibutuhkan |
|---|---|
| Auth | Registrasi pelanggan, login, logout, reset password, profil/sesi aktif |
| Pengguna/RBAC | Informasi pengguna dan peran; endpoint admin untuk pengelolaan akun sesuai hak akses |
| Inventaris | Daftar/detail barang, kategori, stok/status, create/update untuk Admin |
| Pelanggan | Daftar/detail/create/update pelanggan untuk Admin |
| Pesanan | Daftar/detail, buat pengajuan pelanggan, proses/ubah/batalkan oleh Admin, status milik pelanggan |
| Ketersediaan | Cek stok berdasarkan barang dan rentang tanggal |
| Penawaran | Buat, terbitkan, detail, persetujuan/penolakan pelanggan |
| Jadwal | Daftar/create/update jadwal, penugasan Mandor, validasi bentrok barang dan Mandor |
| Surat jalan | Terbitkan, detail, unduh/cetak PDF |
| Invoice | Terbitkan, detail, daftar invoice per pelanggan, unduh/cetak PDF |
| Pembayaran | Catat pembayaran, upload bukti, verifikasi Admin, status pembayaran |
| Tugas Mandor | Daftar tugas, detail, pembaruan status, inspeksi, foto bukti, tanda tangan, BAST, laporan kendala |
| Dashboard/Laporan | Ringkasan Owner/Admin, omzet, stok, proyek, pembayaran dan barang populer |

## Hal yang harus disepakati bersama

1. Base URL lokal, staging dan produksi serta port/container service.
2. Prefix/versioning endpoint (misalnya `/api/v1`) dan HTTP method.
3. Format response sukses/error dan pagination/filter/sort.
4. Mekanisme auth (Bearer token/session), refresh, logout, masa berlaku dan penyimpanan token mobile.
5. Nama role persis dan matriks izin: `admin`, `pemilik`, `mandor`, `pelanggan` atau enum final backend.
6. Field wajib dan enum status pesanan, penawaran, jadwal, invoice, pembayaran dan inspeksi.
7. Bentuk upload multipart, batas tipe/ukuran file, dan response URL/file id.
8. Aturan CORS, hostname Docker, port API, dan akses dari browser/Android emulator/perangkat fisik.
9. Aturan validasi server untuk stok, bentrok jadwal, nominal invoice dan transisi status.

## Catatan URL per perangkat

- Vue di browser laptop dapat mengakses API melalui hostname/port host yang diekspos Docker (contoh `http://localhost:8000/api/v1`).
- Android emulator umumnya perlu `10.0.2.2` untuk mengakses host laptop.
- HP fisik memakai IP LAN laptop atau alamat staging yang bisa dijangkau perangkat.
- Alamat `localhost` di HP fisik menunjuk ke HP itu sendiri, bukan laptop.

## Prinsip keamanan

Frontend hanya menyembunyikan menu dan memvalidasi input awal. Backend wajib mengotorisasi setiap request dan memeriksa kepemilikan data. Jangan menyimpan password, jangan mempercayai nominal yang dikirim klien, dan jangan menganggap tombol UI sebagai bukti operasi berhasil.
