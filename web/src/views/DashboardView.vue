<script setup>
import { computed } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useWorkspaceStore } from '../stores/workspace'
import { useRouter } from 'vue-router'
import { ArrowUpRight, ArrowDownRight, Plus, ArrowRight, Package, Wallet, Truck, AlertTriangle, CalendarDays, ClipboardList } from 'lucide-vue-next'

const auth = useAuthStore()
const workspace = useWorkspaceStore()
const router = useRouter()
const role = computed(() => auth.role)
const greeting = computed(() => ({ admin: 'Selamat datang, Admin', owner: 'Selamat datang, Pemilik', customer: `Halo, ${auth.user?.name?.split(' ')[0] || 'Pelanggan'}` }[role.value]))
const recentOrders = computed(() => role.value === 'customer' ? workspace.data.orders.filter((row) => row.customer === (auth.user?.company || 'CV Maju Jaya')).slice(0, 2) : workspace.data.orders.slice(0, 4))
const money = (v) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(v)
</script>
<template>
  <div class="page-heading"><div><div class="eyebrow">OVERVIEW</div><h1>{{ greeting }}</h1><p>{{ role === 'customer' ? 'Pantau kebutuhan sewa dan aktivitas proyek Anda.' : 'Berikut ringkasan aktivitas operasional ScaffoldSync hari ini.' }}</p></div><button v-if="role === 'admin'" class="button button-primary" @click="router.push('/app/orders')"><Plus :size="17"/> Pesanan baru</button><button v-else-if="role === 'customer'" class="button button-primary" @click="router.push('/app/catalog')">Jelajahi katalog <ArrowRight :size="17"/></button><div v-else class="date-chip"><CalendarDays :size="16"/> Kamis, 19 November 2026</div></div>
  <div v-if="role === 'customer'" class="welcome-banner"><div><span class="banner-kicker">SCAFFOLDING RENTAL, SIMPLIFIED</span><h2>Mulai proyek dengan<br/>perencanaan yang lebih baik.</h2><p>Temukan barang, ajukan sewa, dan pantau status pesanan dari satu tempat.</p><button class="button button-light" @click="router.push('/app/catalog')">Lihat katalog <ArrowRight :size="16"/></button></div><div class="banner-art"><div class="art-line art-line-a"></div><div class="art-line art-line-b"></div><div class="art-block"></div></div></div>
  <div v-else class="metrics-grid">
    <article class="metric-card"><div class="metric-top"><span>Total inventaris tersedia</span><span class="metric-icon icon-blue"><Package :size="19"/></span></div><strong>{{ workspace.totalAvailable.toLocaleString('id-ID') }} <small>unit</small></strong><div class="metric-foot positive"><ArrowUpRight :size="14"/> Kondisi stok terpantau</div></article>
    <article class="metric-card"><div class="metric-top"><span>Barang sedang disewa</span><span class="metric-icon icon-orange"><Truck :size="19"/></span></div><strong>{{ workspace.totalRented.toLocaleString('id-ID') }} <small>unit</small></strong><div class="metric-foot neutral">Tersebar di proyek aktif</div></article>
    <article class="metric-card"><div class="metric-top"><span>Pesanan aktif</span><span class="metric-icon icon-purple"><ClipboardList :size="19"/></span></div><strong>{{ workspace.data.orders.length }} <small>pesanan</small></strong><div class="metric-foot positive"><ArrowUpRight :size="14"/> Perlu pemantauan</div></article>
    <article class="metric-card"><div class="metric-top"><span>Tagihan belum lunas</span><span class="metric-icon icon-green"><Wallet :size="19"/></span></div><strong>{{ money(workspace.data.invoices.filter(i => i.status !== 'Lunas').reduce((s,i)=>s+i.amount-i.paid,0)) }}</strong><div class="metric-foot warning"><AlertTriangle :size="14"/> Periksa jatuh tempo</div></article>
  </div>
  <div v-if="role !== 'customer'" class="dashboard-grid">
    <section class="surface-card chart-card"><div class="section-head"><div><h3>Aktivitas penyewaan</h3><p>Ringkasan tren transaksi bulanan (data demo)</p></div><span class="select-chip">7 bulan terakhir</span></div><div class="chart-summary"><strong>Rp 48.500.000</strong><span class="trend-up"><ArrowUpRight :size="15"/> 12,4%</span></div><div class="bar-chart"><div v-for="(value,index) in [42,58,48,72,61,86,96]" :key="index" class="bar-column"><div class="bar-value" :style="{height: value + '%'}"></div><small>{{ ['Mei','Jun','Jul','Agu','Sep','Okt','Nov'][index] }}</small></div></div></section>
    <section class="surface-card stock-card"><div class="section-head"><div><h3>Kondisi inventaris</h3><p>Distribusi status barang</p></div><button class="text-action" @click="router.push('/app/inventory')">Lihat detail <ArrowRight :size="14"/></button></div><div class="stock-visual"><div class="donut"><div><strong>1.460</strong><small>Total unit</small></div></div><div class="legend-list"><span><i class="dot dot-blue"></i>Tersedia <b>890</b></span><span><i class="dot dot-orange"></i>Disewa <b>540</b></span><span><i class="dot dot-red"></i>Rusak <b>30</b></span></div></div></section>
  </div>
  <section class="surface-card table-card"><div class="section-head"><div><h3>{{ role === 'customer' ? 'Aktivitas pesanan' : 'Pesanan terbaru' }}</h3><p>{{ role === 'customer' ? 'Perkembangan pengajuan sewa Anda.' : 'Pantau pesanan dan status proses operasional.' }}</p></div><button class="text-action" @click="router.push(role === 'customer' ? '/app/rentals' : '/app/orders')">Lihat semua <ArrowRight :size="14"/></button></div><div class="table-scroll"><table><thead><tr><th>ID Pesanan</th><th>Pelanggan</th><th>Proyek</th><th>Periode</th><th>Nilai</th><th>Status</th></tr></thead><tbody><tr v-for="row in recentOrders" :key="row.id"><td class="id-cell">{{ row.id }}</td><td>{{ row.customer }}</td><td>{{ row.project }}</td><td>{{ row.period }}</td><td>{{ row.total ? money(row.total) : 'Menunggu penawaran' }}</td><td><span class="status-pill" :class="row.status.toLowerCase().replaceAll(' ','-')">{{ row.status }}</span></td></tr></tbody></table></div></section>
</template>
