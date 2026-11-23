<script setup>
import { ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { ArrowRight, Mail, LockKeyhole, Eye, EyeOff, ShieldCheck, Boxes } from 'lucide-vue-next'
import { useAuthStore } from '../stores/auth'
import BrandLogo from '../components/BrandLogo.vue'

const router = useRouter()
const auth = useAuthStore()
const email = ref('admin@scaffoldsync.local')
const password = ref('demo123')
const showPassword = ref(false)
const error = ref('')
const busy = ref(false)
function submit() {
  error.value = ''; busy.value = true
  const result = auth.login(email.value, password.value)
  busy.value = false
  if (!result.ok) { error.value = result.message; return }
  router.push('/app/dashboard')
}
function fill(role) {
  email.value = ({ admin: 'admin@scaffoldsync.local', owner: 'owner@scaffoldsync.local', customer: 'customer@scaffoldsync.local' })[role]
  password.value = 'demo123'
}
</script>
<template>
  <main class="auth-page">
    <section class="auth-visual">
      <div class="auth-visual-brand"><BrandLogo /></div>
      <div class="visual-copy"><div class="eyebrow light-eyebrow"><span></span> CONSTRUCTION CONNECTIVITY</div><h1>Kelola operasional<br/>lebih <em>terhubung.</em></h1><p>Platform terpadu untuk mengelola inventaris, penyewaan, jadwal, dan aktivitas proyek scaffolding.</p><div class="visual-stats"><div><strong>Terintegrasi</strong><small>Data operasional</small></div><div><strong>Terukur</strong><small>Aktivitas proyek</small></div><div><strong>Terpantau</strong><small>Status inventaris</small></div></div></div>
      <div class="visual-orbit orbit-one"></div><div class="visual-orbit orbit-two"></div><div class="visual-grid"></div>
    </section>
    <section class="auth-panel">
      <div class="auth-card">
        <div class="auth-mobile-brand"><BrandLogo /></div>
        <div class="auth-kicker">SELAMAT DATANG KEMBALI</div>
        <h2>Masuk ke akun Anda</h2>
        <p class="auth-subtitle">Gunakan email dan kata sandi yang terdaftar untuk melanjutkan.</p>
        <form @submit.prevent="submit">
          <label class="field-label">Alamat email</label>
          <div class="input-wrap"><Mail :size="18"/><input v-model="email" type="email" required autocomplete="username" placeholder="nama@perusahaan.com"/></div>
          <label class="field-label field-label-spaced">Kata sandi</label>
          <div class="input-wrap"><LockKeyhole :size="18"/><input v-model="password" :type="showPassword ? 'text' : 'password'" required autocomplete="current-password" placeholder="Masukkan kata sandi"/><button type="button" class="reveal-button" @click="showPassword = !showPassword"><Eye v-if="!showPassword" :size="17"/><EyeOff v-else :size="17"/></button></div>
          <div class="form-meta"><span></span><RouterLink to="/forgot-password">Lupa kata sandi?</RouterLink></div>
          <div v-if="error" class="form-error">{{ error }}</div>
          <button class="button button-primary button-full" :disabled="busy">{{ busy ? 'Memeriksa...' : 'Masuk ke ScaffoldSync' }} <ArrowRight :size="17"/></button>
        </form>
        <div class="demo-divider"><span>AKSES DEMO FRONTEND</span></div>
        <div class="demo-role-grid"><button @click="fill('admin')"><ShieldCheck :size="16"/>Admin</button><button @click="fill('owner')"><Boxes :size="16"/>Pemilik</button><button @click="fill('customer')"><Mail :size="16"/>Pelanggan</button></div>
        <p class="auth-footnote">Belum memiliki akun? <RouterLink class="inline-link" to="/register">Daftar sebagai pelanggan</RouterLink>.<br/>Akun demo menggunakan kata sandi <b>demo123</b>. Data pada versi ini disimpan lokal di browser.</p>
      </div>
      <div class="auth-copyright">© 2026 ScaffoldSync · Construction Connectivity</div>
    </section>
  </main>
</template>
