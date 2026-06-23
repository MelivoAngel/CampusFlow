<script setup lang="ts">
import { ref,watch } from 'vue'

import { updateMeterRequest } from '../services/meterApi'

import type { Meter } from '../../../types/meter'

import type {
  UpdateMeterPayload
} from '../../../types/requests'

const props =
  defineProps<{

    show: boolean

    meter: Meter | null
}>()

const emit =
  defineEmits([
    'close',
    'updated'
  ])

const name =
  ref('')

const description =
  ref('')

const isActive =
  ref(true)

const errors =
  ref<Record<string,string[]>>({})

const firstError =
  ref('')

const successMessage =
  ref('')

watch(

  () => props.meter,

  (meter) => {

    if (!meter) {
      return
    }

    name.value =
      meter.name

    description.value =
      meter.description || ''

    isActive.value =
      meter.is_active
  },

  { immediate: true }
)

const handleUpdate = async () => {

  errors.value = {}
  firstError.value = ''
  successMessage.value = ''

  try {

    const payload:
      UpdateMeterPayload = {

      name: name.value,

      description: description.value,

      is_active: isActive.value
    }

    await updateMeterRequest(

      props.meter!.id,

      payload
    )

    successMessage.value =
      'Meter updated successfully'

    emit('updated')

    setTimeout(() => {

      emit('close')

    },1000)
  }

  catch (error: any) {

    const backendErrors:
      Record<string,string[]> =

      error.response?.data?.errors

    if (backendErrors) {

      errors.value =
        backendErrors

      firstError.value =
        Object.values(
          backendErrors
        )[0][0]
    }

    else {

      firstError.value =
        error.response?.data?.message ||
        'Something went wrong'
    }
  }
}
</script>

<template>

  <div v-if="show" class="fixed inset-0 bg-black/30 flex items-center justify-center">

    <div class="bg-white p-6 rounded-xl w-[500px]">

      <div class="flex justify-between mb-6">

        <h2 class="text-xl font-semibold">
          Edit Meter
        </h2>

        <button @click="emit('close')">
          X
        </button>

      </div>

      <div class="space-y-4">

        <input
          v-model="name"
          placeholder="Meter Name"
          :class="['w-full px-3 py-2 rounded-md border',errors.name ? 'border-red-500 animate-shake' : 'border-gray-300']"
        >

        <textarea
          v-model="description"
          placeholder="Description"
          :class="['w-full px-3 py-2 rounded-md border',errors.description ? 'border-red-500 animate-shake' : 'border-gray-300']"
        />

        <select
          v-model="isActive"
          class="w-full px-3 py-2 rounded-md border border-gray-300"
        >
          <option :value="true">
            Active
          </option>

          <option :value="false">
            Inactive
          </option>
        </select>

        <p
          v-if="successMessage"
          class="text-green-600 text-sm"
        >
          {{ successMessage }}
        </p>

        <p
          v-if="firstError"
          class="text-red-600 text-sm"
        >
          {{ firstError }}
        </p>

        <button @click="handleUpdate" class="w-full bg-blue-600 text-white py-2 rounded-md">
          Save Changes
        </button>

      </div>

    </div>

  </div>

</template>

<style scoped>
@keyframes shake {

  0% { transform: translateX(0); }

  25% { transform: translateX(-4px); }

  50% { transform: translateX(4px); }

  75% { transform: translateX(-4px); }

  100% { transform: translateX(0); }
}

.animate-shake {
  animation: shake 0.3s;
}
</style>