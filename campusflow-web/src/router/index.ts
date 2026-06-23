import { createRouter,createWebHistory } from 'vue-router'

import LoginView from '../modules/auth/views/LoginView.vue'
import DashboardView from '../modules/dashboard/views/DashboardView.vue'
import UserView from '../modules/users/views/UserView.vue'
import MeterView from '../modules/meters/views/MeterView.vue'
import BuildingView from '../modules/buildings/views/BuildingView.vue'
import ApprovalView from '../modules/approvals/views/ApprovalView.vue'

const routes = [
  { path: '/', name: 'login', component: LoginView, meta: { guestOnly: true } },
  { path: '/dashboard', name: 'dashboard', component: DashboardView, meta: { requiresAuth: true } },
  { path: '/users', name: 'users', component: UserView, meta: { requiresAuth: true } },
  { path: '/meters', name: 'meters', component: MeterView, meta: { requiresAuth: true } },
  { path: '/buildings', name: 'buildings', component: BuildingView, meta: { requiresAuth: true } },
  { path: '/approvals', name: 'approvals', component: ApprovalView, meta: { requiresAuth: true } }
]


const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to) => {

  const token =
    localStorage.getItem('token')

  if (
    to.meta.requiresAuth &&
    !token
  ) {
    return { name: 'login' }
  }

  if (
    to.meta.guestOnly &&
    token
  ) {
    return { name: 'dashboard' }
  }
})

export default router