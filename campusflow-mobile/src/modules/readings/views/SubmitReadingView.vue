<script setup lang="ts">
import { ref } from 'vue'
import { useRoute } from 'vue-router'

import {
  IonPage,
  IonContent,
  IonInput,
  IonButton
} from '@ionic/vue'

import { Camera, CameraResultType} from '@capacitor/camera' 

import {
  submitReadingRequest
} from '../services/readingApi'

const route =
  useRoute()

const meterId =
  Number(
    route.params.meterId
  )

const currentReading =
  ref('')

const photo =
  ref<File | null>(null)

const loading =
  ref(false)

const handlePhoto = async () => {

  try {
    const image = await Camera.getPhoto({ quality: 90, resultType:CameraResultType.DataUrl})

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

  if (
    ! currentReading.value ||
    ! photo.value
  ) {
    return
  }

  loading.value = true

  const formData =
    new FormData()

  formData.append(
    'meter_id',
    String(meterId)
  )

  formData.append(
    'current_reading',
    currentReading.value
  )

  formData.append(
    'photo',
    photo.value
  )

  try {
    const response =
      await submitReadingRequest(
        formData
      )

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

  loading.value = false
}
</script>

<template>

  <ion-page>

    <ion-content class="ion-padding">

      <div class="reading-container">

        <h2>

          Submit Reading

        </h2>

        <p>

          Meter #
          {{ meterId }}

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
          :disabled="!currentReading || !photo"
          @click="handleSubmit"
        >
          {{ loading ? 'Submitting...' : 'Submit Reading' }}
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