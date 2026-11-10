<?php
require_once __DIR__ . '/../config/config.php';

$sql = "INSERT IGNORE INTO pegawai (id_pegawai, nama_lengkap, username, password, alamat, no_telp, peran) VALUES
    ('PGW001', 'Rusmaya',       'rusmaya224', '111222', 'Cawas', '08967801234',  'admin'),
    ('PGW002', 'Ahmad Rusdi',   'rusdi221',   '111333', 'Cawas', '085678902134', 'petugas_lapangan'),
    ('PGW003', 'Doni Subroto',  'doni123',    '111444', 'Cawas', '086423147890', 'petugas_lapangan'),
    ('PGW004', 'Herman Subi',   'herman124',  '123456', 'Pedan', '081243568912', 'petugas_lapangan')
";
mysqli_query($conn, $sql);

$sql = "INSERT IGNORE INTO barang (id_barang, nama_barang, jenis, stok, harga_sewa, status) VALUES
    ('BRG001', 'Main Frame MF 170',  'Scaffolding', 60,  30000.00, 'tersedia'),
    ('BRG002', 'Cross Brace CB 220', 'Scaffolding', 75,  20000.00, 'tersedia'),
    ('BRG003', 'Join Pin', 'Clamp',       200,  6000.00, 'tersedia'),
    ('BRG004', 'Cat Walk','Scaffolding', 50,  20000.00, 'tersedia')
";
mysqli_query($conn, $sql);

echo "Data berhasil dimasukkan!";
?>