import React from 'react';
import { Text, View, StyleSheet } from 'react-native';
import { AppButton, Card, PageHeading } from '../../components/UI';
import { theme } from '../../theme';
export function PhotoEvidenceScreen({ navigate }: { navigate:()=>void }) {
 return <><PageHeading title="Bukti Foto Lokasi" subtitle="Ambil foto barang terpasang atau terbongkar di lokasi proyek."/><Card><View style={styles.upload}><Text style={{fontSize:44,color:theme.colors.primary}}>▧</Text><Text style={styles.title}>Area Foto Bukti</Text><Text style={styles.caption}>Foto serah terima belum dipilih.</Text></View><AppButton title="Buka Kamera (integrasi diperlukan)" onPress={()=>{}}/><AppButton title="Pilih dari Galeri" variant="secondary" onPress={()=>{}}/><AppButton title="Lanjut Tanda Tangan" onPress={navigate}/><Text style={styles.note}>Kamera asli dan galeri memerlukan permission perangkat serta paket Expo Image Picker/Camera.</Text></Card></>;
}
const styles=StyleSheet.create({upload:{height:170,borderWidth:1,borderStyle:'dashed',borderColor:'#B9CADB',backgroundColor:'#F7FAFD',borderRadius:12,alignItems:'center',justifyContent:'center'},title:{fontSize:13,fontWeight:'900',color:theme.colors.navy,marginTop:9},caption:{fontSize:10,color:theme.colors.muted,marginTop:5},note:{fontSize:9,color:theme.colors.muted,lineHeight:15,marginTop:12}});
