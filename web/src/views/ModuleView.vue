<script setup>
import { computed, ref } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useWorkspaceStore } from '../stores/workspace'
import { titles } from '../data/workspace'
import { Plus, Search, SlidersHorizontal, Eye, Pencil, MoreHorizontal, Package, ShoppingCart, CalendarDays, FileText, Download, CheckCircle2, Clock3, MapPin, UserPlus, X, Minus, Trash2, ArrowRight, Send, Save } from 'lucide-vue-next'

const route = useRoute()
const auth = useAuthStore()
const store = useWorkspaceStore()
const role = computed(() => auth.role)
const moduleKey = computed(() => route.params.module)
const title = computed(() => titles[moduleKey.value] || 'Workspace')
const query = ref('')
const categoryFilter = ref('Semua')
const modalOpen = ref(false)
const modalType = ref('')
const saving = ref(false)
const selectedRow = ref(null)
const form = ref({ name: '', code: '', category: 'Scaffolding', total: 100, available: 100, customer: '', contact: '', email: '', phone: '', project: '', period: '', amount: '', address: '', startDate: '', endDate: '', notes: '', documentType: 'Penawaran' })
const money = (v) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(Number(v || 0))
const config = {
  inventory: { description: 'Pantau ketersediaan, pemakaian, dan kondisi setiap unit barang.', collection: 'inventory', columns: [['code','Kode'],['name','Nama barang'],['category','Kategori'],['total','Stok total'],['available','Tersedia'],['rented','Disewa'],['damaged','Rusak']], action: 'Tambah barang' },
  customers: { description: 'Kelola data perusahaan dan kontak pelanggan ScaffoldSync.', collection: 'customers', columns: [['id','ID'],['name','Perusahaan'],['contact','Kontak'],['email','Email'],['phone','Telepon'],['status','Status']], action: 'Tambah pelanggan' },
  orders: { description: 'Tinjau pengajuan, proses penawaran, dan kelola status pesanan sewa.', collection: 'orders', columns: [['id','ID Pesanan'],['customer','Pelanggan'],['project','Proyek'],['period','Periode sewa'],['total','Estimasi nilai'],['status','Status']], action: 'Pesanan baru' },
  projects: { description: 'Data proyek pelanggan yang menggunakan layanan rental scaffolding.', collection: 'projects', columns: [['id','ID Proyek'],['name','Nama proyek'],['customer','Pelanggan'],['address','Lokasi'],['status','Status']] },
  schedules: { description: 'Atur pengiriman, pengambilan, dan penugasan mandor. Validasi bentrok final dilakukan backend.', collection: 'schedules', columns: [['id','ID Jadwal'],['order','Pesanan'],['type','Aktivitas'],['date','Tanggal'],['time','Waktu'],['mandor','Mandor'],['status','Status']], action: 'Tambah jadwal' },
  documents: { description: 'Buat, terbitkan, dan akses dokumen penawaran, surat jalan, serta invoice operasional.', collection: 'documents', columns: [['id','Nomor dokumen'],['type','Jenis dokumen'],['customer','Pelanggan'],['order','Pesanan'],['status','Status']], action: 'Buat dokumen' },
  reports: { description: 'Pantau performa usaha, omzet, penyewaan, dan pemanfaatan inventaris.', collection: 'inventory', columns: [] },
  users: { description: 'Lihat pengguna dan peran pada sistem ScaffoldSync.', collection: 'users', columns: [['id','ID Pengguna'],['name','Nama'],['email','Email'],['role','Peran'],['status','Status']] },
  payments: { description: 'Pantau tagihan dan pembayaran pelanggan.', collection: 'invoices', columns: [['id','Invoice'],['customer','Pelanggan'],['due','Jatuh tempo'],['amount','Total tagihan'],['paid','Terbayar'],['status','Status']] },
  catalog: { description: 'Pilih kebutuhan scaffolding untuk proyek Anda.', collection: 'inventory', columns: [] },
  cart: { description: 'Periksa barang dan jumlah sebelum mengajukan permintaan sewa.', collection: 'cart', columns: [] },
  rentals: { description: 'Pantau status pengajuan dan penyewaan Anda.', collection: 'orders', columns: [['id','ID Pesanan'],['project','Proyek'],['period','Periode'],['total','Nilai'],['status','Status']] },
  invoices: { description: 'Lihat tagihan, invoice, dan status pembayaran.', collection: 'invoices', columns: [['id','Nomor invoice'],['due','Jatuh tempo'],['amount','Tagihan'],['paid','Terbayar'],['status','Status']] },
  profile: { description: 'Informasi akun dan preferensi penggunaan aplikasi.', collection: 'users', columns: [] },
}
const meta = computed(() => config[moduleKey.value] || config.inventory)
const sourceRows = computed(() => {
  const collection = store.data[meta.value.collection] || []
  if (role.value === 'customer' && ['rentals', 'invoices'].includes(moduleKey.value)) return collection.filter((row) => row.customer === (auth.user?.company || 'CV Maju Jaya'))
  return collection
})
const rows = computed(() => sourceRows.value.filter((row) => JSON.stringify(row).toLowerCase().includes(query.value.toLowerCase()) && (moduleKey.value !== 'catalog' || categoryFilter.value === 'Semua' || row.category === categoryFilter.value)))
const imageFor = (category) => category === 'Jackbase' ? '/assets/products/jackbase.png' : category === 'Clamp' ? '/assets/products/clamp-fix.png' : '/assets/products/scaffolding-frame.png'
function openModal(type = moduleKey.value) { modalType.value = type; selectedRow.value = null; modalOpen.value = true; form.value = { name: '', code: '', category: 'Scaffolding', total: 100, available: 100, customer: '', contact: '', email: '', phone: '', project: '', period: '', amount: '', address: '', startDate: '', endDate: '', notes: '', documentType: 'Penawaran' } }
function closeModal() { modalOpen.value = false; selectedRow.value = null }
function openDetail(row) { selectedRow.value = row; modalType.value = 'detail'; modalOpen.value = true }
function openEdit(row) { selectedRow.value = row; modalType.value = `edit-${moduleKey.value}`; form.value = { ...form.value, ...row, amount: row.total ?? row.amount ?? '' }; modalOpen.value = true }
function openPayment(row) { selectedRow.value = row; modalType.value = 'payment'; modalOpen.value = true }
function respondOffer(approved) { if (!selectedRow.value) return; store.updateStatus('orders', selectedRow.value.id, approved ? 'Disetujui' : 'Dibatalkan'); closeModal() }
function submitForm() {
  saving.value = true
  if (modalType.value === 'inventory' && Number(form.value.available) > Number(form.value.total)) { store.notify('Stok tersedia tidak boleh melebihi stok total.'); saving.value = false; return }
  if (modalType.value === 'edit-inventory' && Number(form.value.available) > Number(form.value.total)) { store.notify('Stok tersedia tidak boleh melebihi stok total.'); saving.value = false; return }
  if (modalType.value.startsWith('edit-') && selectedRow.value) {
    if (modalType.value === 'edit-inventory') store.updateRecord('inventory', selectedRow.value.id, { code: form.value.code, name: form.value.name, category: form.value.category, total: Number(form.value.total), available: Number(form.value.available) })
    if (modalType.value === 'edit-customers') store.updateRecord('customers', selectedRow.value.id, { name: form.value.name, contact: form.value.contact, email: form.value.email, phone: form.value.phone })
    saving.value = false; closeModal(); return
  }
  if (modalType.value === 'schedules') {
    const conflict = store.data.schedules.some((item) => item.mandor.toLowerCase() === form.value.name.trim().toLowerCase() && item.date.toLowerCase() === form.value.period.trim().toLowerCase())
    if (conflict) { store.notify('Potensi bentrok mandor pada tanggal yang sama. Periksa kembali jadwal.'); saving.value = false; return }
  }
  if (modalType.value === 'rental-request') {
    if (!form.value.startDate || !form.value.endDate || new Date(form.value.endDate) < new Date(form.value.startDate)) {
      store.notify('Periksa tanggal mulai dan tanggal akhir sewa.'); saving.value = false; return
    }
    const requestedItems = store.data.cart.map((item) => ({ id: item.id, code: item.code, name: item.name, quantity: item.quantity }))
    store.addOrder({
      customer: auth.user?.company || auth.user?.name || 'Pelanggan',
      project: form.value.project,
      address: form.value.address,
      period: `${form.value.startDate} — ${form.value.endDate}`,
      total: 0,
      status: 'Menunggu proses Admin',
      notes: form.value.notes,
      items: requestedItems,
    })
    store.data.cart = []
    store.persist()
    saving.value = false
    closeModal()
    return
  }
  if (modalType.value === 'inventory') store.addInventory({ code: form.value.code || `NEW-${Date.now().toString().slice(-3)}`, name: form.value.name, category: form.value.category, total: Number(form.value.total), available: Number(form.value.available), daily: 0 })
  if (modalType.value === 'customers') store.addCustomer({ name: form.value.name, contact: form.value.contact, email: form.value.email, phone: form.value.phone })
  if (modalType.value === 'documents') { const prefix = form.value.documentType === 'Invoice' ? 'INV' : form.value.documentType === 'Surat jalan' ? 'SJ' : 'OFF'; store.data.documents.unshift({ id: `${prefix}-${String(Date.now()).slice(-4)}`, type: form.value.documentType, customer: form.value.customer, order: form.value.period, status: 'Draft' }); store.persist(); store.notify('Draft dokumen demo berhasil dibuat.') }
  if (modalType.value === 'orders') store.addOrder({ customer: form.value.customer || 'Pelanggan baru', project: form.value.project, period: form.value.period, total: Number(form.value.amount) || 0 })
  if (modalType.value === 'schedules') { store.data.schedules.unshift({ id: `SCH-${Date.now().toString().slice(-4)}`, order: form.value.customer, type: 'Pengiriman', date: form.value.period, time: '08:00', mandor: form.value.name, status: 'Dijadwalkan' }); store.persist(); store.notify('Jadwal demo berhasil ditambahkan.') }
  if (modalType.value === 'payment' && selectedRow.value) store.updateStatus('invoices', selectedRow.value.id, 'Menunggu verifikasi')
  saving.value = false; closeModal()
}
function updateStatus(row) {
  const orderTransitions = {
    'Menunggu proses Admin': 'Menunggu pemeriksaan stok',
    'Menunggu pemeriksaan stok': 'Menunggu penerbitan penawaran',
    'Menunggu penawaran': 'Menunggu persetujuan pelanggan',
    'Disetujui': 'Dijadwalkan',
    'Dijadwalkan': 'Berjalan',
  }
  const documentTransitions = { 'Draft': 'Diterbitkan', 'Menunggu persetujuan pelanggan': 'Diterbitkan', 'Menunggu verifikasi': 'Terverifikasi', 'Diterbitkan': 'Diarsipkan' }
  const paymentTransitions = { 'Belum dibayar': 'Menunggu verifikasi', 'Menunggu verifikasi': 'Lunas' }
  const collection = moduleKey.value === 'documents' ? 'documents' : ['payments', 'invoices'].includes(moduleKey.value) ? 'invoices' : 'orders'
  const transitions = collection === 'documents' ? documentTransitions : collection === 'invoices' ? paymentTransitions : orderTransitions
  const next = transitions[row.status] || row.status
  if (next === row.status) { store.notify('Status ini tidak memiliki aksi demo berikutnya.'); return }
  store.updateStatus(collection, row.id, next)
}
function removeCart(id) { store.data.cart = store.data.cart.filter(row => row.id !== id); store.persist(); store.notify('Barang dihapus dari keranjang.') }
function changeQty(row, delta) { row.quantity = Math.max(1, row.quantity + delta); store.persist() }
const cartTotal = computed(() => store.data.cart.reduce((s, row) => s + row.daily * row.quantity, 0))
function checkout() { if (!store.data.cart.length) { store.notify('Keranjang masih kosong.'); return } openModal('rental-request') }
</script>
<template>
  <div class="page-heading"><div><div class="eyebrow">{{ role === 'customer' ? 'CUSTOMER PORTAL' : role === 'owner' ? 'OWNER OVERVIEW' : 'OPERATIONAL MANAGEMENT' }}</div><h1>{{ title }}</h1><p>{{ meta.description }}</p></div><button v-if="meta.action && role === 'admin'" class="button button-primary" @click="openModal()"><Plus :size="17"/>{{ meta.action }}</button></div>

  <template v-if="moduleKey === 'catalog'">
    <div class="catalog-toolbar"><label class="search-box"><Search :size="17"/><input v-model="query" placeholder="Cari nama barang atau kode..."/></label><div class="filter-pills"><button class="filter-pill" :class="{ active: categoryFilter === 'Semua' }" @click="categoryFilter = 'Semua'">Semua</button><button class="filter-pill" :class="{ active: categoryFilter === 'Scaffolding' }" @click="categoryFilter = 'Scaffolding'">Scaffolding</button><button class="filter-pill" :class="{ active: categoryFilter === 'Jackbase' }" @click="categoryFilter = 'Jackbase'">Jackbase</button><button class="filter-pill" :class="{ active: categoryFilter === 'Clamp' }" @click="categoryFilter = 'Clamp'">Clamp</button></div></div>
    <div class="product-grid"><article v-for="item in rows" :key="item.id" class="product-card"><div class="product-image"><img :src="imageFor(item.category)" :alt="item.name"/><span class="availability-tag">{{ item.available }} tersedia</span></div><div class="product-content"><span class="product-category">{{ item.category }}</span><h3>{{ item.name }}</h3><div class="product-code">{{ item.code }}</div><div class="product-price">{{ money(item.daily) }} <small>/ unit / hari</small></div><button class="button button-primary button-full" @click="store.addToCart(item)"><Plus :size="16"/> Tambah ke keranjang</button></div></article></div>
  </template>

  <template v-else-if="moduleKey === 'cart'">
    <div v-if="store.data.cart.length" class="cart-layout"><section class="surface-card cart-items"><div v-for="item in store.data.cart" :key="item.id" class="cart-row"><img :src="imageFor(item.category)" :alt="item.name"/><div class="cart-info"><strong>{{ item.name }}</strong><small>{{ item.code }} · {{ money(item.daily) }}/hari</small><div class="quantity-control"><button @click="changeQty(item,-1)"><Minus :size="13"/></button><b>{{ item.quantity }}</b><button @click="changeQty(item,1)"><Plus :size="13"/></button></div></div><div class="cart-line-total">{{ money(item.daily * item.quantity) }}<button class="remove-button" @click="removeCart(item.id)"><Trash2 :size="15"/> Hapus</button></div></div></section><aside class="surface-card summary-card"><h3>Ringkasan sewa</h3><div class="summary-line"><span>Jenis barang</span><b>{{ store.data.cart.length }}</b></div><div class="summary-line"><span>Subtotal per hari</span><b>{{ money(cartTotal) }}</b></div><div class="summary-note">Total akhir dihitung kembali berdasarkan durasi dan penawaran dari Admin.</div><button class="button button-primary button-full" @click="checkout">Ajukan permintaan sewa <ArrowRight :size="16"/></button></aside></div><div v-else class="empty-state surface-card"><div class="empty-icon"><ShoppingCart :size="28"/></div><h3>Keranjang masih kosong</h3><p>Tambahkan barang dari katalog untuk memulai pengajuan sewa.</p><RouterLink to="/app/catalog" class="button button-primary">Jelajahi katalog</RouterLink></div>
  </template>

  <template v-else-if="moduleKey === 'reports'">
    <div class="metrics-grid"><article class="metric-card"><span class="metric-top">Omzet bulan ini</span><strong>Rp 48,5 jt</strong><div class="metric-foot positive">↑ 12,4% dibanding bulan lalu</div></article><article class="metric-card"><span class="metric-top">Proyek berjalan</span><strong>5</strong><div class="metric-foot neutral">Aktif dan terjadwal</div></article><article class="metric-card"><span class="metric-top">Barang paling disewa</span><strong>Scaffolding</strong><div class="metric-foot neutral">Kategori tertinggi</div></article><article class="metric-card"><span class="metric-top">Pembayaran diterima</span><strong>Rp 56 jt</strong><div class="metric-foot positive">Periode bulan berjalan</div></article></div>
    <section class="surface-card chart-card report-chart"><div class="section-head"><div><h3>Tren omzet bulanan</h3><p>Visualisasi demonstrasi — menunggu data analitik backend</p></div></div><div class="chart-summary"><strong>Rp 48.500.000</strong><span class="trend-up">+12,4%</span></div><div class="bar-chart tall-bars"><div v-for="(value,index) in [40,52,47,63,58,78,92,74,100,86,95,90]" :key="index" class="bar-column"><div class="bar-value" :style="{height:value+'%'}"></div><small>{{ ['Des','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov'][index] }}</small></div></div></section>
  </template>

  <template v-else-if="moduleKey === 'profile'">
    <section class="surface-card profile-card"><div class="profile-avatar-large">{{ auth.user?.name?.slice(0,1) || 'P' }}</div><div><h2>{{ auth.user?.name }}</h2><p>{{ auth.user?.email }}</p><span class="status-pill aktif">Pelanggan aktif</span></div></section><section class="surface-card profile-form"><h3>Informasi akun</h3><div class="form-grid"><label>Nama lengkap<input v-model="form.name" :placeholder="auth.user?.name"/></label><label>Email<input :value="auth.user?.email" disabled/></label><label>Nomor telepon<input placeholder="Masukkan nomor telepon"/></label><label>Nama perusahaan<input placeholder="Nama perusahaan"/></label></div><button class="button button-primary" @click="store.notify('Form profil demo. Penyimpanan akun menunggu API backend.')"><Save :size="16"/> Simpan perubahan</button></section>
  </template>

  <template v-else>
    <section v-if="moduleKey === 'inventory' || moduleKey === 'payments' || moduleKey === 'invoices'" class="mini-metrics"><div class="mini-metric"><span>{{ moduleKey === 'inventory' ? 'Unit tersedia' : 'Total tagihan' }}</span><strong>{{ moduleKey === 'inventory' ? store.totalAvailable.toLocaleString('id-ID') : money(43000000) }}</strong></div><div class="mini-metric"><span>{{ moduleKey === 'inventory' ? 'Unit disewa' : 'Sudah dibayar' }}</span><strong>{{ moduleKey === 'inventory' ? store.totalRented.toLocaleString('id-ID') : money(25000000) }}</strong></div><div class="mini-metric"><span>{{ moduleKey === 'inventory' ? 'Kategori barang' : 'Belum lunas' }}</span><strong>{{ moduleKey === 'inventory' ? '3 kategori' : money(18000000) }}</strong></div></section>
    <section class="surface-card table-card resource-table"><div class="table-toolbar"><label class="search-box"><Search :size="17"/><input v-model="query" :placeholder="`Cari ${title.toLowerCase()}...`"/></label><button class="filter-button" @click="store.notify('Filter tambahan akan mengikuti kebutuhan data backend.')"><SlidersHorizontal :size="16"/> Filter</button></div><div class="table-scroll"><table><thead><tr><th v-for="col in meta.columns" :key="col[0]">{{ col[1] }}</th><th v-if="['admin','owner'].includes(role) || ['rentals','invoices'].includes(moduleKey)">Aksi</th></tr></thead><tbody><tr v-for="row in rows" :key="row.id"><td v-for="col in meta.columns" :key="col[0]" :class="col[0] === 'id' || col[0] === 'code' ? 'id-cell' : ''"><span v-if="col[0] === 'status'" class="status-pill" :class="String(row[col[0]]).toLowerCase().replaceAll(' ','-')">{{ row[col[0]] }}</span><span v-else-if="col[0] === 'total' && row.status === 'Menunggu proses Admin'">Menunggu penawaran</span><span v-else-if="['amount','paid','total'].includes(col[0]) && typeof row[col[0]] === 'number'">{{ money(row[col[0]]) }}</span><span v-else>{{ row[col[0]] }}</span></td><td v-if="['admin','owner'].includes(role) || ['rentals','invoices'].includes(moduleKey)" class="action-cell"><button class="table-action" title="Lihat detail" @click="openDetail(row)"><Eye :size="16"/></button><button v-if="role==='admin' && ['inventory','customers'].includes(moduleKey)" class="table-action" title="Ubah data" @click="openEdit(row)"><Pencil :size="16"/></button><button v-if="role==='admin' && ((moduleKey==='orders' && ['Menunggu proses Admin','Menunggu pemeriksaan stok','Menunggu penawaran','Disetujui','Dijadwalkan'].includes(row.status)) || (moduleKey==='documents' && ['Draft','Menunggu persetujuan pelanggan','Menunggu verifikasi'].includes(row.status)) || (moduleKey==='payments' && ['Belum dibayar','Menunggu verifikasi'].includes(row.status)))" class="table-action" title="Perbarui status demo" @click="updateStatus(row)"><CheckCircle2 :size="16"/></button><button v-if="role==='customer' && moduleKey==='invoices'" class="table-action" title="Unggah bukti pembayaran" @click="openPayment(row)"><Download :size="16"/></button><button v-if="role==='customer' && moduleKey==='rentals'" class="table-action" title="Lihat penawaran dan status" @click="openDetail(row)"><MoreHorizontal :size="17"/></button></td></tr><tr v-if="rows.length === 0"><td :colspan="meta.columns.length + 1" class="empty-cell">Tidak ada data yang cocok.</td></tr></tbody></table></div><div class="table-bottom"><span>Menampilkan {{ rows.length }} data · Data demo lokal</span><span class="pagination"><button disabled>‹</button><b>1</b><button disabled>›</button></span></div></section>
  </template>

  <div v-if="modalOpen" class="modal-overlay" @click.self="closeModal"><section class="modal-card"><div class="modal-head"><div><span class="eyebrow">FORMULIR DATA</span><h2>{{ modalType === 'edit-inventory' ? 'Ubah barang' : modalType === 'edit-customers' ? 'Ubah pelanggan' : modalType === 'inventory' ? 'Tambah barang' : modalType === 'customers' ? 'Tambah pelanggan' : modalType === 'orders' ? 'Buat pesanan baru' : modalType === 'documents' ? 'Buat dokumen operasional' : modalType === 'rental-request' ? 'Ajukan permintaan sewa' : 'Tambah jadwal' }}</h2></div><button class="icon-button" @click="closeModal"><X :size="19"/></button></div><form @submit.prevent="submitForm" class="modal-form">
    <template v-if="modalType==='detail' && selectedRow"><div class="detail-summary"><div v-for="(value,key) in selectedRow" :key="key" class="detail-row"><span>{{ key }}</span><b>{{ value }}</b></div></div><div v-if="role==='customer' && moduleKey==='rentals' && selectedRow.status==='Menunggu persetujuan pelanggan'" class="modal-actions"><button type="button" class="button button-outline" @click="respondOffer(false)">Tolak penawaran</button><button type="button" class="button button-primary" @click="respondOffer(true)"><CheckCircle2 :size="16"/> Setujui penawaran</button></div><div v-else class="modal-actions"><button type="button" class="button button-primary" @click="closeModal">Tutup</button></div></template>
    <template v-else-if="modalType==='payment'"><p class="payment-note">Invoice: <b>{{ selectedRow?.id }}</b>. Pilih bukti pembayaran untuk simulasi pencatatan. File belum dikirim ke server.</p><label>File bukti pembayaran<input type="file" accept="image/*,.pdf" required/></label><label>Catatan pembayaran<input v-model="form.name" placeholder="Contoh: Transfer bank tanggal ..."/></label><div class="modal-actions"><button type="button" class="button button-outline" @click="closeModal">Batal</button><button class="button button-primary"><Send :size="16"/> Catat bukti demo</button></div></template>
    <template v-else-if="modalType==='rental-request'"><label>Nama proyek<input v-model="form.project" required placeholder="Contoh: Proyek Gedung A"/></label><label>Alamat pengiriman<input v-model="form.address" required placeholder="Alamat lengkap lokasi proyek"/></label><div class="form-grid"><label>Tanggal mulai<input v-model="form.startDate" type="date" required/></label><label>Tanggal selesai<input v-model="form.endDate" type="date" required :min="form.startDate || undefined"/></label></div><label>Catatan tambahan (opsional)<textarea v-model="form.notes" rows="3" placeholder="Kebutuhan akses, jam pengiriman, atau catatan lain"></textarea></label><div class="payment-note">Admin akan memeriksa ketersediaan barang sesuai rentang tanggal. Harga akhir diterbitkan melalui penawaran.</div></template>
    <template v-else-if="['inventory','edit-inventory'].includes(modalType)"><label>Nama barang<input v-model="form.name" required placeholder="Contoh: Scaffolding Frame 2.0 m"/></label><div class="form-grid"><label>Kode barang<input v-model="form.code" placeholder="SCF-001"/></label><label>Kategori<select v-model="form.category"><option>Scaffolding</option><option>Jackbase</option><option>Clamp</option></select></label><label>Stok total<input v-model="form.total" type="number" min="0" required/></label><label>Stok tersedia<input v-model="form.available" type="number" min="0" required/></label></div></template>
    <template v-else-if="['customers','edit-customers'].includes(modalType)"><label>Nama perusahaan<input v-model="form.name" required placeholder="Nama perusahaan pelanggan"/></label><div class="form-grid"><label>Nama kontak<input v-model="form.contact" required/></label><label>Nomor telepon<input v-model="form.phone"/></label></div><label>Email<input v-model="form.email" type="email"/></label></template>
    <template v-else-if="modalType==='documents'"><label>Jenis dokumen<select v-model="form.documentType"><option>Penawaran</option><option>Surat jalan</option><option>Invoice</option></select></label><label>Pelanggan<select v-model="form.customer" required><option value="" disabled>Pilih pelanggan</option><option v-for="c in store.data.customers" :key="c.id" :value="c.name">{{ c.name }}</option></select></label><label>Nomor pesanan<input v-model="form.period" required placeholder="PS-0021"/></label></template>
    <template v-else-if="modalType==='orders'"><label>Pelanggan<select v-model="form.customer" required><option value="" disabled>Pilih pelanggan</option><option v-for="c in store.data.customers" :key="c.id" :value="c.name">{{ c.name }}</option></select></label><label>Nama dan lokasi proyek<input v-model="form.project" required placeholder="Proyek A — Jakarta"/></label><label>Periode sewa<input v-model="form.period" required placeholder="01–30 Desember 2026"/></label><label>Estimasi nilai sewa (Rp)<input v-model="form.amount" type="number" min="0" required/></label></template>
    <template v-else><label>Nomor pesanan<input v-model="form.customer" required placeholder="PS-0021"/></label><label>Mandor<input v-model="form.name" required placeholder="Nama mandor"/></label><label>Tanggal jadwal<input v-model="form.period" required placeholder="20 November 2026"/></label></template>
    <div v-if="!['detail','payment'].includes(modalType)" class="modal-actions"><button type="button" class="button button-outline" @click="closeModal">Batal</button><button class="button button-primary" :disabled="saving"><Save :size="16"/> {{ modalType === 'rental-request' ? 'Kirim permintaan sewa' : modalType === 'documents' ? 'Buat draft dokumen' : modalType.startsWith('edit-') ? 'Simpan perubahan' : 'Simpan data demo' }}</button></div><p class="modal-disclaimer">Perubahan saat ini hanya tersimpan pada browser. Penyimpanan permanen menunggu integrasi API.</p>
  </form></section></div>
</template>
