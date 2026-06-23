<script setup lang="ts">
  import { ref } from 'vue'
  import { useRouter } from 'vue-router'
  import { loginRequest } from '../services/authApi'
  import { useAuthStore } from '../../../stores/authStore'
  import bsuBanner from '../assets/bsu_banner.jpg'
  import bsuLogo from '../assets/bsu_logo.png'

  const email = ref('')
  const password = ref('')
  const errorMessage = ref('')
  const router = useRouter()
  const authStore = useAuthStore()

  const handleLogin = async () => {
    try {
      const response = await loginRequest( email.value, password.value )
      const token = response.data.data.token
      const user = response.data.data.user

      if ( user.role === 'field_technician') {
        errorMessage.value = 'Access denied'
        return
      }

      authStore.setAuth( token, user )
      router.push({ name: 'dashboard' })
    }

    catch (error) {
      errorMessage.value = 'Invalid credentials'
      console.log(error)
    }
  }
</script>

<template>
  <div class="min-h-screen flex">

    <div class="w-1/2 bg-emerald-900 text-white flex flex-col justify-center items-center px-16">

      <img :src="bsuBanner" alt="BSU Banner" class="w-96 mb-10">
      
      <h1 class="text-5xl font-bold mb-4"> CampusFLOW </h1>

      <p class="text-center text-lg max-w-lg leading-8 mb-10">
        Smart campus sustainability and resource monitoring platform for Batangas State University.
      </p>

      <img :src="bsuLogo" alt="BSU Logo" class="w-24" >

    </div>

    <div class="w-1/2 bg-gray-100 flex items-center justify-center">

      <div class="bg-white p-10 rounded-xl shadow-md w-[420px]">

        <h2 class="text-2xl font-semibold mb-8"> 
          Welcome Back 
        </h2>

        <div class="mb-4">
          <input v-model="email" placeholder="Email" class="w-full border rounded-md px-4 py-3">
        </div>

        <div class="mb-4">
          <input v-model="password" type="password" placeholder="Password" class="w-full border rounded-md px-4 py-3">
        </div>

        <div v-if="errorMessage" class="text-red-500 text-sm mb-4">
          {{ errorMessage }}
        </div>

        <button @click="handleLogin" class="w-full bg-emerald-700 text-white py-3 rounded-md hover:bg-emerald-800">
          Login
        </button>

      </div>

    </div>

  </div>
</template>