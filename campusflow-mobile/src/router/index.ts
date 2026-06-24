import {
  createRouter,
  createWebHistory
} from '@ionic/vue-router'

import LoginView from '../modules/auth/views/LoginView.vue'
import MeterView from '../modules/meters/views/MeterView.vue'

const routes = [

  { path: '/',redirect: '/login' },

  {
    path: '/login',
    name: 'login',
    component: LoginView
  },

  {
    path: '/meters',
    name: 'meters',
    component: MeterView
  }
]

const router = createRouter({

  history: createWebHistory(
    import.meta.env.BASE_URL
  ),

  routes
})

router.beforeEach((to) => {

  const token =
    localStorage.getItem('token')

  if (
    to.name !== 'login' &&
    !token
  ) {
    return { name: 'login' }
  }

  if (
    to.name === 'login' &&
    token
  ) {
    return { name: 'meters' }
  }
})

export default router