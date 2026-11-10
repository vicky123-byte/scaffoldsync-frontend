import React from 'react';
import { Linking, Text } from 'react-native';
import { AppButton, Card, PageHeading } from '../../components/UI';
export function HelpScreen() {
 const faq=[['Bagaimana cara menyewa barang?','Pilih barang dari katalog, masukkan ke keranjang, lalu kirim pengajuan dengan tanggal dan lokasi proyek.'],['Kapan penawaran diterbitkan?','Admin memeriksa ketersediaan stok dan jadwal sebelum menerbitkan penawaran.'],['Bagaimana cara membayar invoice?','Buka detail sewa, periksa invoice, kemudian unggah bukti transfer.'],['Bagaimana melaporkan kendala?','Hubungi Admin dengan menyebutkan nomor pesanan dan kendala di lokasi.']];
 return <><PageHeading title="Pusat Bantuan" subtitle="Customer support dan pertanyaan yang sering diajukan."/><Card><Text style={{fontSize:15,fontWeight:'900',color:'#1E2E46'}}>Customer Support 24/7</Text><Text style={{fontSize:11,color:'#718198',marginTop:6,marginBottom:9}}>Hubungi Admin untuk bantuan proses penyewaan.</Text><AppButton title="Chat WhatsApp Admin" onPress={()=>Linking.openURL('https://wa.me/62').catch(()=>{})}/></Card>{faq.map(([q,a])=><Card key={q}><Text style={{fontSize:12,color:'#1E2E46',fontWeight:'900'}}>{q}</Text><Text style={{fontSize:10,color:'#718198',lineHeight:17,marginTop:7}}>{a}</Text></Card>)}</>;
}
