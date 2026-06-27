<script setup lang="ts">
import {
  ref,
  computed
} from 'vue'

import { useAuthStore } from '../../../stores/authStore'

import {
  createScheduleRequest
} from '../services/scheduleApi'

import {
  getFacilitiesRequest
} from '../../facilities/services/facilityApi'

import {
  getCampusesRequest
} from '../../users/services/userApi'

import type {
  Facility
} from '../../../types/facility'

import type {
  Campus
} from '../../../types/campus'

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

const facilities =
  ref<Facility[]>([])

const campuses =
  ref<Campus[]>([])

const loading =
  ref(false)

const error =
  ref('')

const facilityId =
  ref('')

const organization =
  ref('')

const eventName =
  ref('')

const eventDate =
  ref('')

const startTime =
  ref('')

const endTime =
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

const fetchFacilities = async () => {

  try {

    const response =
      await getFacilitiesRequest()

    facilities.value =
      response.data.data
  }

  catch (error) {
    console.log(error)
  }
}

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

fetchFacilities()

fetchCampuses()

const handleSubmit = async () => {

  loading.value =
    true

  error.value =
    ''

  try {

    await createScheduleRequest({

      facility_id:
        facilityId.value,

      organization:
        organization.value,

      event_name:
        eventName.value,

      event_date:
        eventDate.value,

      start_time:
        startTime.value,

      end_time:
        endTime.value,

      description:
        description.value,

      campus_id:

        isSuperAdmin.value

          ? campusId.value

          : null
    })

    emit('created')

    emit('close')

    facilityId.value =
      ''

    organization.value =
      ''

    eventName.value =
      ''

    eventDate.value =
      ''

    startTime.value =
      ''

    endTime.value =
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
      class="bg-white rounded-xl p-6 w-[550px]"
    >

      <div class="mb-4">

        <h2 class="text-xl font-semibold">
          Create Reservation
        </h2>

      </div>

      <div class="space-y-4">

        <select
          v-model="facilityId"
          class="w-full border border-gray-300 rounded-md px-3 py-2"
        >

          <option value="">
            Select Facility
          </option>

          <option
            v-for="facility in facilities"
            :key="facility.id"
            :value="facility.id"
          >
            {{ facility.name }}
          </option>

        </select>

        <input
          v-model="organization"
          placeholder="Organization / Department"
          class="w-full border border-gray-300 rounded-md px-3 py-2"
        >

        <input
          v-model="eventName"
          placeholder="Event Name"
          class="w-full border border-gray-300 rounded-md px-3 py-2"
        >

        <input
          v-model="eventDate"
          type="date"
          class="w-full border border-gray-300 rounded-md px-3 py-2"
        >

        <div class="flex gap-3">

          <input
            v-model="startTime"
            type="time"
            class="flex-1 border border-gray-300 rounded-md px-3 py-2"
          >

          <input
            v-model="endTime"
            type="time"
            class="flex-1 border border-gray-300 rounded-md px-3 py-2"
          >

        </div>

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

              : 'Create Reservation'
          }}
        </button>

      </div>

    </div>

  </div>

</template>