<script setup lang="ts">
import { ref,watch } from 'vue'

import {
  getAvailableMetersRequest,
  assignBuildingMetersRequest
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

    'updated'
  ])

const meters =
  ref<any[]>([])

const selectedMeters =
  ref<number[]>([])

const loading =
  ref(false)

const fetchMeters = async () => {

  if (
    !props.building
  ) {
    return
  }

  try {

    const response =
      await getAvailableMetersRequest(

        props.building.id
      )

    meters.value =
      response.data.data
  }

  catch (error) {
    console.log(error)
  }
}

const handleAssign = async () => {

  if (
    !props.building
  ) {
    return
  }

  loading.value =
    true

  try {

    await assignBuildingMetersRequest(

      props.building.id,

      selectedMeters.value
    )

    emit('updated')

    emit('close')
  }

  catch (error) {
    console.log(error)
  }

  loading.value =
    false
}

watch(

  () => props.show,

  async (show) => {

    if (
      !show ||
      !props.building
    ) {
      return
    }

    await fetchMeters()

    selectedMeters.value =

      props.building.meters.map(
        meter => meter.id
      )
  }
)
</script>

<template>

  <div
    v-if="show"
    class="fixed inset-0 bg-black/40 flex items-center justify-center"
  >

    <div
      class="bg-white p-6 rounded-xl w-[550px]"
    >

      <div class="mb-5">

        <h2 class="text-xl font-semibold">
          Assign Meters
        </h2>

        <p class="text-sm text-gray-500">

          {{ props.building?.name }}

        </p>

      </div>

      <div
        class="max-h-72 overflow-y-auto border rounded-lg p-3 mb-5"
      >

        <div
          v-if="!meters.length"
          class="text-sm text-gray-500"
        >
          No available meters
        </div>

        <label
          v-for="meter in meters"
          :key="meter.id"
          class="flex items-center justify-between py-3 border-b last:border-b-0 cursor-pointer"
        >

          <div>

            <p class="font-medium">
              {{ meter.name }}
            </p>

            <p
              class="text-xs text-gray-500"
            >
              {{ meter.meter_code }}
            </p>

          </div>

          <input
            v-model="selectedMeters"
            :value="meter.id"
            type="checkbox"
            class="w-4 h-4"
          >

        </label>

      </div>

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
          @click="handleAssign"
          class="bg-red-600 text-white px-4 py-2 rounded-md"
        >
          {{
            loading
              ? 'Saving...'
              : 'Save Assignment'
          }}
        </button>

      </div>

    </div>

  </div>

</template>