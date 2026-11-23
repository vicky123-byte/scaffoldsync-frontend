# Docker — Penggunaan untuk Frontend ScaffoldSync

## Rekomendasi pembagian

- Vue: punya `web/Dockerfile` sendiri untuk build dan penyajian file statis melalui Nginx.
- Laravel + database: dikelola anggota backend di Docker Compose backend mereka.
- Integrasi penuh: satukan service frontend ke Compose tim setelah nama service, network, port, healthcheck, dan environment disepakati.
- Flutter: tetap dijalankan lewat Flutter SDK; tidak perlu di-container-kan untuk development normal.

Jangan membuat database kedua atau menggandakan service Laravel pada Compose frontend.

## Build dan jalankan website sendiri

Dari folder repository:

```powershell
cd web
docker build -t scaffoldsync-web .
docker run --rm -p 8080:80 scaffoldsync-web
```

Buka `http://localhost:8080`.

## Contoh pola integrasi Compose (untuk diskusi dengan backend)

Berikut hanya contoh struktur. Ganti `backend-api` dan network sesuai file Compose backend kelompok; jangan menyalin secara buta ke Compose utama.

```yaml
services:
  web:
    build:
      context: ./web
      args:
        VITE_API_BASE_URL: http://localhost:8000/api/v1
    ports:
      - "8080:80"
    depends_on:
      - backend-api

  # backend-api dan database harus didefinisikan/di-include oleh tim backend.
  # Gunakan satu network bersama dan jangan mengulang service database.
```

## Catatan penting untuk SPA

Nginx mengarahkan route Vue Router kembali ke `index.html` melalui `try_files`. Jika route seperti `/app/inventory` dibuka langsung di browser, refresh tetap diarahkan ke aplikasi Vue.

## Perbedaan URL API di lingkungan

Nilai `VITE_API_BASE_URL` dimasukkan saat build Vue. Untuk lingkungan produksi, gunakan alamat API publik/staging yang dapat dijangkau browser, bukan hostname internal Docker yang hanya dikenal di dalam network container.
