import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import ForgotPasswordView from '../views/ForgotPasswordView.vue'
import AppShell from '../layouts/AppShell.vue'
import DashboardView from '../views/DashboardView.vue'
import ModuleView from '../views/ModuleView.vue'
import { menus } from '../data/workspace'

const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', component: LoginView, meta: { guestOnly: true } },
  { path: '/register', component: RegisterView, meta: { guestOnly: true } },
  { path: '/forgot-password', component: ForgotPasswordView, meta: { guestOnly: true } },
  { path: '/app', component: AppShell, meta: { requiresAuth: true }, children: [
    { path: '', redirect: '/app/dashboard' },
    { path: 'dashboard', component: DashboardView, meta: { roles: ['admin','owner','customer'], title: 'Dashboard' } },
    { path: ':module', component: ModuleView, meta: { roles: ['admin','owner','customer'], title: 'Workspace' } },
  ] },
  { path: '/:pathMatch(.*)*', redirect: '/login' },
]

const router = createRouter({ history: createWebHistory(), routes })
router.beforeEach((to) => {
  const auth = useAuthStore()
  if (to.meta.requiresAuth && !auth.isAuthenticated) return '/login'
  if (to.meta.guestOnly && auth.isAuthenticated) return '/app/dashboard'
  if (to.meta.roles && auth.role && !to.meta.roles.includes(auth.role)) return '/app/dashboard'
  if (to.params.module && auth.role && !['dashboard', ...(menus[auth.role] || []).map((item) => item.key)].includes(to.params.module)) return '/app/dashboard'
  return true
})
export default router
