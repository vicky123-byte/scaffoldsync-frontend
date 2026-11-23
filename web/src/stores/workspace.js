import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { initialData } from '../data/workspace'

function restore() {
  try { const value = JSON.parse(localStorage.getItem('scaffoldsync.demo-data') || 'null'); return value || structuredClone(initialData) }
  catch { return structuredClone(initialData) }
}

export const useWorkspaceStore = defineStore('workspace', () => {
  const data = ref(restore())
  const toast = ref('')
  const totalAvailable = computed(() => data.value.inventory.reduce((sum, item) => sum + item.available, 0))
  const totalRented = computed(() => data.value.inventory.reduce((sum, item) => sum + item.rented, 0))
  function persist() { localStorage.setItem('scaffoldsync.demo-data', JSON.stringify(data.value)) }
  function notify(message) { toast.value = message; window.setTimeout(() => { if (toast.value === message) toast.value = '' }, 3000) }
  function addOrder(order) {
    data.value.orders.unshift({ id: `PS-${String(Date.now()).slice(-4)}`, status: 'Menunggu penawaran', ...order })
    persist(); notify('Pengajuan pesanan demo berhasil dicatat.')
  }
  function addCustomer(customer) {
    data.value.customers.unshift({ id: `CUS-${String(Date.now()).slice(-4)}`, status: 'Aktif', ...customer })
    persist(); notify('Data pelanggan demo berhasil ditambahkan.')
  }
  function addInventory(item) {
    data.value.inventory.unshift({ id: `BRG-${String(Date.now()).slice(-4)}`, rented: 0, damaged: 0, ...item })
    persist(); notify('Barang demo berhasil ditambahkan.')
  }
  function addToCart(item) {
    const existing = data.value.cart.find((row) => row.id === item.id)
    if (existing) existing.quantity += 1
    else data.value.cart.push({ ...item, quantity: 1 })
    persist(); notify('Barang ditambahkan ke keranjang.')
  }
  function updateStatus(collection, id, status) {
    const row = data.value[collection]?.find((item) => item.id === id)
    if (!row) return
    row.status = status; persist(); notify('Status demo berhasil diperbarui.')
  }
  function updateRecord(collection, id, changes) {
    const row = data.value[collection]?.find((item) => item.id === id)
    if (!row) return
    Object.assign(row, changes)
    persist(); notify('Data demo berhasil diperbarui.')
  }
  return { data, toast, totalAvailable, totalRented, persist, notify, addOrder, addCustomer, addInventory, addToCart, updateStatus, updateRecord }
})
