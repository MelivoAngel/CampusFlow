<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'

import {
  IonPage,
  IonContent,
  IonInput,
  IonButton
} from '@ionic/vue'

import { loginRequest } from '../services/authApi'
import { useAuthStore } from '../../../stores/authStore'

const authStore =
  useAuthStore()

const router =
  useRouter()

const email =
  ref('')

const password =
  ref('')

const loading =
  ref(false)

const handleLogin = async () => {

  loading.value = true

  try {
    const response =
      await loginRequest(

        email.value,

        password.value
      )

    const user =
      response.data.data.user

    if (
      user.role !==
      'field_technician'
    ) {
      loading.value = false
      return
    }

    authStore.setAuth(

      response.data.data.token,

      user
    )
    

    router.push(
      '/resources'
    )
  }

  catch (error) {
    console.log(error)
  }

  loading.value = false
}

const handleEmail = (
  event: any
) => {
  email.value =
    event.detail.value
}

const handlePassword = (
  event: any
) => {
  password.value =
    event.detail.value
}
</script>

<template>

  <ion-page>

    <ion-content class="ion-padding">

      <div class="login-container">

        <h2>
          CampusFLOW
        </h2>

        <ion-input
          placeholder="Email"
          @ionInput="handleEmail"
        />

        <ion-input
          type="password"
          placeholder="Password"
          @ionInput="handlePassword"
        />

        <ion-button
          expand="block"
          @click="handleLogin"
        >
          {{ loading ? 'Loading...' : 'Login' }}
        </ion-button>

      </div>

    </ion-content>

  </ion-page>

</template>

<style scoped>
.login-container {
  margin-top: 120px;
}
</style>