<script setup lang="ts">
import { computed } from 'vue'

import { useAuthStore } from '../../stores/authStore'
import { menu } from '../sidebar/menu'

import { formatLabel } from '../../utils/formatters'

const authStore =
  useAuthStore()

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

  <aside class="w-56 bg-emerald-900 text-white min-h-screen px-5 py-6">

    <h2 class="text-2xl font-bold">
      CampusFLOW
    </h2>

    <div class="mt-1 mb-8 text-sm text-gray-300">

      {{ formatLabel(

        authStore.user?.role || ''

      ) }}

    </div>

    <div
      v-for="group in items"
      :key="group.section"
      class="mb-7"
    >

      <p
        v-if="group.section"
        class="text-[11px] uppercase text-gray-400 mb-3 mt-2 tracking-widest"
      >
        {{ group.section }}
      </p>

      <ul class="space-y-1">

        <li
          v-for="item in group.items"
          :key="item.name"
        >

          <router-link
            :to="item.path"
            v-slot="{ isActive }"
          >

            <div
              :class="[

                'pl-3 pr-2 py-2 rounded-md cursor-pointer transition-all duration-200 border-l-4 text-sm',

                isActive

                  ? 'bg-emerald-700 border-white font-medium'

                  : 'border-transparent hover:bg-emerald-800/70'
              ]"
            >
              {{ item.name }}
            </div>

          </router-link>

        </li>

      </ul>

    </div>

  </aside>

</template>