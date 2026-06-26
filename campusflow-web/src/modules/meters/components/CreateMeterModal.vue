<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { BoltIcon } from '@heroicons/vue/24/outline'
import { useAuthStore } from '../../../stores/authStore'

import { createMeterRequest } from '../services/meterApi'
import { getCampusesRequest } from '../../users/services/userApi'

import type { Campus } from '../../../types/campus'
import type { CreateMeterPayload } from '../../../types/requests'

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

      resource_type:
        resourceType.value,

      description:
        description.value
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
            class="w-11 h-11 rounded-full bg-emerald-50 flex items-center justify-center"
          >
            <BoltIcon
              class="w-5 h-5 text-emerald-700"
            />
          </div>

          <div>

            <h2 class="text-lg font-semibold">
              Add Meter
            </h2>

            <p class="text-sm text-gray-500">
              Create a new utility meter
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

      <div class="space-y-4">

        <div>

          <label
            class="block text-sm font-medium mb-2"
          >
            Resource Type
          </label>

          <select
            v-model="resourceType"
            :class="[
              'w-full px-3 py-3 rounded-lg border',
              errors.resource_type
                ? 'border-red-500'
                : 'border-gray-300'
            ]"
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

        </div>

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

        <div
          v-if="showCampus"
        >

          <label
            class="block text-sm font-medium mb-2"
          >
            Campus
          </label>

          <select
            v-model="campusId"
            :class="[
              'w-full px-3 py-3 rounded-lg border',
              errors.campus_id
                ? 'border-red-500'
                : 'border-gray-300'
            ]"
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

        <div
          class="p-3 rounded-lg bg-gray-50 border"
        >

          <p class="text-sm text-gray-500">
            Meter code will be generated automatically
          </p>

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
          @click="handleCreate"
          class="bg-emerald-700 text-white px-5 py-2 rounded-lg"
        >
          Create Meter
        </button>

      </div>

    </div>

  </div>

</template>