<script setup lang="ts">
import { useRouter } from 'vue-router'

import {
  IonPage,
  IonContent,
  IonCard,
  IonCardHeader,
  IonCardTitle
} from '@ionic/vue'

const router =
  useRouter()

const goToMeters = (
  resource: string
) => {

  router.push(

    `/meters/${resource}`
  )
}
</script>

<template>

  <ion-page>

    <ion-content class="ion-padding">

      <h2>
        Select Resource
      </h2>

      <ion-card
        @click="goToMeters('water')"
      >

        <ion-card-header>

          <ion-card-title>
            Water
          </ion-card-title>

        </ion-card-header>

      </ion-card>

      <ion-card
        @click="goToMeters('electricity')"
      >

        <ion-card-header>

          <ion-card-title>
            Electricity
          </ion-card-title>

        </ion-card-header>

      </ion-card>

      <ion-card
        @click="goToMeters('waste')"
      >

        <ion-card-header>

          <ion-card-title>
            Waste
          </ion-card-title>

        </ion-card-header>

      </ion-card>

    </ion-content>

  </ion-page>

</template>