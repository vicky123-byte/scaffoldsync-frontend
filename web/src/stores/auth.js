import { defineStore } from 'pinia'
import { computed, ref } from 'vue'

const DEMO_USERS = {
  'admin@scaffoldsync.local': { password: 'demo123', role: 'admin', name: 'Rina Administrator' },
  'owner@scaffoldsync.local': { password: 'demo123', role: 'owner', name: 'Ade — Pemilik' },
  'customer@scaffoldsync.local': { password: 'demo123', role: 'customer', name: 'Budi Santoso', company: 'CV Maju Jaya', phone: '0812-3456-7890' },
}

export const useAuthStore = defineStore('auth', () => {
  const stored = (() => { try { return JSON.parse(localStorage.getItem('scaffoldsync.auth') || 'null') } catch { return null } })()
  const user = ref(stored)
  const isAuthenticated = computed(() => Boolean(user.value?.role))
  const role = computed(() => user.value?.role || null)
  function login(email, password) {
    const account = DEMO_USERS[email.trim().toLowerCase()]
    if (!account || account.password !== password) return { ok: false, message: 'Email atau kata sandi tidak sesuai.' }
    user.value = { email: email.trim().toLowerCase(), name: account.name, role: account.role, company: account.company || '', phone: account.phone || '' }
    localStorage.setItem('scaffoldsync.auth', JSON.stringify(user.value))
    return { ok: true }
  }
  function registerCustomer({ name, email, company = '', phone = '' }) {
    const normalizedEmail = email.trim().toLowerCase()
    if (!name.trim() || !normalizedEmail.includes('@')) return { ok: false, message: 'Periksa nama dan alamat email.' }
    user.value = { email: normalizedEmail, name: name.trim(), company: company.trim(), phone: phone.trim(), role: 'customer' }
    localStorage.setItem('scaffoldsync.auth', JSON.stringify(user.value))
    return { ok: true }
  }
  function logout() { user.value = null; localStorage.removeItem('scaffoldsync.auth') }
  function setDemoRole(nextRole) {
    const account = Object.entries(DEMO_USERS).find(([, value]) => value.role === nextRole)
    if (!account) return
    user.value = { email: account[0], name: account[1].name, role: nextRole, company: account[1].company || '', phone: account[1].phone || '' }
    localStorage.setItem('scaffoldsync.auth', JSON.stringify(user.value))
  }
  return { user, role, isAuthenticated, login, registerCustomer, logout, setDemoRole }
})
