import React from 'react';
import { Pressable, Text, StyleSheet, View } from 'react-native';
import { AppBadge, Card, PageHeading, AppButton } from '../../components/UI';
import { taskItems } from '../../data/mockData';
import { theme } from '../../theme';
export function MandorDashboardScreen({ navigate, offline, toggleOffline }: { navigate:()=>void; offline:boolean; toggleOffline:()=>void }) {
 return <><PageHeading title="Tugas Hari Ini" subtitle="Halo, Andi. Berikut penugasan lapangan kamu."/><Card style={styles.hero}><Text style={styles.heroLabel}>Penugasan hari ini</Text><Text style={styles.heroNumber}>2 Tugas</Text><Text style={styles.heroLabel}>1 pengiriman · 1 pengambilan</Text></Card>{taskItems.map(t=><Pressable key={t.id} onPress={navigate}><Card><View style={styles.row}><AppBadge text={t.type} tone={t.type==='Pengiriman'?'blue':'orange'}/><Text style={styles.time}>{t.time}</Text></View><Text style={styles.title}>{t.id} · {t.project}</Text><Text style={styles.caption}>{t.client}</Text><Text style={styles.caption}>{t.address}</Text><Text style={styles.link}>Buka detail tugas →</Text></Card></Pressable>)}<AppButton title={offline?'Aktifkan Koneksi':'Simulasikan Mode Offline'} variant="secondary" onPress={toggleOffline}/></>;
}
const styles=StyleSheet.create({hero:{backgroundColor:theme.colors.primaryDark},heroLabel:{fontSize:11,color:'#D8E9F7'},heroNumber:{fontSize:30,color:'#FFFFFF',fontWeight:'900',marginVertical:6},row:{flexDirection:'row',justifyContent:'space-between',alignItems:'center'},time:{fontSize:11,fontWeight:'800',color:theme.colors.navy},title:{fontSize:14,fontWeight:'900',color:theme.colors.navy,marginTop:12},caption:{fontSize:10,color:theme.colors.muted,marginTop:5},link:{fontSize:11,color:theme.colors.primary,fontWeight:'800',marginTop:12}});
