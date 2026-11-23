<script setup>
import { computed, ref } from 'vue'
import { useRoute, useRouter, RouterView } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useWorkspaceStore } from '../stores/workspace'
import { menus, titles } from '../data/workspace'
import BrandLogo from '../components/BrandLogo.vue'
import {
  LayoutDashboard, Boxes, Users, ClipboardList, Building2, CalendarDays,
  FileText, ChartNoAxesCombined, Wallet, PackageSearch, ShoppingCart,
  Receipt, UserRound, Bell, Menu, Search, ChevronDown, LogOut, CircleHelp,
} from 'lucide-vue-next'

const icons = { LayoutDashboard, Boxes, Users, ClipboardList, Building2, CalendarDays, FileText, ChartNoAxesCombined, Wallet, PackageSearch, ShoppingCart, Receipt, UserRound }
const auth = useAuthStore()
const workspace = useWorkspaceStore()
const route = useRoute()
const router = useRouter()
const sidebarOpen = ref(false)
const role = computed(() => auth.role || 'admin')
const menu = computed(() => menus[role.value] || menus.admin)
const title = computed(() => titles[route.params.module] || route.meta.title || 'Workspace')
const roleText = computed(() => ({ admin: 'Administrator', owner: 'Pemilik', customer: 'Pelanggan' }[role.value] || 'Pengguna'))
function logout() { auth.logout(); router.replace('/login') }
function go(key) { router.push(`/app/${key}`); sidebarOpen.value = false }
</script>
<template>
  <div class="app-frame">
    <div v-if="sidebarOpen" class="mobile-scrim" @click="sidebarOpen = false"></div>
    <aside class="sidebar" :class="{ open: sidebarOpen }">
      <div class="sidebar-brand"><BrandLogo /></div>
      <div class="workspace-label">WORKSPACE</div>
      <nav class="side-nav">
        <button v-for="item in menu" :key="item.key" class="nav-item" :class="{ active: route.params.module === item.key || (item.key === 'dashboard' && !route.params.module) }" @click="go(item.key)">
          <component :is="icons[item.icon] || LayoutDashboard" :size="18" :stroke-width="1.8" />
          <span>{{ item.label }}</span>
          <span v-if="item.key === 'orders' && role === 'admin'" class="nav-count">3</span>
        </button>
      </nav>
      <div class="sidebar-bottom">
        <div class="support-card"><div class="support-icon"><CircleHelp :size="18" /></div><div><strong>Butuh bantuan?</strong><small>Hubungi tim ScaffoldSync</small></div></div>
        <button class="nav-item logout-item" @click="logout"><LogOut :size="18"/>Keluar</button>
      </div>
    </aside>
    <main class="main-area">
      <header class="topbar">
        <button class="icon-button mobile-menu" aria-label="Buka navigasi" @click="sidebarOpen = true"><Menu :size="20"/></button>
        <div class="breadcrumbs"><span>ScaffoldSync</span><span class="crumb-sep">/</span><strong>{{ title }}</strong></div>
        <div class="topbar-actions">
          <label class="top-search"><Search :size="17"/><input placeholder="Cari menu atau data..." aria-label="Cari menu atau data" /></label>
          <button class="icon-button notification-button" aria-label="Notifikasi" @click="workspace.notify('Belum ada notifikasi baru.')"><Bell :size="19"/><i></i></button>
          <div class="profile-mini"><div class="avatar">{{ auth.user?.name?.slice(0,1) || 'S' }}</div><div class="profile-mini-copy"><strong>{{ auth.user?.name || 'Pengguna' }}</strong><small>{{ roleText }}</small></div><ChevronDown :size="15"/></div>
        </div>
      </header>
      <div class="page-area"><RouterView /></div>
      <footer class="app-footer"><span>© 2026 ScaffoldSync</span><span>Construction Connectivity</span></footer>
    </main>
    <Transition name="toast"><div v-if="workspace.toast" class="toast-message">{{ workspace.toast }}</div></Transition>
  </div>
</template>
