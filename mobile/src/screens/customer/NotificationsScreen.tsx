import React from 'react';
import { Text, View, StyleSheet } from 'react-native';
import { AppBadge, Card, PageHeading } from '../../components/UI';
import { theme } from '../../theme';
const notices=[
 {title:'Penawaran tersedia',body:'Penawaran untuk pesanan PS-0012 siap ditinjau.',time:'Hari ini · 09:30',tone:'blue' as const},
 {title:'Jadwal pengiriman diperbarui',body:'Jadwal pengiriman Proyek A sedang diproses.',time:'Kemarin · 14:10',tone:'orange' as const},
 {title:'Pembayaran diterima',body:'Bukti pembayaran sedang menunggu verifikasi Admin.',time:'Kemarin · 10:05',tone:'green' as const},
];
export function NotificationsScreen() {
 return <><PageHeading title="Notifikasi" subtitle="Informasi terbaru tentang pengajuan dan penyewaan kamu."/>{notices.map(n=><Card key={n.title}><View style={styles.row}><Text style={styles.title}>{n.title}</Text><AppBadge text="Baru" tone={n.tone}/></View><Text style={styles.body}>{n.body}</Text><Text style={styles.time}>{n.time}</Text></Card>)}</>;
}
const styles=StyleSheet.create({row:{flexDirection:'row',justifyContent:'space-between',alignItems:'center',gap:8},title:{fontSize:12,fontWeight:'900',color:theme.colors.navy,flex:1},body:{fontSize:10,color:theme.colors.text,lineHeight:17,marginTop:8},time:{fontSize:9,color:theme.colors.muted,marginTop:8}});
