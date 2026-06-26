<script setup lang="ts">
import { ref,onMounted } from 'vue'
import { useRouter } from 'vue-router'

import {
  IonPage,
  IonContent,
  IonCard,
  IonCardHeader,
  IonCardTitle,
  IonButton
} from '@ionic/vue'

import {
  getPendingReadingsRequest
} from '../services/dashboardApi'

import {
  useAuthStore
} from '../../../stores/authStore'

const router =
  useRouter()

const authStore =
  useAuthStore()

const readings =
  ref<any[]>([])

const loading =
  ref(false)

const fetchPending = async () => {

  loading.value = true

  try {
    const response =
      await getPendingReadingsRequest()

    readings.value =
      response.data.data
  }

  catch (error) {
    console.log(error)
  }

  loading.value = false
}

const handleResources = () => {

  router.push(

    '/resources'
  )
}

const handleEdit = (
  reading: any
) => {

  router.push({

    path:

      `/edit-reading/${reading.id}`,

    query: {

      name:
        reading.meter.name,

      value:
        reading.current_reading
    }
  })
}

const handleLogout = () => {

  authStore.logout()

  router.push(

    '/login'
  )
}

onMounted(() => {
  fetchPending()
})
</script>

<template>

  <ion-page>

    <ion-content class="ion-padding">

      <h2>

        Pending Submissions

      </h2>

      <p v-if="loading">

        Loading...

      </p>

      <p v-else-if="!readings.length">

        No pending submissions

      </p>

      <ion-card
        v-for="reading in readings"
        :key="reading.id"
      >

        <ion-card-header>

          <ion-card-title>

            {{ reading.meter.name }}

          </ion-card-title>

          <p>

            Reading:
            {{ reading.current_reading }}

          </p>

          <p>

            {{ reading.recorded_date }}

          </p>

          <ion-button
            @click="handleEdit(reading)"
          >

            Edit

          </ion-button>

        </ion-card-header>

      </ion-card>

      <ion-button
        expand="block"
        @click="handleResources"
      >

        Submit New Reading

      </ion-button>

      <ion-button
        expand="block"
        @click="handleLogout"
      >

        Logout

      </ion-button>

    </ion-content>

  </ion-page>

</template>