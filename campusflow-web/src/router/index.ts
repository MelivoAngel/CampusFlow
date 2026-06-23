import { createRouter, createWebHistory } from 'vue-router'

import LoginView from '../modules/auth/views/LoginView.vue'
import DashboardView from '../modules/dashboard/views/DashboardView.vue'

const routes = [

  {
    path: '/',

    name: 'login',

    component: LoginView,

    meta: {

      guestOnly: true
    }
  },

  {
    path: '/dashboard',

    name: 'dashboard',

    component: DashboardView,

    meta: {

      requiresAuth: true
    }
  }
]

const router = createRouter({

  history: createWebHistory(),

  routes
})

router.beforeEach(

  (to) => {

    const token =
      localStorage.getItem(
        'token'
      )

    if (

      to.meta.requiresAuth &&

      !token
    ) {
      return {

        name: 'login'
      }
    }

    if (

      to.meta.guestOnly &&

      token
    ) {
      return {

        name: 'dashboard'
      }
    }
  }
)

export default router