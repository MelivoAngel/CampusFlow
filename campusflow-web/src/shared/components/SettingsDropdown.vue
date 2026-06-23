<script setup lang="ts">
import { ref,onMounted,onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { Settings } from '@lucide/vue'

import { useAuthStore } from '../../stores/authStore'
import { logoutRequest } from '../../modules/auth/services/authApi'

const authStore =
  useAuthStore()

const router =
  useRouter()

const show =
  ref(false)

const dropdownRef =
  ref<HTMLElement | null>(null)

const toggleMenu = () => {

  show.value =
    !show.value
}

const handleOutsideClick = (
  event: MouseEvent
) => {

  if (

    dropdownRef.value &&

    !dropdownRef.value.contains(

      event.target as Node
    )
  ) {
    show.value =
      false
  }
}

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

onMounted(() => {

  document.addEventListener(

    'click',

    handleOutsideClick
  )
})

onUnmounted(() => {

  document.removeEventListener(

    'click',

    handleOutsideClick
  )
})
</script>

<template>

  <div
    ref="dropdownRef"
    class="relative"
  >

    <button
      @click="toggleMenu"
      class="text-gray-500 hover:text-gray-800 transition"
    >

      <Settings :size="18" />

    </button>

    <div
      v-if="show"
      class="absolute right-0 mt-3 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
    >

      <button
        class="block w-full text-left px-4 py-3 text-sm hover:bg-gray-50"
      >
        Profile
      </button>

      <hr class="border-gray-200">

      <button
        @click="handleLogout"
        class="block w-full text-left px-4 py-3 text-sm text-red-500 hover:bg-gray-50"
      >
        Logout
      </button>

    </div>

  </div>

</template>