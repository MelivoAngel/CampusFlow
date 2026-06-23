<script setup lang="ts">
import Sidebar from '../components/Sidebar.vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/authStore'
import { logoutRequest } from '../../modules/auth/services/authApi'

const authStore = useAuthStore()

const router = useRouter()

const handleLogout = async () => {
  try {
    await logoutRequest()
  }

  catch (error) {
    console.log(error)
  }

  authStore.logout()

  router.push('/')
}
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    
    <Sidebar />

    <div class="flex-1 flex flex-col">

      <header class="bg-white shadow-sm px-6 py-4">

        <div class="flex justify-between items-center">

          <h1 class="text-lg font-semibold">
            CampusFLOW
          </h1>

          <div class="flex items-center gap-4">

            <span class="text-sm font-medium">
              {{ authStore.user?.name }}
            </span>

            <button @click="handleLogout" class="bg-red-500 text-white px-3 py-1 rounded-md">
              Logout
            </button>

          </div>

        </div>

      </header>

      <main class="p-6">
        <slot />
      </main>

    </div>
  </div>
</template>