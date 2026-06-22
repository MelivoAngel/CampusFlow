<script setup lang="ts">

import { ref } from 'vue'
import { useRouter } from 'vue-router'

import { loginRequest } from '../services/authApi'
import { useAuthStore } from '../../../stores/authStore'

const email = ref('')

const password = ref('')

const router = useRouter()

const authStore = useAuthStore()

const handleLogin = async () => {

  try {

    const response = await loginRequest(

      email.value,

      password.value
    )

    const token =
      response.data.data.token

    const user =
      response.data.data.user

    authStore.setAuth(

      token,

      user
    )

    router.push(
      '/dashboard'
    )

  }

  catch (error) {

    console.log(error)
  }
}

</script>

<template>

  <div>

    <h1>CampusFLOW Login</h1>

    <input
      v-model="email"
      placeholder="Email"
    >

    <input
      v-model="password"
      placeholder="Password"
      type="password"
    >

    <button
      @click="handleLogin"
    >
      Login
    </button>

  </div>

</template>