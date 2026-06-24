<script setup lang="ts">
import { ref } from 'vue'

import { resolveAnomalyRequest } from '../services/anomalyApi'
import type { Anomaly } from '../types/anomaly'

const props = defineProps<{
  show: boolean
  anomaly: Anomaly | null
}>()

const emit = defineEmits([
  'close',
  'updated'
])

const resolutionNote = ref('')
const errorMessage = ref('')
const successMessage = ref('')

const handleResolve = async () => {
  errorMessage.value = ''
  successMessage.value = ''

  try {
    await resolveAnomalyRequest(
      props.anomaly!.id,
      resolutionNote.value
    )

    successMessage.value =
      'Anomaly resolved successfully'

    emit('updated')

    resolutionNote.value = ''

    setTimeout(() => {
      emit('close')
    },1000)
  }

  catch (error: any) {
    errorMessage.value =
      error.response?.data?.message ||
      'Something went wrong'
  }
}
</script>

<template>

  <div
    v-if="show"
    class="fixed inset-0 bg-black/30 flex items-center justify-center"
  >

    <div class="bg-white p-6 rounded-xl w-[500px]">

      <div class="flex justify-between mb-6">

        <h2 class="text-xl font-semibold">
          Resolve Anomaly
        </h2>

        <button @click="emit('close')">
          X
        </button>

      </div>

      <div class="space-y-4">

        <textarea
          v-model="resolutionNote"
          placeholder="Enter resolution note"
          class="w-full px-3 py-2 rounded-md border border-gray-300"
        />

        <p
          v-if="successMessage"
          class="text-green-600 text-sm"
        >
          {{ successMessage }}
        </p>

        <p
          v-if="errorMessage"
          class="text-red-600 text-sm"
        >
          {{ errorMessage }}
        </p>

        <button
          @click="handleResolve"
          class="w-full bg-blue-600 text-white py-2 rounded-md"
        >
          Resolve
        </button>

      </div>

    </div>

  </div>

</template>