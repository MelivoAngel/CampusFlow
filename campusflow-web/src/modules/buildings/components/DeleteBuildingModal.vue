<script setup lang="ts">
import { ref } from 'vue'

import {
  deleteBuildingRequest
} from '../services/buildingApi'

import type {
  Building
} from '../../../types/building'

const props =
  defineProps<{

    show: boolean

    building: Building | null
}>()

const emit =
  defineEmits([

    'close',

    'deleted'
  ])

const loading =
  ref(false)

const error =
  ref('')

const requiresConfirmation =
  ref(false)

const assignedMeters =
  ref<any[]>([])

const confirmed =
  ref(false)

const handleDelete = async () => {

  if (
    !props.building
  ) {
    return
  }

  loading.value =
    true

  error.value =
    ''

  try {

    await deleteBuildingRequest(

      props.building.id
    )

    emit('deleted')

    emit('close')
  }

  catch (error: any) {

    if (

      error.response?.data
        ?.requires_confirmation
    ) {

      requiresConfirmation.value =
        true

      assignedMeters.value =

        error.response
          .data
          .meters

      loading.value =
        false

      return
    }

    error.value =

      error.response?.data?.message ||

      'Something went wrong'
  }

  loading.value =
    false
}

const handleForceDelete =
  async () => {

    if (
      !props.building
    ) {
      return
    }

    loading.value =
      true

    try {

      await deleteBuildingRequest(

        props.building.id,

        true
      )

      emit('deleted')

      emit('close')
    }

    catch (error: any) {

      error.value =

        error.response?.data?.message ||

        'Something went wrong'
    }

    loading.value =
      false
}
</script>

<template>

  <div
    v-if="show"
    class="fixed inset-0 bg-black/40 flex items-center justify-center"
  >

    <div
      class="bg-white rounded-xl p-6 w-[500px]"
    >

      <div class="mb-4">

        <h2 class="text-xl font-semibold">
          Delete Building
        </h2>

      </div>

      <div
        v-if="!requiresConfirmation"
        class="mb-6"
      >

        <p class="text-gray-700">

          Are you sure you want to delete

          <span class="font-semibold">
            {{ props.building?.name }}
          </span>

          ?

        </p>

        <p
          class="text-sm text-red-500 mt-2"
        >
          This action cannot be undone.
        </p>

      </div>

      <div
        v-if="requiresConfirmation"
        class="bg-red-50 p-4 rounded-lg mb-6"
      >

        <p
          class="text-red-700 font-medium mb-3"
        >
          This building has assigned meters.
        </p>

        <p
          class="text-sm text-gray-700 mb-3"
        >
          These assignments will be removed.
        </p>

        <div
          class="max-h-32 overflow-y-auto mb-4 border rounded-md bg-white p-2"
        >

          <div v-for="meter in assignedMeters" :key="meter.id" class="flex justify-between text-sm py-2 border-b">
            <span>
              {{ meter.name }}
            </span>

            <span class="font-mono text-gray-500">
              {{ meter.meter_code }}
            </span>
          </div>

        </div>
        <label class="flex gap-2 items-start">
          <input v-model="confirmed" type="checkbox">
          <span class="text-sm">
            I understand the assigned meters will no longer belong to this building after deletion.
          </span>
        </label>
      </div>

      <p
        v-if="error"
        class="text-sm text-red-600 mb-4 bg-red-50 p-3 rounded-md"
      >
        {{ error }}
      </p>

      <div
        class="flex justify-end gap-3"
      >

        <button
          @click="emit('close')"
          class="text-gray-600"
        >
          Cancel
        </button>

        <button
          v-if="!requiresConfirmation"
          @click="handleDelete"
          class="bg-red-600 text-white px-4 py-2 rounded-md"
        >
          {{

            loading

              ? 'Deleting...'

              : 'Delete Building'
          }}
        </button>

        <button
          v-if="requiresConfirmation"
          :disabled="!confirmed"
          @click="handleForceDelete"
          class="bg-red-700 text-white px-4 py-2 rounded-md disabled:opacity-50"
        >
          Confirm Delete
        </button>

      </div>

    </div>

  </div>

</template>