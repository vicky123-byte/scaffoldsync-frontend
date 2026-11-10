-- Skema awal ScaffoldSync semester 3 berdasarkan ERD yang diberikan.
-- Periksa kembali enum dan kolom dengan tim sebelum migrasi produksi.
CREATE DATABASE IF NOT EXISTS scaffoldsync CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE scaffoldsync;

CREATE TABLE users (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','pelanggan','mandor','pemilik') NOT NULL,
  no_telepon VARCHAR(20),
  status_aktif BOOLEAN NOT NULL DEFAULT TRUE,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE password_reset_token (
  id_token INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  token_hash VARCHAR(255) NOT NULL,
  expired_at DATETIME NOT NULL,
  used_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_user) REFERENCES users(id_user)
);
CREATE TABLE pelanggan (
  id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL UNIQUE,
  nama_pelanggan VARCHAR(100) NOT NULL,
  perusahaan VARCHAR(100),
  alamat TEXT,
  no_telepon VARCHAR(20),
  FOREIGN KEY (id_user) REFERENCES users(id_user)
);
CREATE TABLE proyek (
  id_proyek INT AUTO_INCREMENT PRIMARY KEY,
  id_pelanggan INT NOT NULL,
  nama_proyek VARCHAR(100) NOT NULL,
  alamat_proyek TEXT NOT NULL,
  FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan)
);
CREATE TABLE barang (
  id_barang INT AUTO_INCREMENT PRIMARY KEY,
  kode_barang VARCHAR(20) NOT NULL UNIQUE,
  nama_barang VARCHAR(100) NOT NULL,
  kategori VARCHAR(50) NOT NULL,
  harga_sewa_per_hari DECIMAL(12,2) NOT NULL DEFAULT 0,
  harga_sewa_mingguan DECIMAL(12,2) NOT NULL DEFAULT 0,
  stok_total INT NOT NULL DEFAULT 0,
  stok_tersedia INT NOT NULL DEFAULT 0,
  stok_disewa INT NOT NULL DEFAULT 0,
  stok_rusak INT NOT NULL DEFAULT 0
);
CREATE TABLE pesanan (
  id_pesanan INT AUTO_INCREMENT PRIMARY KEY,
  kode_pesanan VARCHAR(20) NOT NULL UNIQUE,
  id_pelanggan INT NOT NULL,
  id_proyek INT NOT NULL,
  id_admin INT NULL,
  tgl_mulai_sewa DATE NOT NULL,
  tgl_selesai_sewa DATE NOT NULL,
  status VARCHAR(30) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan),
  FOREIGN KEY (id_proyek) REFERENCES proyek(id_proyek),
  FOREIGN KEY (id_admin) REFERENCES users(id_user)
);
CREATE TABLE detail_pesanan (
  id_detail INT AUTO_INCREMENT PRIMARY KEY,
  id_pesanan INT NOT NULL,
  id_barang INT NOT NULL,
  jumlah INT NOT NULL,
  harga_sewa DECIMAL(12,2) NOT NULL,
  subtotal DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan),
  FOREIGN KEY (id_barang) REFERENCES barang(id_barang)
);
CREATE TABLE penawaran (
  id_penawaran INT AUTO_INCREMENT PRIMARY KEY,
  kode_penawaran VARCHAR(20) NOT NULL UNIQUE,
  id_pesanan INT NOT NULL,
  tgl_terbit DATE NOT NULL,
  total DECIMAL(12,2) NOT NULL,
  status VARCHAR(30) NOT NULL,
  tgl_persetujuan DATETIME NULL,
  FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan)
);
CREATE TABLE jadwal (
  id_jadwal INT AUTO_INCREMENT PRIMARY KEY,
  id_pesanan INT NOT NULL,
  id_mandor INT NOT NULL,
  jenis VARCHAR(30) NOT NULL,
  tgl_jadwal DATE NOT NULL,
  waktu TIME NULL,
  status VARCHAR(30) NOT NULL,
  catatan TEXT,
  FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan),
  FOREIGN KEY (id_mandor) REFERENCES users(id_user)
);
CREATE TABLE surat_jalan (
  id_surat_jalan INT AUTO_INCREMENT PRIMARY KEY,
  kode_dokumen VARCHAR(20) NOT NULL UNIQUE,
  id_pesanan INT NOT NULL,
  id_jadwal INT NOT NULL,
  tgl_terbit DATE NOT NULL,
  FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan),
  FOREIGN KEY (id_jadwal) REFERENCES jadwal(id_jadwal)
);
CREATE TABLE inspeksi (
  id_inspeksi INT AUTO_INCREMENT PRIMARY KEY,
  id_jadwal INT NOT NULL,
  tgl_inspeksi DATE NOT NULL,
  catatan TEXT,
  FOREIGN KEY (id_jadwal) REFERENCES jadwal(id_jadwal)
);
CREATE TABLE detail_inspeksi (
  id_detail_inspeksi INT AUTO_INCREMENT PRIMARY KEY,
  id_inspeksi INT NOT NULL,
  id_barang INT NOT NULL,
  jumlah_baik INT NOT NULL DEFAULT 0,
  jumlah_rusak INT NOT NULL DEFAULT 0,
  jumlah_hilang INT NOT NULL DEFAULT 0,
  FOREIGN KEY (id_inspeksi) REFERENCES inspeksi(id_inspeksi),
  FOREIGN KEY (id_barang) REFERENCES barang(id_barang)
);
CREATE TABLE invoice (
  id_invoice INT AUTO_INCREMENT PRIMARY KEY,
  kode_dokumen VARCHAR(20) NOT NULL UNIQUE,
  id_pesanan INT NOT NULL,
  tgl_terbit DATE NOT NULL,
  subtotal_sewa DECIMAL(12,2) NOT NULL,
  total_denda DECIMAL(12,2) NOT NULL DEFAULT 0,
  total_tagihan DECIMAL(12,2) NOT NULL,
  status VARCHAR(30) NOT NULL,
  jatuh_tempo DATE NOT NULL,
  FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan)
);
CREATE TABLE pembayaran (
  id_pembayaran INT AUTO_INCREMENT PRIMARY KEY,
  id_invoice INT NOT NULL,
  tgl_bayar DATE NOT NULL,
  jumlah DECIMAL(12,2) NOT NULL,
  metode VARCHAR(30) NOT NULL,
  catatan TEXT,
  FOREIGN KEY (id_invoice) REFERENCES invoice(id_invoice)
);
