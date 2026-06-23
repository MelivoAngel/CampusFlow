<script setup lang="ts">
import { computed } from 'vue'
import { useAuthStore } from '../../stores/authStore'
import { menu } from '../sidebar/menu'

const authStore = useAuthStore()

const items = computed(() => {

  if (!authStore.user) {
    return []
  }

  return menu[
    authStore.user.role as keyof typeof menu
  ]
})
</script>

<template>
  <aside class="w-64 bg-emerald-900 text-white min-h-screen p-6">

    <h2 class="text-2xl font-bold">
      CampusFLOW
    </h2>

    <div class="mb-8 text-sm text-gray-300">
      {{ authStore.user?.role }}
    </div>

    <ul class="space-y-3">

      <router-link
        v-for="item in items"
        :key="item.name"
        :to="item.path"
      >

        <li class="cursor-pointer hover:bg-emerald-700 px-3 py-2 rounded-md">
          {{ item.name }}
        </li>

      </router-link>

    </ul>

  </aside>
</template>