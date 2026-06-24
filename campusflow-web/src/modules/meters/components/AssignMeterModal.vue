<script setup lang="ts">
import { ref,onMounted,watch } from 'vue'

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
    props.meter?.assignment?.technician.id ||
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

    <div class="bg-white p-6 rounded-xl w-96">

      <h2 class="text-lg font-semibold mb-4">
        Assign Technician
      </h2>

      <select
        v-model="technicianId"
        class="w-full border rounded-md p-2 mb-4"
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

      <div class="flex justify-end gap-3">

        <button
          @click="emit('close')"
          class="text-gray-600"
        >
          Cancel
        </button>

        <button
          @click="handleAssign"
          class="bg-emerald-700 text-white px-4 py-2 rounded-md"
        >
          {{ loading ? 'Assigning...' : 'Assign' }}
        </button>

      </div>

    </div>

  </div>

</template>