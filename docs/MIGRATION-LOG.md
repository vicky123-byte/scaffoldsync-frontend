# Log Migrasi Teknologi — ScaffoldSync

## Kondisi sumber sebelum migrasi

Audit atas ZIP yang diunggah menemukan:

- Website: beberapa view PHP serta aset CSS/JavaScript/logo/produk.
- Mobile: aset produk, tetapi tidak ada source aplikasi Expo/React Native di dalam ZIP yang diaudit.
- ZIP tersebut tidak memuat `web/index.php`, router/controller/action PHP, `mobile/App.tsx`, maupun `mobile/package.json`. Karena itu source lama yang dapat diarsipkan ke folder `legacy/` bersifat parsial, bukan salinan aplikasi lama yang runnable.
- Dokumentasi yang tersedia: BPMN semester 3, ERD semester 3, Use Case semester 3, skema SQL awal, peta fitur dan catatan revisi.
- ZIP asli dari pengguna tetap tidak diubah dan menjadi sumber pemulihan file lama yang paling lengkap.

## Kondisi target

- Website: Vue 3 + Vite + JavaScript, Vue Router, Pinia dan Axios.
- Mobile: Flutter + Dart, dijalankan langsung melalui Flutter SDK.
- Docker: Dockerfile website Vue untuk packaging static web. Docker Compose untuk lingkungan gabungan harus disepakati dengan backend.
- Backend: tetap dikerjakan anggota kelompok lain; endpoint produksi belum diberikan.

## Pengamanan / rollback

1. ZIP yang dikirim pengguna tetap menjadi snapshot sumber asli dan tidak dimodifikasi.
2. Folder `legacy/php-native-web` hanya berisi view dan aset web lama yang memang ada di ZIP.
3. Folder `legacy/expo-react-native` hanya berisi aset produk yang ada di ZIP; source Expo tidak ditemukan pada arsip yang diaudit.
4. Folder `legacy/` bukan backup runnable. Untuk mengembalikan file sumber yang tidak tersedia pada folder legacy, gunakan ZIP asli pengguna.
5. Pengembangan baru berada di folder proyek terpisah, tidak menimpa sumber maupun repository Git lama.

## Keputusan visual

- Nama ScaffoldSync, logo horizontal yang diberikan pengguna, tema navy/biru/oranye dan identitas Construction Connectivity dipertahankan.
- Desain halaman dirancang ulang agar lebih konsisten dan profesional; tidak menjadikan Figma lama sebagai batas desain.
- Proses bisnis, aktor, scope dan entitas tidak boleh diganti oleh keputusan visual.

## Batas verifikasi

- Website dapat diuji dengan `npm run build` bila dependency berhasil dipasang.
- Flutter SDK tidak tersedia di lingkungan pembuatan ini. Semua pemeriksaan `flutter analyze`, `flutter test`, `flutter run`, dan build Android harus dilaksanakan di laptop pengguna.
- Backend integration belum dapat diuji tanpa kontrak endpoint dan server dari anggota backend.
