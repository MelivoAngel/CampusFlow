<script setup lang="ts">
import { ref,onMounted,watch } from 'vue'
import { UserPlusIcon } from '@heroicons/vue/24/outline'

import {
  getUsersRequest
} from '../../users/services/userApi'

import {
  assignMeterRequest
} from '../services/meterApi'

import type { Meter } from '../../../types/meter'
import type { User } from '../../../types/user'

const props = defineProps<{
  show: boolean
  meter: Meter | null
}>()

const emit = defineEmits([
  'close',
  'updated'
])

const technicians =
  ref<User[]>([])

const technicianId =
  ref<number | null>(null)

const loading =
  ref(false)

const fetchTechnicians = async () => {

  try {
    const response =
      await getUsersRequest()

    technicians.value =
      response.data.data.filter(

        (user: User) =>

          user.role ===
          'field_technician'
      )
  }

  catch (error) {
    console.log(error)
  }
}

const handleAssign = async () => {

  if (
    ! props.meter ||
    ! technicianId.value
  ) {
    return
  }

  loading.value = true

  try {
    await assignMeterRequest(

      props.meter.id,

      technicianId.value
    )

    emit('updated')

    emit('close')
  }

  catch (error) {
    console.log(error)
  }

  loading.value = false
}

watch(() => props.show,() => {

  technicianId.value =
    props.meter?.assignment?.technician?.id ||
    null
})

onMounted(() => {
  fetchTechnicians()
})
</script>

<template>

  <div
    v-if="show"
    class="fixed inset-0 bg-black/40 flex items-center justify-center"
  >

    <div class="bg-white p-6 rounded-2xl w-[430px] shadow-xl">

      <div class="flex items-center gap-3 mb-5">

        <div
          class="w-11 h-11 rounded-full bg-red-50 flex items-center justify-center"
        >
          <UserPlusIcon
            class="w-5 h-5 text-red-600"
          />
        </div>

        <div>

          <h2 class="text-lg font-semibold">
            Assign Technician
          </h2>

          <p class="text-sm text-gray-500">
            Assign a technician to this meter
          </p>

        </div>

      </div>

      <div
        v-if="meter"
        class="mb-5 p-3 rounded-lg bg-gray-50 border"
      >

        <p class="text-xs text-gray-500 mb-1">
          Selected Meter
        </p>

        <p class="font-medium">
          {{ meter.name }}
        </p>

        <p class="text-sm text-gray-500 font-mono">
          {{ meter.meter_code }}
        </p>

      </div>

      <div class="mb-6">

        <label
          class="block text-sm font-medium mb-2"
        >
          Technician
        </label>

        <select
          v-model="technicianId"
          class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-600"
        >

          <option :value="null">
            Select Technician
          </option>

          <option
            v-for="technician in technicians"
            :key="technician.id"
            :value="technician.id"
          >
            {{ technician.name }}
          </option>

        </select>

      </div>

      <div
        class="flex justify-end gap-3 pt-2 border-t"
      >

        <button
          @click="emit('close')"
          class="px-4 py-2 text-gray-600 hover:text-black"
        >
          Cancel
        </button>

        <button
          @click="handleAssign"
          class="bg-red-600 text-white px-5 py-2 rounded-lg"
        >
          {{
            loading
              ? 'Assigning...'
              : 'Assign'
          }}
        </button>

      </div>

    </div>

  </div>

</template>