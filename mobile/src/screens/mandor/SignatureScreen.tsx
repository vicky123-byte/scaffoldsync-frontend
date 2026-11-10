import React, { useState } from 'react';
import { Text, View, StyleSheet } from 'react-native';
import { AppButton, AppField, Card, PageHeading } from '../../components/UI';
import { theme } from '../../theme';
export function SignatureScreen({ navigate }: { navigate:()=>void }) {
 const [signed,setSigned]=useState(false);
 return <><PageHeading title="Tanda Tangan Digital" subtitle="Minta penerima menandatangani bukti serah terima."/><Card><AppField label="Nama Penerima" placeholder="Nama lengkap penerima"/><View style={styles.pad}><Text style={styles.padText}>{signed?'✓ Tanda tangan telah dikonfirmasi':'Area tanda tangan penerima'}</Text><Text style={styles.padHint}>Kanvas tanda tangan perlu integrasi drawing component.</Text></View><AppButton title={signed?'Hapus Konfirmasi':'Tandai Sudah Ditandatangani'} variant="secondary" onPress={()=>setSigned(v=>!v)}/><AppButton title="Lihat Ringkasan BAST" onPress={navigate}/></Card></>;
}
const styles=StyleSheet.create({pad:{height:180,borderWidth:1,borderColor:'#C9D5E1',borderRadius:10,alignItems:'center',justifyContent:'center',backgroundColor:'#FAFCFE',marginVertical:13},padText:{fontSize:13,fontWeight:'800',color:theme.colors.navy},padHint:{fontSize:9,color:theme.colors.muted,marginTop:6}});
