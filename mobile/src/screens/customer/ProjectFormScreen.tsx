import React from 'react';
import { Alert } from 'react-native';
import { AppButton, AppField, Card, PageHeading } from '../../components/UI';
export function ProjectFormScreen({ onSubmit }: { onSubmit:()=>void }) {
 return <><PageHeading title="Pengajuan Proyek & Alamat Kirim" subtitle="Masukkan alamat lengkap dan informasi akses kendaraan ke lokasi."/><Card><AppField label="Nama Proyek" placeholder="Contoh: Proyek A - Jakarta"/><AppField label="Alamat Lokasi Proyek" placeholder="Jalan, kota, kecamatan, patokan lokasi" multiline/><AppField label="Tanggal Mulai Sewa" placeholder="DD-MM-YYYY"/><AppField label="Tanggal Selesai Sewa" placeholder="DD-MM-YYYY"/><AppField label="Catatan Akses Truk" placeholder="Batas tinggi kendaraan, kondisi jalan, jam akses..." multiline/><AppButton title="Gunakan Lokasi GPS (simulasi)" variant="secondary" onPress={()=>Alert.alert('Lokasi GPS','Integrasi GPS/Maps memerlukan permission perangkat dan API lokasi.')}/><AppButton title="Kirim Pengajuan Sewa" onPress={onSubmit}/></Card></>;
}
