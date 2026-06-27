<script setup lang="ts">
import { ref,onMounted,computed } from 'vue'
import { useAuthStore } from '../../../stores/authStore'

import AppLayout from '../../../shared/layouts/AppLayout.vue'
import ScheduleTable from '../components/ScheduleTable.vue'
import CreateScheduleModal from '../components/CreateScheduleModal.vue'
import EditScheduleModal from '../components/EditScheduleModal.vue'
import DeleteScheduleModal from '../components/DeleteScheduleModal.vue'

import {
  getSchedulesRequest
} from '../services/scheduleApi'

import {
  getCampusesRequest
} from '../../users/services/userApi'

import type {
  Schedule
} from '../../../types/schedule'

import type {
  Campus
} from '../../../types/campus'

const authStore =
  useAuthStore()

const schedules =
  ref<Schedule[]>([])

const campuses =
  ref<Campus[]>([])

const loading =
  ref(false)

const search =
  ref('')

const campusFilter =
  ref('')

const showCreateModal =
  ref(false)

const showEditModal =
  ref(false)

const showDeleteModal =
  ref(false)

const selectedSchedule =
  ref<Schedule | null>(null)

const isSuperAdmin = computed(() => {

  return (
    authStore.user?.role ===
    'super_admin'
  )
})

const filteredSchedules = computed(() => {

  return schedules.value.filter(
    schedule => {

      const matchesSearch =

        schedule.event_name
          .toLowerCase()
          .includes(
            search.value
              .toLowerCase()
          ) ||

        schedule.organization
          .toLowerCase()
          .includes(
            search.value
              .toLowerCase()
          )

      const matchesCampus =

        !campusFilter.value ||

        schedule.campus?.id ===
        Number(
          campusFilter.value
        )

      return (

        matchesSearch &&

        matchesCampus
      )
    }
  )
})

const fetchSchedules = async () => {

  loading.value =
    true

  try {

    const response =
      await getSchedulesRequest()

    schedules.value =
      response.data.data
  }

  catch (error) {
    console.log(error)
  }

  loading.value =
    false
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

const handleEdit = (
  schedule: Schedule
) => {

  selectedSchedule.value =
    schedule

  showEditModal.value =
    true
}

const handleDelete = (
  schedule: Schedule
) => {

  selectedSchedule.value =
    schedule

  showDeleteModal.value =
    true
}

onMounted(() => {

  fetchSchedules()

  fetchCampuses()
})
</script>

<template>

  <AppLayout>

    <div class="bg-white p-6 rounded-xl shadow">

      <div class="flex justify-between mb-6">

        <div>

          <h1 class="text-2xl font-semibold">
            Reservations
          </h1>

          <p class="text-sm text-gray-500">
            {{ filteredSchedules.length }}
            scheduled reservations
          </p>

        </div>

        <button
          @click="showCreateModal = true"
          class="bg-emerald-700 text-white px-4 py-2 rounded-md"
        >
          Add Reservation
        </button>

      </div>

      <div class="flex gap-3 mb-6">

        <input
          v-model="search"
          placeholder="Search reservation..."
          class="border border-gray-300 rounded-md px-3 py-2 flex-1"
        >

        <select
          v-if="isSuperAdmin"
          v-model="campusFilter"
          class="border border-gray-300 rounded-md px-3 py-2"
        >

          <option value="">
            All Campuses
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
        v-if="loading"
        class="text-gray-500"
      >
        Loading reservations...
      </p>

      <p
        v-else-if="!filteredSchedules.length"
        class="text-gray-500"
      >
        No reservations found
      </p>

      <ScheduleTable
        v-else
        :schedules="filteredSchedules"
        @edit="handleEdit"
        @delete="handleDelete"
      />

      <CreateScheduleModal
        :show="showCreateModal"
        @close="showCreateModal = false"
        @created="fetchSchedules"
      />

      <EditScheduleModal
        :show="showEditModal"
        :schedule="selectedSchedule"
        @close="showEditModal = false"
        @updated="fetchSchedules"
      />

      <DeleteScheduleModal
        :show="showDeleteModal"
        :schedule="selectedSchedule"
        @close="showDeleteModal = false"
        @deleted="fetchSchedules"
      />

    </div>

  </AppLayout>

</template>