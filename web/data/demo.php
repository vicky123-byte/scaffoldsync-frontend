<?php
return [
 'barang'=>[
  ['kode'=>'SCF-001','nama'=>'Scaffolding 2x1 m','kategori'=>'Scaffolding','harga'=>2500,'mingguan'=>15000,'total'=>120,'tersedia'=>85,'disewa'=>30,'rusak'=>5],
  ['kode'=>'SCF-002','nama'=>'Scaffolding 1.5x1 m','kategori'=>'Scaffolding','harga'=>2200,'mingguan'=>13000,'total'=>95,'tersedia'=>60,'disewa'=>30,'rusak'=>5],
  ['kode'=>'JKB-001','nama'=>'Jackbase 1.2 m','kategori'=>'Jackbase','harga'=>1800,'mingguan'=>11000,'total'=>200,'tersedia'=>150,'disewa'=>45,'rusak'=>5],
  ['kode'=>'CLF-001','nama'=>'Clamp Fix','kategori'=>'Clamp','harga'=>800,'mingguan'=>5000,'total'=>300,'tersedia'=>200,'disewa'=>90,'rusak'=>10],
  ['kode'=>'CLS-001','nama'=>'Clamp Swivel','kategori'=>'Clamp','harga'=>900,'mingguan'=>5500,'total'=>250,'tersedia'=>180,'disewa'=>60,'rusak'=>10],
 ],
 'pelanggan'=>[
  ['nama'=>'Budi Santoso','perusahaan'=>'CV Maju Jaya','telepon'=>'0812-3456-7890','status'=>'Aktif'],
  ['nama'=>'Siti Rahayu','perusahaan'=>'PT Indah Karya','telepon'=>'0813-4567-8901','status'=>'Aktif'],
  ['nama'=>'Joko Prasetyo','perusahaan'=>'CV Sinar Abadi','telepon'=>'0814-5678-9012','status'=>'Aktif'],
  ['nama'=>'Andi Wijaya','perusahaan'=>'PT Bangun Jaya','telepon'=>'0815-6789-0123','status'=>'Aktif'],
  ['nama'=>'Dewi Lestari','perusahaan'=>'CV Karya Utama','telepon'=>'0816-7890-1234','status'=>'Aktif'],
 ],
 'pesanan'=>[
  ['kode'=>'PS-0012','pelanggan'=>'CV Maju Jaya','proyek'=>'Proyek A - Jakarta','tanggal'=>'01 Okt 2025','durasi'=>'7 hari','status'=>'Dikirim'],
  ['kode'=>'PS-0011','pelanggan'=>'PT Indah Karya','proyek'=>'Proyek B - Bandung','tanggal'=>'02 Okt 2025','durasi'=>'14 hari','status'=>'Disewa'],
  ['kode'=>'PS-0010','pelanggan'=>'CV Sinar Abadi','proyek'=>'Proyek C - Surabaya','tanggal'=>'03 Okt 2025','durasi'=>'5 hari','status'=>'Diproses'],
  ['kode'=>'PS-0009','pelanggan'=>'PT Bangun Jaya','proyek'=>'Proyek D - Medan','tanggal'=>'04 Okt 2025','durasi'=>'10 hari','status'=>'Selesai'],
  ['kode'=>'PS-0008','pelanggan'=>'CV Karya Utama','proyek'=>'Proyek E - Denpasar','tanggal'=>'05 Okt 2025','durasi'=>'3 hari','status'=>'Belum Lunas'],
 ],
 'jadwal'=>[
  ['waktu'=>'08:00','jenis'=>'Pengiriman','barang'=>'Scaffolding 2x1 m','pelanggan'=>'CV Maju Jaya','proyek'=>'Proyek A'],
  ['waktu'=>'10:30','jenis'=>'Pengambilan','barang'=>'Jackbase 1.2 m','pelanggan'=>'PT Indah Karya','proyek'=>'Proyek B'],
  ['waktu'=>'14:00','jenis'=>'Pengiriman','barang'=>'Clamp Fix','pelanggan'=>'CV Sinar Abadi','proyek'=>'Proyek C'],
  ['waktu'=>'16:30','jenis'=>'Pengiriman','barang'=>'Scaffolding 1.5x1 m','pelanggan'=>'PT Bangun Jaya','proyek'=>'Proyek D'],
 ],
 'dokumen'=>[
  ['kode'=>'INV-0012','tanggal'=>'01 Okt 2025','pelanggan'=>'CV Maju Jaya','jenis'=>'Invoice','status'=>'Belum Lunas'],
  ['kode'=>'SJ-0011','tanggal'=>'02 Okt 2025','pelanggan'=>'PT Indah Karya','jenis'=>'Surat Jalan','status'=>'Terbit'],
  ['kode'=>'PW-0010','tanggal'=>'03 Okt 2025','pelanggan'=>'CV Sinar Abadi','jenis'=>'Penawaran','status'=>'Draft'],
  ['kode'=>'INV-0009','tanggal'=>'04 Okt 2025','pelanggan'=>'PT Bangun Jaya','jenis'=>'Invoice','status'=>'Lunas'],
  ['kode'=>'SJ-0008','tanggal'=>'05 Okt 2025','pelanggan'=>'CV Karya Utama','jenis'=>'Surat Jalan','status'=>'Terbit'],
 ],
 'users'=>[
  ['nama'=>'Rina Administrator','email'=>'admin@scaffoldsync.local','role'=>'Admin','status'=>'Aktif'],
  ['nama'=>'Owner Pemilik','email'=>'owner@scaffoldsync.local','role'=>'Owner','status'=>'Aktif'],
  ['nama'=>'Andi Mandor','email'=>'mandor@scaffoldsync.local','role'=>'Mandor','status'=>'Aktif'],
 ],
 'projects'=>[
  ['nama'=>'Proyek A - Jakarta','perusahaan'=>'CV Maju Jaya','alamat'=>'Jl. Sudirman No. 123, Jakarta Pusat','status'=>'Aktif'],
  ['nama'=>'Proyek B - Bandung','perusahaan'=>'PT Indah Karya','alamat'=>'Jl. Asia Afrika, Bandung','status'=>'Disewa'],
  ['nama'=>'Proyek C - Surabaya','perusahaan'=>'CV Sinar Abadi','alamat'=>'Jl. Ahmad Yani, Surabaya','status'=>'Diproses'],
  ['nama'=>'Proyek D - Medan','perusahaan'=>'PT Bangun Jaya','alamat'=>'Jl. Gatot Subroto, Medan','status'=>'Selesai'],
  ['nama'=>'Proyek E - Denpasar','perusahaan'=>'CV Karya Utama','alamat'=>'Jl. Teuku Umar, Denpasar','status'=>'Belum Lunas'],
 ],
];
