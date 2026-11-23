<script setup>
import { ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { ArrowRight, Building2, Mail, LockKeyhole, UserRound, Phone } from 'lucide-vue-next'
import { useAuthStore } from '../stores/auth'
import BrandLogo from '../components/BrandLogo.vue'
const auth = useAuthStore()
const router = useRouter()
const form = ref({ name: '', company: '', email: '', phone: '', password: '', confirm: '' })
const error = ref('')
function submit() {
  error.value = ''
  if (!form.value.name.trim() || !form.value.company.trim() || !form.value.email.includes('@') || form.value.password.length < 6 || form.value.password !== form.value.confirm) {
    error.value = 'Lengkapi data. Kata sandi minimal 6 karakter dan konfirmasi harus sama.'; return
  }
  const result = auth.registerCustomer({ name: form.value.name, email: form.value.email, company: form.value.company, phone: form.value.phone })
  if (!result.ok) { error.value = result.message; return }
  router.replace('/app/dashboard')
}
</script>
<template>
  <main class="auth-page"><section class="auth-visual"><div class="auth-visual-brand"><BrandLogo/></div><div class="visual-copy"><div class="eyebrow light-eyebrow"><span></span> CUSTOMER PORTAL</div><h1>Mulai proyek<br/>dengan lebih <em>terencana.</em></h1><p>Buat akun pelanggan untuk mengajukan kebutuhan sewa dan memantau proses penyewaan scaffolding.</p></div><div class="visual-grid"></div></section><section class="auth-panel"><div class="auth-card"><div class="auth-mobile-brand"><BrandLogo/></div><div class="auth-kicker">REGISTRASI PELANGGAN</div><h2>Buat akun baru</h2><p class="auth-subtitle">Daftarkan perusahaan dan informasi kontak Anda.</p><form @submit.prevent="submit"><label class="field-label">Nama penanggung jawab</label><div class="input-wrap"><UserRound :size="18"/><input v-model="form.name" required placeholder="Nama lengkap"/></div><label class="field-label field-label-spaced">Nama perusahaan</label><div class="input-wrap"><Building2 :size="18"/><input v-model="form.company" required placeholder="Nama perusahaan"/></div><label class="field-label field-label-spaced">Alamat email</label><div class="input-wrap"><Mail :size="18"/><input v-model="form.email" type="email" required placeholder="nama@perusahaan.com"/></div><label class="field-label field-label-spaced">Nomor telepon</label><div class="input-wrap"><Phone :size="18"/><input v-model="form.phone" placeholder="08xxxxxxxxxx"/></div><label class="field-label field-label-spaced">Kata sandi</label><div class="input-wrap"><LockKeyhole :size="18"/><input v-model="form.password" type="password" required placeholder="Minimal 6 karakter"/></div><label class="field-label field-label-spaced">Konfirmasi kata sandi</label><div class="input-wrap"><LockKeyhole :size="18"/><input v-model="form.confirm" type="password" required placeholder="Ulangi kata sandi"/></div><div v-if="error" class="form-error">{{ error }}</div><button class="button button-primary button-full" style="margin-top:20px">Daftar sebagai pelanggan <ArrowRight :size="17"/></button></form><p class="auth-footnote">Sudah memiliki akun? <RouterLink class="inline-link" to="/login">Masuk di sini</RouterLink>.<br/>Registrasi pada prototype hanya membuat sesi demo lokal. Penyimpanan akun permanen menunggu API backend.</p></div><div class="auth-copyright">© 2026 ScaffoldSync · Construction Connectivity</div></section></main>
</template>
