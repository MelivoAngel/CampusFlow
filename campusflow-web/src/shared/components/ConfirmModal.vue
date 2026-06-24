<script setup lang="ts">
defineProps<{
  show: boolean
  title: string
  message: string
}>()

const emit = defineEmits([
  'confirm',
  'close'
])
</script>

<template>

  <div
    v-if="show"
    class="fixed inset-0 bg-black/30 flex items-center justify-center"
  >

    <div class="bg-white p-6 rounded-xl w-[420px]">

      <h2 class="text-lg font-semibold mb-3">
        {{ title }}
      </h2>

      <p class="text-gray-600 mb-6">
        {{ message }}
      </p>

      <div class="flex justify-end gap-3">

        <button
          @click="emit('close')"
          class="px-4 py-2 border rounded-md"
        >
          Cancel
        </button>

        <button
          @click="emit('confirm')"
          class="bg-red-600 text-white px-4 py-2 rounded-md"
        >
          Confirm
        </button>

      </div>

    </div>

  </div>

</template>