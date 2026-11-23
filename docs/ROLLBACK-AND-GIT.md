# Pengamanan dan Rollback ScaffoldSync

## Sebelum memindahkan hasil ke repository Git utama

1. Simpan ZIP proyek lama yang diunggah sebagai snapshot sumber asli.
2. Ekstrak proyek hasil migrasi ke folder baru terlebih dahulu. Jangan menimpa folder lama saat pertama kali mencoba.
3. Pada repository kerja, pastikan perubahan saat ini aman dengan `git status`.
4. Buat branch migrasi tersendiri:

```powershell
git switch -c refactor/vue-flutter
```

5. Salin/masukkan isi proyek migrasi hanya setelah memeriksa folder tujuan dan file yang akan berubah.
6. Buat commit checkpoint:

```powershell
git add .
git commit -m "chore: checkpoint frontend sebelum migrasi Vue dan Flutter"
```

## Cara kembali ke versi sebelumnya

Jika migrasi masih berupa branch terpisah, kembali ke branch lama tanpa menghapus branch migrasi:

```powershell
git switch main
```

Jika nama branch utama bukan `main`, gunakan nama branch lama yang tampil pada `git branch`.

Jangan menjalankan `git reset --hard`, menghapus branch, atau menghapus folder sumber lama sebagai langkah rollback rutin. Simpan branch migrasi dan ZIP asli sampai versi baru disetujui kelompok/dosen.

## Batas arsip legacy di paket ini

Audit ZIP sumber yang dikirim pengguna menemukan file view dan aset web lama, tetapi tidak menemukan entrypoint/router/controller/action PHP. Di folder mobile, ZIP sumber hanya berisi aset produk dan tidak memuat source Expo (`App.tsx`, `package.json`, dan sebagainya). Karena itu direktori `legacy/` pada paket migrasi hanya arsip parsial file lama yang tersedia. ZIP sumber asli tetap menjadi cadangan utama untuk file-file tersebut.
