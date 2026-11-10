import React, { useState } from 'react';
import { Pressable, Text, View, StyleSheet } from 'react-native';
import { AppButton, AppField, Card, PageHeading } from '../../components/UI';
import { theme } from '../../theme';
export function InspectionScreen({ navigate }: { navigate:()=>void }) {
 const [checked,setChecked]=useState([false,false,false]); const labels=['Jumlah barang sesuai surat jalan','Tidak ada kerusakan yang terlewat','Barang lengkap sesuai daftar'];
 return <><PageHeading title="Checklist Inspeksi" subtitle="Periksa kondisi dan kuantitas barang sebelum serah terima."/><Card>{labels.map((label,i)=><Pressable key={label} onPress={()=>setChecked(v=>v.map((x,j)=>i===j?!x:x))} style={styles.check}><Text style={[styles.box,checked[i]&&styles.checked]}>{checked[i]?'✓':' '}</Text><Text style={styles.text}>{label}</Text></Pressable>)}<View style={styles.divider}/><AppField label="Jumlah Baik" defaultValue="40" keyboardType="numeric"/><AppField label="Jumlah Rusak" defaultValue="0" keyboardType="numeric"/><AppField label="Jumlah Kurang / Hilang" defaultValue="0" keyboardType="numeric"/><AppButton title="Simpan Checklist & Lanjut Foto" onPress={navigate}/></Card></>;
}
const styles=StyleSheet.create({check:{paddingVertical:13,borderBottomWidth:1,borderBottomColor:theme.colors.border,flexDirection:'row',alignItems:'center',gap:11},box:{width:22,height:22,borderWidth:1,borderColor:'#AFC0D0',borderRadius:5,textAlign:'center',textAlignVertical:'center',color:'#FFFFFF'},checked:{backgroundColor:theme.colors.green,borderColor:theme.colors.green},text:{fontSize:11,color:theme.colors.text,flex:1},divider:{height:1,backgroundColor:theme.colors.border,marginVertical:13}});
