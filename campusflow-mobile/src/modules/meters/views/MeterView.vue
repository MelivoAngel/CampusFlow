<script setup lang="ts">

import { ref,onMounted } from 'vue'
import { useRoute,useRouter } from 'vue-router'

import {
  IonPage,
  IonContent,
  IonCard,
  IonCardHeader,
  IonCardTitle
} from '@ionic/vue'

import {
  getMetersRequest
} from '../services/meterApi'

import type { Meter } from '../types/meter'

const route =
  useRoute()

const router =
  useRouter()

const meters =
  ref<Meter[]>([])

const loading =
  ref(false)

const resource =
  route.params.resource

const fetchMeters = async () => {

  loading.value = true

  try {
    const response =
      await getMetersRequest()

    meters.value =
      response.data.data.filter(

        (meter: Meter) =>

          meter.resource_type ===
          resource
      )
  }

  catch (error) {
    console.log(error)
  }

  loading.value = false
}

const handleSelectMeter = (
  meterId: number
) => {

  router.push(

    `/submit-reading/${meterId}`
  )
}

onMounted(() => {
  fetchMeters()
})

</script>

<template>

  <ion-page>

    <ion-content class="ion-padding">

      <h2>

        {{ resource }}

      </h2>

      <p v-if="loading">

        Loading...

      </p>

      <p v-else-if="!meters.length">

        No assigned meters

      </p>

      <ion-card :button="true" v-for="meter in meters" :key="meter.id" @click="handleSelectMeter(meter.id)">

        <ion-card-header>

          <ion-card-title>

            {{ meter.name }}

          </ion-card-title>

        </ion-card-header>

      </ion-card>

    </ion-content>

  </ion-page>

</template>