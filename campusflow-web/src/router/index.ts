import { createRouter,createWebHistory } from 'vue-router'

import { useAuthStore } from '../stores/authStore'

import LoginView from '../modules/auth/views/LoginView.vue'
import DashboardView from '../modules/dashboard/views/DashboardView.vue'
import UserView from '../modules/users/views/UserView.vue'
import MeterView from '../modules/meters/views/MeterView.vue'
import BuildingView from '../modules/buildings/views/BuildingView.vue'
import ApprovalView from '../modules/approvals/views/ApprovalView.vue'
import AnomalyView from '../modules/anomalies/views/AnomalyView.vue'
import FacilityView from '../modules/facilities/views/FacilityView.vue'
import ScheduleView from '../modules/schedules/views/ScheduleView.vue'

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
  },

  {
    path: '/users',

    name: 'users',

    component: UserView,

    meta: {
      requiresAuth: true,

      roles: [

        'super_admin',

        'campus_admin',

        'staff'
      ]
    }
  },

  {
    path: '/meters',

    name: 'meters',

    component: MeterView,

    meta: {
      requiresAuth: true,

      roles: [

        'super_admin',

        'campus_admin',

        'staff'
      ]
    }
  },

  {
    path: '/buildings',

    name: 'buildings',

    component: BuildingView,

    meta: {
      requiresAuth: true,

      roles: [

        'super_admin',

        'campus_admin',

        'staff'
      ]
    }
  },

  {
    path: '/facilities',

    name: 'facilities',

    component: FacilityView,

    meta: {
      requiresAuth: true,

      roles: [

        'super_admin',

        'campus_admin',

        'calendar_admin'
      ]
    }
  },

  {
    path: '/approvals',

    name: 'approvals',

    component: ApprovalView,

    meta: {
      requiresAuth: true,

      roles: [

        'campus_admin',

        'staff'
      ]
    }
  },

  {
    path: '/anomalies',

    name: 'anomalies',

    component: AnomalyView,

    meta: {
      requiresAuth: true,

      roles: [

        'campus_admin',

        'staff'
      ]
    }
  },

  {
    path: '/schedules',

    name: 'schedules',

    component: ScheduleView,

    meta: {
      requiresAuth: true,

      roles: [

        'super_admin',

        'campus_admin',

        'calendar_admin'
      ]
    }
  }
]

const router = createRouter({

  history: createWebHistory(),

  routes
})

router.beforeEach((to) => {

  const authStore =
    useAuthStore()

  const token =
    localStorage.getItem('token')

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

  const allowedRoles =

    to.meta.roles as
      string[] | undefined

  if (

    allowedRoles &&

    !allowedRoles.includes(

      authStore.user?.role || ''
    )
  ) {
    return {

      name: 'dashboard'
    }
  }
})

export default router