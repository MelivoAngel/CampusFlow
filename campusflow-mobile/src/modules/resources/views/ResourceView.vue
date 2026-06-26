<script setup lang="ts">
import { useRouter } from 'vue-router'

import {
  IonPage,
  IonHeader,
  IonToolbar,
  IonButtons,
  IonBackButton,
  IonTitle,
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

    <ion-header>

      <ion-toolbar>

        <ion-buttons slot="start">

          <ion-back-button defaultHref="/dashboard" />

        </ion-buttons>

        <ion-title>

          Resources

        </ion-title>

      </ion-toolbar>

    </ion-header>

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