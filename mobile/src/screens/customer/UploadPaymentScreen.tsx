import React from 'react';
import { Alert, Text, View, StyleSheet } from 'react-native';
import { AppButton, AppField, Card, PageHeading } from '../../components/UI';
import { theme } from '../../theme';
export function UploadPaymentScreen({ openPhotoSource, onSent }: { openPhotoSource:()=>void; onSent:()=>void }) {
 return <><PageHeading title="Upload Bukti Transfer" subtitle="Pilih foto struk ATM atau bukti m-banking."/><Card><View style={styles.upload}><Text style={styles.icon}>▧</Text><Text style={styles.title}>Belum ada bukti dipilih</Text><Text style={styles.caption}>JPG, PNG, atau PDF · Maksimal 5 MB</Text></View><AppButton title="Ambil Foto / Pilih Galeri" onPress={openPhotoSource}/><AppField label="Catatan Pembayaran" placeholder="Contoh: DP untuk invoice INV-0012" multiline/><AppButton title="Kirim Bukti Pembayaran" variant="secondary" onPress={onSent}/><Text style={styles.note}>Pemilihan kamera/galeri dan pengiriman file permanen memerlukan integrasi native serta API backend.</Text></Card></>;
}
const styles=StyleSheet.create({upload:{height:170,borderWidth:1,borderStyle:'dashed',borderColor:'#B9CADB',backgroundColor:'#F7FAFD',borderRadius:12,alignItems:'center',justifyContent:'center',marginBottom:12},icon:{fontSize:40,color:theme.colors.primary},title:{fontSize:13,fontWeight:'800',color:theme.colors.navy,marginTop:9},caption:{fontSize:10,color:theme.colors.muted,marginTop:5},note:{fontSize:9,color:theme.colors.muted,lineHeight:15,marginTop:10}});
