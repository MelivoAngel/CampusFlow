<script setup lang="ts">
import { Sun,Moon } from '@lucide/vue'

import { useThemeStore } from '../../stores/themeStore'

const themeStore =
  useThemeStore()
</script>

<template>

  <button
    @click="themeStore.toggleTheme()"
    class="text-gray-500 hover:text-gray-800 transition"
  >

    <Moon
      v-if="!themeStore.darkMode"
      :size="18"
    />

    <Sun
      v-else
      :size="18"
    />

  </button>

</template>