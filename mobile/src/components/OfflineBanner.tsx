import React from 'react';
import { StyleSheet, Text, View } from 'react-native';
export function OfflineBanner() { return <View style={styles.banner}><Text style={styles.text}>⚠ Mode Offline · Data lokal menunggu sinkronisasi saat koneksi tersedia</Text></View>; }
const styles=StyleSheet.create({banner:{backgroundColor:'#FFF2DA',paddingHorizontal:12,paddingVertical:9},text:{fontSize:10,color:'#9A5A05',fontWeight:'700',textAlign:'center'}});
