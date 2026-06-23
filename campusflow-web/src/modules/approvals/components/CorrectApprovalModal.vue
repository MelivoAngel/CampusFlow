<script setup lang="ts">
import { ref,watch } from 'vue'

import { correctRequest } from '../services/approvalApi'
import type { Approval } from '../types/approval'

const props = defineProps<{
  show: boolean
  approval: Approval | null
}>()

const emit = defineEmits([
  'close',
  'updated'
])

const currentReading = ref('')
const errorMessage = ref('')
const successMessage = ref('')

watch(() => props.approval,(approval) => {
  if (!approval) return
  currentReading.value = approval.current_reading.toString()
})

const handleCorrect = async () => {
  errorMessage.value = ''
  successMessage.value = ''

  try {
    await correctRequest(
      props.approval!.id,
      Number(currentReading.value)
    )

    successMessage.value = 'Reading corrected successfully'

    emit('updated')

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
          Correct Reading
        </h2>

        <button @click="emit('close')">
          X
        </button>

      </div>

      <div class="space-y-4">

        <input
          v-model="currentReading"
          type="number"
          placeholder="Enter corrected reading"
          class="w-full px-3 py-2 rounded-md border border-gray-300"
        >

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
          @click="handleCorrect"
          class="w-full bg-orange-600 text-white py-2 rounded-md"
        >
          Submit Correction
        </button>

      </div>

    </div>

  </div>

</template>