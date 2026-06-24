<script setup lang="ts">
import { ref } from 'vue'
import { useRoute } from 'vue-router'

import {
  IonPage,
  IonContent,
  IonInput,
  IonButton
} from '@ionic/vue'

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
        >
          Take Photo
        </ion-button>

        <ion-button
          expand="block"
          :disabled="!currentReading"
        >
          Submit Reading
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