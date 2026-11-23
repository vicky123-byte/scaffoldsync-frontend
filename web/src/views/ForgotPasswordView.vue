<script setup>
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
import { ArrowLeft, Mail, Send } from 'lucide-vue-next'
import BrandLogo from '../components/BrandLogo.vue'
const email = ref('')
const sent = ref(false)
const error = ref('')
function submit() { error.value = ''; if (!email.value.includes('@')) { error.value = 'Masukkan alamat email yang valid.'; return } sent.value = true }
</script>
<template>
  <main class="auth-page"><section class="auth-visual"><div class="auth-visual-brand"><BrandLogo/></div><div class="visual-copy"><div class="eyebrow light-eyebrow"><span></span> ACCOUNT SECURITY</div><h1>Akses akun<br/>tetap <em>terlindungi.</em></h1><p>Gunakan proses pemulihan akun untuk mendapatkan kembali akses ke workspace ScaffoldSync.</p></div><div class="visual-grid"></div></section><section class="auth-panel"><div class="auth-card"><div class="auth-mobile-brand"><BrandLogo/></div><div class="auth-kicker">PEMULIHAN AKUN</div><h2>Lupa kata sandi?</h2><p class="auth-subtitle">Masukkan email akun. Sistem produksi akan mengirim tautan reset satu kali ke email terdaftar.</p><form @submit.prevent="submit"><label class="field-label">Alamat email</label><div class="input-wrap"><Mail :size="18"/><input v-model="email" type="email" required placeholder="nama@perusahaan.com"/></div><div v-if="error" class="form-error" style="margin-top:12px">{{ error }}</div><div v-if="sent" class="form-success">Permintaan reset demo dicatat. Pengiriman email akan aktif setelah SMTP dan API backend terhubung.</div><button class="button button-primary button-full" style="margin-top:20px"><Send :size="16"/> Kirim permintaan reset</button></form><p class="auth-footnote"><RouterLink class="inline-link" to="/login"><ArrowLeft :size="13"/> Kembali ke login</RouterLink></p></div><div class="auth-copyright">© 2026 ScaffoldSync · Construction Connectivity</div></section></main>
</template>
