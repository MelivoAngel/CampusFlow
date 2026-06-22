import { createRouter, createWebHistory } from 'vue-router'

import LoginView from '../modules/auth/views/LoginView.vue'
import DashboardView from '../modules/dashboard/views/DashboardView.vue'

const routes = [

  {
    path: '/',
    name: 'login',
    component: LoginView
  },

  {
    path: '/dashboard',
    name: 'dashboard',
    component: DashboardView
  }
]

const router = createRouter({

  history: createWebHistory(),

  routes
})

export default router