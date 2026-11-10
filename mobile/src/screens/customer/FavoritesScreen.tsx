import React from 'react';
import { Text, Pressable, View, StyleSheet } from 'react-native';
import { AppBadge, Card, PageHeading, Row } from '../../components/UI';
import { Product, formatRupiah } from '../../data/mockData';
import { theme } from '../../theme';
export function FavoritesScreen({ products, selectProduct, toggleFavorite }: { products:Product[]; selectProduct:(p:Product)=>void; toggleFavorite:(id:string)=>void }) {
 return <><PageHeading title="Barang Favorit" subtitle="Daftar barang yang kamu simpan untuk kebutuhan proyek."/>{products.length===0?<Card><Text style={styles.empty}>Belum ada barang favorit. Simpan barang dari detail katalog.</Text></Card>:products.map(p=><Card key={p.id}><Row><View style={styles.icon}><Text style={{fontSize:22,color:theme.colors.primary}}>▦</Text></View><View style={{flex:1}}><Pressable onPress={()=>selectProduct(p)}><Text style={styles.name}>{p.name}</Text><Text style={styles.caption}>{p.code} · {formatRupiah(p.dailyPrice)}/hari</Text></Pressable></View><AppBadge text={`${p.stock} tersedia`} tone="green"/></Row><Pressable onPress={()=>toggleFavorite(p.id)}><Text style={styles.remove}>Hapus dari favorit</Text></Pressable></Card>)}</>;
}
const styles=StyleSheet.create({icon:{width:44,height:44,borderRadius:9,backgroundColor:theme.colors.blueSoft,alignItems:'center',justifyContent:'center'},name:{fontSize:12,fontWeight:'900',color:theme.colors.navy},caption:{fontSize:10,color:theme.colors.muted,marginTop:4},remove:{fontSize:10,color:theme.colors.red,fontWeight:'800',marginTop:12},empty:{fontSize:11,color:theme.colors.muted,lineHeight:18}});
