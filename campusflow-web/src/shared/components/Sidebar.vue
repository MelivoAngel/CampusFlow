<script setup lang="ts">

import { computed } from 'vue'

import { useAuthStore } from '../../stores/authStore'

import { menu } from '../sidebar/menu'

const authStore =
  useAuthStore()

const items = computed(

  () => {

    if (!authStore.user) {

      return []
    }

    return menu[
      authStore.user.role as keyof typeof menu
    ]
  }
)

</script>

<template>

  <div>

    <h2>

      CampusFLOW

    </h2>

    <ul>

      <li

        v-for="item in items"

        :key="item"
      >

        {{ item }}

      </li>

    </ul>

  </div>

</template>