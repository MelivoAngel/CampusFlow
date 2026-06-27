<script setup lang="ts">
import { computed } from 'vue'
import { useAuthStore } from '../../../stores/authStore'

import AdminDashboardView from './AdminDashboardView.vue'
import CalendarDashboardView from './CalendarDashboardView.vue'

const authStore =
  useAuthStore()

const isCalendarAdmin = computed(() => {

  return (
    authStore.user?.role ===
    'calendar_admin'
  )
})
</script>

<template>

  <CalendarDashboardView v-if="isCalendarAdmin"/>

  <AdminDashboardView v-else/>

</template>