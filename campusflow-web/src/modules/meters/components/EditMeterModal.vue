<script setup lang="ts">
import { ref,watch } from 'vue'
import { PencilSquareIcon } from '@heroicons/vue/24/outline'

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

      description:
        description.value,

      is_active:
        isActive.value
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

  <div
    v-if="show"
    class="fixed inset-0 bg-black/40 flex items-center justify-center"
  >

    <div
      class="bg-white p-6 rounded-2xl w-[500px] shadow-xl"
    >

      <div
        class="flex justify-between items-center mb-5"
      >

        <div class="flex items-center gap-3">

          <div
            class="w-11 h-11 rounded-full bg-blue-50 flex items-center justify-center"
          >
            <PencilSquareIcon
              class="w-5 h-5 text-blue-600"
            />
          </div>

          <div>

            <h2 class="text-lg font-semibold">
              Edit Meter
            </h2>

            <p
              class="text-sm text-gray-500"
            >
              Update meter details
            </p>

          </div>

        </div>

        <button
          @click="emit('close')"
          class="text-gray-500 hover:text-black"
        >
          ✕
        </button>

      </div>

      <div
        v-if="meter"
        class="mb-5 p-3 rounded-lg bg-gray-50 border"
      >

        <p
          class="text-xs text-gray-500 mb-1"
        >
          Current Meter
        </p>

        <p class="font-medium">
          {{ meter.name }}
        </p>

        <p
          class="text-sm text-gray-500 font-mono"
        >
          {{ meter.meter_code }}
        </p>

      </div>

      <div class="space-y-4">

        <div>

          <label
            class="block text-sm font-medium mb-2"
          >
            Meter Name
          </label>

          <input
            v-model="name"
            :class="[
              'w-full px-3 py-3 rounded-lg border',
              errors.name
                ? 'border-red-500'
                : 'border-gray-300'
            ]"
          >

        </div>

        <div>

          <label
            class="block text-sm font-medium mb-2"
          >
            Description
          </label>

          <textarea
            v-model="description"
            rows="3"
            :class="[
              'w-full px-3 py-3 rounded-lg border',
              errors.description
                ? 'border-red-500'
                : 'border-gray-300'
            ]"
          />

        </div>

        <div>

          <label
            class="block text-sm font-medium mb-2"
          >
            Meter Status
          </label>

          <select
            v-model="isActive"
            class="w-full px-3 py-3 rounded-lg border border-gray-300"
          >

            <option :value="true">
              Active
            </option>

            <option :value="false">
              Inactive
            </option>

          </select>

        </div>

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

      </div>

      <div
        class="flex justify-end gap-3 mt-6 pt-4 border-t"
      >

        <button
          @click="emit('close')"
          class="px-4 py-2 text-gray-600"
        >
          Cancel
        </button>

        <button
          @click="handleUpdate"
          class="bg-blue-600 text-white px-5 py-2 rounded-lg"
        >
          Save Changes
        </button>

      </div>

    </div>

  </div>

</template>