import React from 'react';
import { Text, View, StyleSheet, Pressable } from 'react-native';
import { AppButton, Card, PageHeading, Row } from '../../components/UI';
import { Product, formatRupiah } from '../../data/mockData';
import { theme } from '../../theme';
type CartLine = { product: Product; quantity: number };
export function CartScreen({ items, setQty, removeItem, navigate }: {
  items: CartLine[]; setQty:(id:string,n:number)=>void; removeItem:(id:string)=>void;
  navigate:(screen:'projectForm'|'catalog')=>void;
}) {
 const dailySubtotal=items.reduce((sum,line)=>sum+line.product.dailyPrice*line.quantity,0);
 return <><PageHeading title="Keranjang" subtitle="Periksa barang pilihan, jumlah unit, dan lanjutkan pengajuan proyek."/>
 {items.length===0 ? <Card style={styles.empty}><Text style={styles.emptyIcon}>🛒</Text><Text style={styles.name}>Keranjang masih kosong</Text><Text style={styles.caption}>Pilih perlengkapan scaffolding dari katalog terlebih dahulu.</Text><AppButton title="Buka Katalog" onPress={()=>navigate('catalog')}/></Card> : <>
 {items.map(({product,quantity})=><Card key={product.id}><Row><View style={styles.icon}><Text style={{fontSize:24,color:theme.colors.primary}}>▦</Text></View><View style={{flex:1}}><Text style={styles.name}>{product.name}</Text><Text style={styles.caption}>{product.code} · {formatRupiah(product.dailyPrice)}/unit/hari</Text><Text style={styles.stock}>{product.stock} unit tersedia</Text></View><Pressable onPress={()=>removeItem(product.id)}><Text style={styles.remove}>Hapus</Text></Pressable></Row><Row style={{marginTop:13}}><AppButton title="−" variant="secondary" onPress={()=>setQty(product.id,Math.max(1,quantity-1))}/><Text style={styles.qty}>{quantity}</Text><AppButton title="＋" variant="secondary" onPress={()=>setQty(product.id,Math.min(product.stock,quantity+1))}/><View style={{flex:1}}/><Text style={styles.lineTotal}>{formatRupiah(product.dailyPrice*quantity)}</Text></Row></Card>)}
 <Card><Text style={styles.caption}>Subtotal estimasi per hari</Text><Text style={styles.total}>{formatRupiah(dailySubtotal)}</Text><Text style={styles.note}>Belum termasuk durasi sewa, pengiriman, dan komponen lain dalam penawaran Admin.</Text><AppButton title="Lanjut Pengajuan Proyek" onPress={()=>navigate('projectForm')}/><AppButton title="Tambah Barang Lain" variant="secondary" onPress={()=>navigate('catalog')}/></Card></>}
 </>;
}
const styles=StyleSheet.create({icon:{height:48,width:48,borderRadius:10,backgroundColor:theme.colors.blueSoft,alignItems:'center',justifyContent:'center'},name:{fontSize:13,fontWeight:'800',color:theme.colors.navy},caption:{fontSize:10,color:theme.colors.muted,marginTop:5},stock:{fontSize:9,color:theme.colors.green,marginTop:4,fontWeight:'700'},qty:{fontSize:16,fontWeight:'900',minWidth:30,textAlign:'center',color:theme.colors.navy},lineTotal:{fontSize:12,fontWeight:'900',color:theme.colors.navy},remove:{fontSize:10,color:theme.colors.red,fontWeight:'800'},total:{fontSize:20,fontWeight:'900',color:theme.colors.navy,marginTop:5},note:{fontSize:9,color:theme.colors.muted,lineHeight:15,marginTop:5},empty:{alignItems:'center',padding:25},emptyIcon:{fontSize:36,marginBottom:12}});
