import React from 'react';
import { Pressable, StyleSheet, Text, View } from 'react-native';
import { theme } from '../theme';
import { AppRole, ScreenName } from '../types/navigation';
const customerItems: Array<[string, ScreenName, string]> = [['Beranda','home','⌂'],['Katalog','catalog','▦'],['Keranjang','cart','＋'],['Sewa','rentals','▤'],['Profil','profile','◉']];
const mandorItems: Array<[string, ScreenName, string]> = [['Tugas','mandorDashboard','▣'],['Profil','profile','◉']];
export function BottomNavigation({ role, active, navigate }: { role: AppRole; active: ScreenName; navigate: (screen: ScreenName) => void }) {
  const items=role==='mandor'?mandorItems:customerItems;
  return <View style={styles.nav}>{items.map(([label,screen,icon])=><Pressable key={screen} onPress={()=>navigate(screen)} style={styles.item}><Text style={[styles.icon,active===screen&&styles.active]}>{icon}</Text><Text style={[styles.label,active===screen&&styles.active]}>{label}</Text></Pressable>)}</View>;
}
const styles=StyleSheet.create({nav:{minHeight:63,backgroundColor:'#FFFFFF',borderTopWidth:1,borderTopColor:theme.colors.border,flexDirection:'row',justifyContent:'space-around',alignItems:'center',paddingHorizontal:5},item:{alignItems:'center',justifyContent:'center',minWidth:52,paddingVertical:5},icon:{fontSize:19,color:theme.colors.muted},label:{fontSize:9,color:theme.colors.muted,marginTop:2},active:{color:theme.colors.primary,fontWeight:'900'}});
