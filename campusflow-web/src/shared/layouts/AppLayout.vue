<script setup lang="ts">
import Sidebar from '../components/Sidebar.vue'
import SettingsDropdown from '../components/SettingsDropdown.vue'
import ThemeToggle from '../components/ThemeToggle.vue'

import { useAuthStore } from '../../stores/authStore'
import { useClock } from '../../composables/useClock'

const authStore =
  useAuthStore()

const {
  currentTime
} = useClock()
</script>

<template>

  <div class="flex min-h-screen bg-gray-100">

    <Sidebar />

    <div class="flex-1 flex flex-col">

      <header class="bg-white border-b px-6 py-3">

        <div class="flex items-center">

          <div class="text-sm text-gray-500">

            {{ currentTime }}

          </div>

          <div class="ml-auto mr-6 flex items-center gap-5">

            <ThemeToggle />

            <span class="text-sm font-medium text-gray-700">

              {{ authStore.user?.name }}

            </span>

            <SettingsDropdown />

          </div>

        </div>

      </header>

      <main class="p-6">

        <slot />

      </main>

    </div>

  </div>

</template>