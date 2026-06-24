<script setup lang="ts">
import { ref,computed,onMounted } from 'vue'
import { useRoute } from 'vue-router'

import {
  IonPage,
  IonHeader,
  IonToolbar,
  IonButtons,
  IonBackButton,
  IonTitle,
  IonContent,
  IonInput,
  IonButton
} from '@ionic/vue'

import {
  Camera,
  CameraResultType
} from '@capacitor/camera'

import {
  Network
} from '@capacitor/network'

import {
  submitReadingRequest,
  updateReadingRequest
} from '../services/readingApi'

const route =
  useRoute()

const isEditMode =
  computed(() => {

    return route.path.includes(
      'edit-reading'
    )
  })

const meterId =
  Number(
    route.params.meterId || 0
  )

const readingId =
  Number(
    route.params.readingId || 0
  )

const meterName =
  String(
    route.query.name || ''
  )

const currentReading =
  ref(

    String(
      route.query.value || ''
    )
  )

const photo =
  ref<File | null>(
    null
  )

const loading =
  ref(false)

const title =
  computed(() => {

    return isEditMode.value

      ? 'Edit Reading'

      : 'Submit Reading'
  })

const buttonText =
  computed(() => {

    return isEditMode.value

      ? 'Update Reading'

      : 'Submit Reading'
  })

const checkNetwork = async () => {

  const status =
    await Network.getStatus()

  console.log(

    'connected:',

    status.connected
  )
}

const handlePhoto = async () => {

  try {
    const image =
      await Camera.getPhoto({

        quality: 90,

        resultType:
          CameraResultType.DataUrl
      })

    if (! image.dataUrl) {
      return
    }

    const response =
      await fetch(
        image.dataUrl
      )

    const blob =
      await response.blob()

    photo.value =
      new File(

        [blob],

        'reading.jpg',

        {
          type: 'image/jpeg'
        }
      )
  }

  catch (error) {
    console.log(error)
  }
}

const handleSubmit = async () => {

  if (! currentReading.value) {
    return
  }

  if (
    ! isEditMode.value &&
    ! photo.value
  ) {
    return
  }

  loading.value =
    true

  const formData =
    new FormData()

  formData.append(

    'current_reading',

    currentReading.value
  )

  if (photo.value) {

    formData.append(

      'photo',

      photo.value
    )
  }

  try {

    let response

    if (
      isEditMode.value
    ) {

      response =
        await updateReadingRequest(

          readingId,

          formData
        )
    }

    else {

      formData.append(

        'meter_id',

        String(
          meterId
        )
      )

      response =
        await submitReadingRequest(

          formData
        )
    }

    console.log(
      response.data
    )

    currentReading.value =
      ''

    photo.value =
      null
  }

  catch (error) {
    console.log(error)
  }

  loading.value =
    false
}

onMounted(() => {

  checkNetwork()
})
</script>

<template>

  <ion-page>

    <ion-header>

      <ion-toolbar>

        <ion-buttons slot="start">

          <ion-back-button defaultHref="/dashboard" />

        </ion-buttons>

        <ion-title>

          {{ title }}

        </ion-title>

      </ion-toolbar>

    </ion-header>

    <ion-content class="ion-padding">

      <div class="reading-container">

        <h2>

          {{ title }}

        </h2>

        <p>

          {{ meterName }}

        </p>

        <ion-input
          v-model="currentReading"
          type="number"
          placeholder="Current Reading"
        />

        <ion-button
          expand="block"
          @click="handlePhoto"
        >

          {{ photo ? 'Photo Ready' : 'Take Photo' }}

        </ion-button>

        <ion-button
          expand="block"
          @click="handleSubmit"
        >

          {{ loading ? 'Loading...' : buttonText }}

        </ion-button>

      </div>

    </ion-content>

  </ion-page>

</template>

<style scoped>
.reading-container {
  margin-top: 40px;
}
</style>