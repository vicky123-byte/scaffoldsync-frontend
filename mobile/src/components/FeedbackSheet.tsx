import React from 'react';
import { Modal, Pressable, StyleSheet, Text, View } from 'react-native';
import { AppButton, PageHeading } from './UI';
import { SheetName } from '../types/navigation';
import { theme } from '../theme';

const issues=['Pelanggan Tidak Ada di Lokasi','Akses Truk Terhalang','Barang Rusak di Jalan','Jumlah Barang Tidak Sesuai'];
export function FeedbackSheet({ type, close, chooseFilter, notify }: { type: SheetName; close: () => void; chooseFilter: (value: string) => void; notify: (message: string) => void }) {
  const visible=Boolean(type);
  const heading=type==='filter'?'Filter Katalog':type==='photoSource'?'Pilih Sumber Foto':type==='issue'?'Laporkan Kendala':type==='finish'?'Selesaikan Tugas?':'Notifikasi';
  return <Modal visible={visible} transparent animationType="slide" onRequestClose={close}><Pressable style={styles.scrim} onPress={close}><Pressable style={styles.sheet} onPress={e=>e.stopPropagation()}><View style={styles.handle}/><PageHeading title={heading} subtitle="Pilihan ini merupakan interaksi prototype lokal."/>
    {type==='filter' ? ['Semua','Scaffolding','Jackbase','Clamp'].map(v=><Pressable key={v} style={styles.option} onPress={()=>{chooseFilter(v);close();}}><Text style={styles.optionText}>{v}</Text><Text style={styles.radio}>○</Text></Pressable>) : null}
    {type==='photoSource' ? <><AppButton title="Kamera Langsung" onPress={()=>{notify('Kamera memerlukan integrasi expo-camera.');close();}}/><AppButton title="Galeri HP" variant="secondary" onPress={()=>{notify('Galeri memerlukan integrasi image picker.');close();}}/></> : null}
    {type==='issue' ? issues.map(v=><Pressable key={v} style={styles.option} onPress={()=>{notify(`Kendala dicatat: ${v}`);close();}}><Text style={styles.optionText}>{v}</Text><Text style={styles.radio}>›</Text></Pressable>) : null}
    {type==='finish' ? <><Text style={styles.description}>Pastikan checklist inspeksi, bukti foto, dan tanda tangan penerima sudah lengkap sebelum menyelesaikan tugas.</Text><AppButton title="Ya, Selesaikan Tugas" onPress={()=>{notify('Tugas selesai pada simulasi lokal.');close();}}/><AppButton title="Periksa Lagi" variant="secondary" onPress={close}/></> : null}
    {type==='errors' ? ['Koneksi Terputus','Stok Tidak Mencukupi','Ukuran File Terlalu Besar (>5MB)'].map(v=><Pressable key={v} style={styles.option} onPress={()=>{notify(v);close();}}><Text style={styles.optionText}>{v}</Text><Text style={styles.radio}>!</Text></Pressable>) : null}
    <AppButton title="Tutup" variant="outline" onPress={close}/>
  </Pressable></Pressable></Modal>;
}
const styles=StyleSheet.create({scrim:{flex:1,backgroundColor:'#14263D88',justifyContent:'flex-end'},sheet:{backgroundColor:'#FFFFFF',borderTopLeftRadius:22,borderTopRightRadius:22,padding:20,paddingBottom:28,minHeight:260},handle:{width:42,height:4,borderRadius:4,backgroundColor:'#D6DEE8',alignSelf:'center',marginBottom:18},option:{paddingVertical:13,borderBottomWidth:1,borderBottomColor:theme.colors.border,flexDirection:'row',justifyContent:'space-between',alignItems:'center'},optionText:{fontSize:13,color:theme.colors.text,fontWeight:'600'},radio:{color:theme.colors.primary,fontSize:17},description:{fontSize:12,color:theme.colors.muted,lineHeight:19,marginBottom:10}});
