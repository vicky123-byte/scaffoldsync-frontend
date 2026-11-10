# Pemetaan Fitur ScaffoldSync Semester 3

Peta ini merangkum kebutuhan yang diberikan. Detail status dan urutan kondisi khusus harus dikonfirmasi kembali dengan BPMN final kelompok.

## Website

| Aktor | Halaman | Kemampuan pada prototype |
|---|---|---|
| Admin | Dashboard | Ringkasan stok, barang disewa, pengiriman, invoice, pesanan terbaru, jadwal, peringatan bentrok |
| Admin | Inventaris | Cari/filter barang, lihat stok total/tersedia/disewa/rusak, tambah barang demo |
| Admin | Pelanggan | Cari dan lihat pelanggan, tambah pelanggan demo |
| Admin | Pesanan | Lihat pesanan, filter status, ubah status pada data demo |
| Admin | Proyek | Lihat daftar proyek, perusahaan, dan alamat |
| Admin | Jadwal | Lihat jadwal pengiriman/pengambilan dan tambah jadwal demo |
| Admin | Dokumen | Filter penawaran, surat jalan, invoice; pratinjau/cetak tampilan |
| Admin/Pemilik | Laporan | Ringkasan omzet dan barang yang sering disewa (data contoh) |
| Pemilik | Pengguna | Lihat dan ubah status akun pada data demo |
| Pelanggan | Katalog/Keranjang | Cari barang, filter kategori, tambah item, ubah kuantitas |
| Pelanggan | Pengajuan | Input alamat, tanggal sewa, dan catatan akses truk |
| Pelanggan | Sewa/Detail | Riwayat, status, timeline, invoice, aksi persetujuan demo, upload bukti demo |
| Pelanggan | Profil/Bantuan | Edit nama demo, lokasi tersimpan sebagai tampilan, FAQ, tautan WhatsApp placeholder |

## Mobile Pelanggan

| ID | Screen/state | Status implementasi |
|---|---|---|
| M-CUS-01 | Login dan registrasi | UI dan pilihan peran demo; pendaftaran mobile masih simulasi lokal |
| M-CUS-02–07 | Beranda/katalog/detail/pencarian/favorit/notifikasi/navigasi | Beranda, katalog, detail, pencarian, favorit, notifikasi, dan navigasi dasar |
| M-CUS-08 | Keranjang | UI daftar beberapa item, ubah kuantitas, hapus item, dan subtotal per hari |
| M-CUS-09 | Pengajuan proyek/alamat kirim | Form alamat, periode, catatan; GPS masih placeholder |
| M-CUS-10 | Daftar sewa | Tab status dan empty state |
| M-CUS-11 | Detail sewa/status | Timeline, invoice, aksi upload |
| M-CUS-12 | Upload bukti transfer | UI pilihan kamera/galeri; native picker dan API belum terhubung |
| M-CUS-13 | Profil | Form profil dan lokasi tersimpan (demo) |
| M-CUS-14 | Bantuan | FAQ dan tautan WhatsApp placeholder |
| M-CUS-B01/B02 | Bottom sheet filter/sumber foto | Bottom sheet filter dan sumber foto |
| M-CUS-S01 | Empty state | Empty state sewa |
| M-CUS-E01 | Error toast | Pesan notifikasi lokal; tidak berasal dari respons server |

## Mobile Mandor

| ID | Screen/state | Status implementasi |
|---|---|---|
| M-MND-01 | Login Mandor | Pilih peran Mandor pada login demo |
| M-MND-02 | Dashboard tugas | Daftar pengiriman/pengambilan contoh |
| M-MND-03 | Detail tugas | Data pemesan, alamat, barang, catatan akses |
| M-MND-04 | Checklist inspeksi | Checklist dan kolom jumlah kondisi barang |
| M-MND-05 | Foto bukti lokasi | UI; kamera/galeri native belum terhubung |
| M-MND-06 | Tanda tangan digital | UI konfirmasi; kanvas gambar nyata belum terhubung |
| M-MND-07 | BAST digital | Ringkasan data dan status demo |
| M-MND-B01 | Laporan kendala | Bottom sheet pilihan kendala |
| M-MND-S01 | Offline mode | Banner dan toggle simulasi; belum ada antrean/sinkronisasi lokal |
| M-MND-M01 | Konfirmasi selesai | Dialog konfirmasi prototype |

## Keterangan

- **UI** berarti tampilan dan navigasi sudah disediakan.
- **Demo** berarti aksi mengubah state/session lokal, bukan database server.
- **Placeholder** berarti titik interaksi terlihat tetapi membutuhkan API, izin perangkat, atau implementasi native.
- Tidak ada endpoint backend yang diasumsikan benar sebelum kontrak API disepakati.
