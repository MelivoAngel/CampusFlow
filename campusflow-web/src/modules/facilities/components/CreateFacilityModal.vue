<script setup lang="ts">
import { ref,computed } from 'vue'
import { useAuthStore } from '../../../stores/authStore'

import {
  createFacilityRequest
} from '../services/facilityApi'

import {
  getCampusesRequest
} from '../../users/services/userApi'

import type {
  Campus
} from '../../../types/campus'

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

const campuses =
  ref<Campus[]>([])

const loading =
  ref(false)

const error =
  ref('')

const name =
  ref('')

const description =
  ref('')

const campusId =
  ref('')

const isSuperAdmin = computed(() => {

  return (
    authStore.user?.role ===
    'super_admin'
  )
})

const fetchCampuses = async () => {

  if (
    !isSuperAdmin.value
  ) {
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

fetchCampuses()

const handleSubmit = async () => {

  loading.value =
    true

  error.value =
    ''

  try {

    await createFacilityRequest({

      name:
        name.value,

      description:
        description.value,

      campus_id:

        isSuperAdmin.value

          ? campusId.value

          : null
    })

    emit('created')

    emit('close')

    name.value =
      ''

    description.value =
      ''

    campusId.value =
      ''
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
          Create Facility
        </h2>

      </div>

      <div class="space-y-4">

        <input
          v-model="name"
          placeholder="Facility Name"
          class="w-full border border-gray-300 rounded-md px-3 py-2"
        >

        <textarea
          v-model="description"
          placeholder="Description"
          class="w-full border border-gray-300 rounded-md px-3 py-2"
        />

        <select
          v-if="isSuperAdmin"
          v-model="campusId"
          class="w-full border border-gray-300 rounded-md px-3 py-2"
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

      <p
        v-if="error"
        class="text-sm text-red-600 mt-4"
      >
        {{ error }}
      </p>

      <div
        class="flex justify-end gap-3 mt-6"
      >

        <button
          @click="emit('close')"
          class="text-gray-600"
        >
          Cancel
        </button>

        <button
          @click="handleSubmit"
          class="bg-emerald-700 text-white px-4 py-2 rounded-md"
        >
          {{

            loading

              ? 'Creating...'

              : 'Create Facility'
          }}
        </button>

      </div>

    </div>

  </div>

</template>