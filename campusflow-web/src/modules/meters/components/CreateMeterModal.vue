<script setup lang="ts">
import { ref,computed,onMounted } from 'vue'
import { useAuthStore } from '../../../stores/authStore'

import { createMeterRequest } from '../services/meterApi'
import { getCampusesRequest } from '../../users/services/userApi'

import type { Campus } from '../../../types/campus'

import type {
  CreateMeterPayload
} from '../../../types/requests'

import { resourceTypes } from '../../../constants/resourceTypes'
import { formatLabel } from '../../../utils/formatters'

const props =
  defineProps<{
    show: boolean
}>()

const emit =
  defineEmits([
    'close',
    'created'
  ])

const authStore =
  useAuthStore()

const name =
  ref('')

const meterCode =
  ref('')

const resourceType =
  ref('')

const description =
  ref('')

const campusId =
  ref('')

const campuses =
  ref<Campus[]>([])

const errors =
  ref<Record<string,string[]>>({})

const firstError =
  ref('')

const successMessage =
  ref('')

const showCampus = computed(() => {

  return (
    authStore.user?.role ===
    'super_admin'
  )
})

const fetchCampuses = async () => {

  if (!showCampus.value) {
    return
  }

  try {
    const response =
      await getCampusesRequest()

    campuses.value =
      response.data.data
  }

  catch (error) {
    console.log(error)
  }
}

const resetForm = () => {

  name.value = ''
  meterCode.value = ''
  resourceType.value = ''
  description.value = ''
  campusId.value = ''

  errors.value = {}
  firstError.value = ''
  successMessage.value = ''
}

const handleCreate = async () => {

  errors.value = {}
  firstError.value = ''
  successMessage.value = ''

  try {

    const payload:
      CreateMeterPayload = {

      name: name.value,

      meter_code: meterCode.value,

      resource_type: resourceType.value,

      description: description.value
    }

    if (showCampus.value) {
      payload.campus_id =
        campusId.value
    }

    await createMeterRequest(
      payload
    )

    successMessage.value =
      'Meter created successfully'

    emit('created')

    setTimeout(() => {

      emit('close')

      resetForm()

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

onMounted(() => {

  fetchCampuses()
})
</script>

<template>

  <div v-if="show" class="fixed inset-0 bg-black/30 flex items-center justify-center">

    <div class="bg-white p-6 rounded-xl w-[500px]">

      <div class="flex justify-between mb-6">

        <h2 class="text-xl font-semibold">
          Add Meter
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

        <input
          v-model="meterCode"
          placeholder="Meter Code"
          :class="['w-full px-3 py-2 rounded-md border',errors.meter_code ? 'border-red-500 animate-shake' : 'border-gray-300']"
        >

        <select
          v-model="resourceType"
          :class="['w-full px-3 py-2 rounded-md border',errors.resource_type ? 'border-red-500 animate-shake' : 'border-gray-300']"
        >

          <option value="">
            Select Resource Type
          </option>

          <option
            v-for="type in resourceTypes"
            :key="type"
            :value="type"
          >
            {{ formatLabel(type) }}
          </option>

        </select>

        <select
          v-if="showCampus"
          v-model="campusId"
          :class="['w-full px-3 py-2 rounded-md border',errors.campus_id ? 'border-red-500 animate-shake' : 'border-gray-300']"
        >

          <option value="">
            Select Campus
          </option>

          <option
            v-for="campus in campuses"
            :key="campus.id"
            :value="campus.id"
          >
            {{ campus.name }}
          </option>

        </select>

        <textarea
          v-model="description"
          placeholder="Description"
          :class="['w-full px-3 py-2 rounded-md border',errors.description ? 'border-red-500 animate-shake' : 'border-gray-300']"
        />

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

        <button @click="handleCreate" class="w-full bg-emerald-700 text-white py-2 rounded-md">
          Create Meter
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