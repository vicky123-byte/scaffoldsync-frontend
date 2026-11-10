import React from 'react';
import { Pressable, StyleSheet, Text, View } from 'react-native';
import { AppRole } from '../types/navigation';
import { theme } from '../theme';

export function AppHeader({ role, onToggleOffline, offline }: { role: AppRole; onToggleOffline: () => void; offline: boolean }) {
  return <View style={styles.header}><View><Text style={styles.brand}>Scaffold<Text style={styles.brandAccent}>Sync</Text></Text><Text style={styles.tagline}>{role === 'mandor' ? 'FIELD OPERATIONS' : 'CONSTRUCTION CONNECTIVITY'}</Text></View><Pressable onPress={onToggleOffline} style={styles.statusButton}><Text style={styles.statusText}>{offline ? '● Offline' : '● Online'}</Text></Pressable></View>;
}
const styles=StyleSheet.create({header:{height:66,backgroundColor:'#FFFFFF',borderBottomWidth:1,borderBottomColor:theme.colors.border,paddingHorizontal:17,flexDirection:'row',alignItems:'center',justifyContent:'space-between'},brand:{fontSize:20,fontWeight:'900',color:theme.colors.primaryDark},brandAccent:{color:theme.colors.orange},tagline:{fontSize:7,color:theme.colors.muted,fontWeight:'800',letterSpacing:1.1,marginTop:2},statusButton:{backgroundColor:'#F0F5F9',paddingHorizontal:10,paddingVertical:7,borderRadius:18},statusText:{fontSize:10,color:theme.colors.green,fontWeight:'800'}});
