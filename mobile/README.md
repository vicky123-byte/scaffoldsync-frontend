# ScaffoldSync Mobile (Expo + React Native)

Aplikasi mobile prototype untuk aktor **Pelanggan** dan **Mandor**. Kode layar dipisahkan per fitur agar mudah dikembangkan dan dihubungkan ke API backend kelompok.

## Menjalankan di Windows

```powershell
cd mobile
npm.cmd install
npx.cmd expo start
```

Buka Expo Go di HP dan scan QR. Untuk menjalankan emulator Android:

```powershell
npm.cmd run android
```

## Struktur kode

- `App.tsx` — entry point.
- `src/navigation/RootNavigator.tsx` — navigasi prototype dan state aplikasi.
- `src/screens/auth/` — login Pelanggan/Mandor.
- `src/screens/customer/` — katalog, detail barang, favorit, notifikasi, keranjang multi-item, pengajuan, riwayat, pembayaran, profil, dan bantuan.
- `src/screens/mandor/` — dashboard tugas, detail, inspeksi, foto, tanda tangan, dan BAST.
- `src/components/` — komponen UI yang dipakai ulang.
- `src/data/mockData.ts` — seed produk dan data tugas.
- `src/services/` — kontrak layanan API (lihat `api.ts`).

## Batasan prototype

Login, pengajuan, pembayaran, dan navigasi masih menggunakan data lokal. Kamera, galeri, GPS/Maps, tanda tangan canvas, penyimpanan offline, dan sinkronisasi membutuhkan integrasi paket perangkat serta backend API. Jangan gunakan data akun demo untuk produksi.
