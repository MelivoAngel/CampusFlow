import { createRouter,createWebHistory } from '@ionic/vue-router'

import LoginView from '../modules/auth/views/LoginView.vue'
import ResourceView from '../modules/resources/views/ResourceView.vue'
import MeterView from '../modules/meters/views/MeterView.vue'
import SubmitReadingView from '../modules/readings/views/SubmitReadingView.vue'
import DashboardView from '../modules/dashboard/views/DashboardView.vue'

const routes = [

  {path: '/',redirect: '/login'},
  {path: '/login',name: 'login',component: LoginView},
  {path: '/resources',name: 'resources',component: ResourceView},
  {path: '/meters/:resource',name: 'meters',component: MeterView},
  {path: '/submit-reading/:meterId',name: 'submit-reading',component: SubmitReadingView},
  {path: '/edit-reading/:readingId',name: 'edit-reading',component: SubmitReadingView},
  {path: '/dashboard',name: 'dashboard',component: DashboardView}

]

const router = createRouter({history: createWebHistory(import.meta.env.BASE_URL),routes})

router.beforeEach((to) => {

  const token =localStorage.getItem('token')

  if (to.name !== 'login' &&! token) {
    return {name: 'login'}
  }
})

export default router