<script setup lang="ts">
import {
  ref,
  watch
} from 'vue'

import {
  updateScheduleRequest
} from '../services/scheduleApi'

import {
  getFacilitiesRequest
} from '../../facilities/services/facilityApi'

import type {
  Schedule
} from '../../../types/schedule'

import type {
  Facility
} from '../../../types/facility'

const props =
  defineProps<{

    show: boolean

    schedule: Schedule | null
}>()

const emit =
  defineEmits([

    'close',

    'updated'
  ])

const facilities =
  ref<Facility[]>([])

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

fetchFacilities()

watch(

  () => props.schedule,

  schedule => {

    if (!schedule) {
      return
    }

    facilityId.value =
      String(
        schedule.facility?.id
      )

    organization.value =
      schedule.organization

    eventName.value =
      schedule.event_name

    eventDate.value =
      schedule.event_date

    startTime.value =
      schedule.start_time

    endTime.value =
      schedule.end_time

    description.value =
      schedule.description || ''
  },

  {
    immediate: true
  }
)

const handleSubmit = async () => {

  if (
    !props.schedule
  ) {
    return
  }

  loading.value =
    true

  error.value =
    ''

  try {

    await updateScheduleRequest(

      props.schedule.id,

      {

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
          description.value
      }
    )

    emit('updated')

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
      class="bg-white rounded-xl p-6 w-[550px]"
    >

      <div class="mb-4">

        <h2 class="text-xl font-semibold">
          Edit Reservation
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
          class="bg-blue-600 text-white px-4 py-2 rounded-md"
        >
          {{

            loading

              ? 'Updating...'

              : 'Update Reservation'
          }}
        </button>

      </div>

    </div>

  </div>

</template>