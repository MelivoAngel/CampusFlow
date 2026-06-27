<script setup lang="ts">
import { ref,onMounted } from 'vue'

import {
  CalendarDaysIcon,
  ClockIcon,
  BuildingOffice2Icon,
  CheckCircleIcon,
  XCircleIcon,
  ArrowPathRoundedSquareIcon
} from '@heroicons/vue/24/outline'

import AppLayout from '../../../shared/layouts/AppLayout.vue'

import {
  getCalendarDashboardRequest
} from '../services/dashboardApi'

import type {
  CalendarDashboard
} from '../types/calendarDashboard'

const loading =
  ref(false)

const dashboard =
  ref<CalendarDashboard | null>(
    null
  )

const fetchDashboard = async () => {

  loading.value =
    true

  try {

    const response =
      await getCalendarDashboardRequest()

    dashboard.value =
      response.data.data
  }

  catch (error) {
    console.log(error)
  }

  loading.value =
    false
}

onMounted(() => {

  fetchDashboard()
})
</script>

<template>

  <AppLayout>

    <div class="bg-gray-50 min-h-screen p-6 space-y-6">

      <div>

        <h1 class="text-3xl font-semibold">
          Calendar Dashboard
        </h1>

        <p class="text-gray-500 mt-1">
          Reservation analytics and facility activity overview
        </p>

      </div>

      <p
        v-if="loading"
        class="text-gray-500"
      >
        Loading dashboard...
      </p>

      <template v-else-if="dashboard">

        <!-- reservation metrics -->

        <div class="grid grid-cols-3 gap-5">

          <div
            class="bg-emerald-600 text-white rounded-2xl p-6 shadow-md"
          >

            <div
              class="flex justify-between items-start"
            >

              <div>

                <p class="text-sm opacity-80">
                  Reservations Today
                </p>

                <h2
                  class="text-4xl font-bold mt-3"
                >
                  {{ dashboard.reservations_today }}
                </h2>

              </div>

              <CalendarDaysIcon
                class="w-8 h-8 opacity-70"
              />

            </div>

          </div>

          <div
            class="bg-blue-600 text-white rounded-2xl p-6 shadow-md"
          >

            <div
              class="flex justify-between items-start"
            >

              <div>

                <p class="text-sm opacity-80">
                  This Week
                </p>

                <h2
                  class="text-4xl font-bold mt-3"
                >
                  {{ dashboard.reservations_week }}
                </h2>

              </div>

              <ClockIcon
                class="w-8 h-8 opacity-70"
              />

            </div>

          </div>

          <div
            class="bg-indigo-600 text-white rounded-2xl p-6 shadow-md"
          >

            <div
              class="flex justify-between items-start"
            >

              <div>

                <p class="text-sm opacity-80">
                  This Month
                </p>

                <h2
                  class="text-4xl font-bold mt-3"
                >
                  {{ dashboard.reservations_month }}
                </h2>

              </div>

              <BuildingOffice2Icon
                class="w-8 h-8 opacity-70"
              />

            </div>

          </div>

        </div>

        <!-- status metrics -->

        <div class="grid grid-cols-5 gap-4">

          <div
            class="bg-blue-50 rounded-xl p-4 shadow-sm"
          >

            <div class="flex justify-between">

              <p class="text-sm text-blue-600">
                Upcoming
              </p>

              <CalendarDaysIcon
                class="w-5 h-5 text-blue-600"
              />

            </div>

            <h2
              class="text-2xl font-bold text-blue-700 mt-3"
            >
              {{ dashboard.upcoming }}
            </h2>

          </div>

          <div
            class="bg-yellow-50 rounded-xl p-4 shadow-sm"
          >

            <div class="flex justify-between">

              <p class="text-sm text-yellow-700">
                Ongoing
              </p>

              <ClockIcon
                class="w-5 h-5 text-yellow-700"
              />

            </div>

            <h2
              class="text-2xl font-bold text-yellow-700 mt-3"
            >
              {{ dashboard.ongoing }}
            </h2>

          </div>

          <div
            class="bg-green-50 rounded-xl p-4 shadow-sm"
          >

            <div class="flex justify-between">

              <p class="text-sm text-green-700">
                Completed
              </p>

              <CheckCircleIcon
                class="w-5 h-5 text-green-700"
              />

            </div>

            <h2
              class="text-2xl font-bold text-green-700 mt-3"
            >
              {{ dashboard.completed }}
            </h2>

          </div>

          <div
            class="bg-red-50 rounded-xl p-4 shadow-sm"
          >

            <div class="flex justify-between">

              <p class="text-sm text-red-700">
                Cancelled
              </p>

              <XCircleIcon
                class="w-5 h-5 text-red-700"
              />

            </div>

            <h2
              class="text-2xl font-bold text-red-700 mt-3"
            >
              {{ dashboard.cancelled }}
            </h2>

          </div>

          <div
            class="bg-purple-50 rounded-xl p-4 shadow-sm"
          >

            <div class="flex justify-between">

              <p class="text-sm text-purple-700">
                Rescheduled
              </p>

              <ArrowPathRoundedSquareIcon
                class="w-5 h-5 text-purple-700"
              />

            </div>

            <h2
              class="text-2xl font-bold text-purple-700 mt-3"
            >
              {{ dashboard.rescheduled }}
            </h2>

          </div>

        </div>

        <!-- middle section -->

        <div class="grid grid-cols-3 gap-6">

          <!-- top facilities -->

          <div
            class="bg-white rounded-2xl shadow-sm p-6"
          >

            <div
              class="flex items-center gap-2 mb-5"
            >

              <BuildingOffice2Icon
                class="w-5 h-5 text-gray-600"
              />

              <h2
                class="text-lg font-semibold"
              >
                Top Facilities
              </h2>

            </div>

            <div
              v-if="!dashboard.top_facilities.length"
              class="text-gray-400"
            >
              No reservations yet
            </div>

            <div
              v-else
              class="space-y-4"
            >

              <div
                v-for="item in dashboard.top_facilities"
                :key="item.facility_id"
                class="flex justify-between items-center"
              >

                <span class="text-gray-700">
                  {{
                    item.facility?.name ||
                    'Unknown'
                  }}
                </span>

                <span
                  class="bg-gray-100 px-3 py-1 rounded-full text-sm font-medium"
                >
                  {{ item.total }}
                </span>

              </div>

            </div>

          </div>

          <!-- events today -->

          <div
            class="col-span-2 bg-white rounded-2xl shadow-sm p-6"
          >

            <div
              class="flex items-center gap-2 mb-5"
            >

              <CalendarDaysIcon
                class="w-5 h-5 text-gray-600"
              />

              <h2
                class="text-lg font-semibold"
              >
                Events Today
              </h2>

            </div>

            <table
              class="w-full text-sm"
            >

              <thead
                class="bg-gray-50"
              >

                <tr>

                  <th class="text-left px-4 py-3">
                    Organization
                  </th>

                  <th class="text-left px-4 py-3">
                    Event
                  </th>

                  <th class="text-left px-4 py-3">
                    Facility
                  </th>

                  <th class="text-left px-4 py-3">
                    Time
                  </th>

                  <th class="text-left px-4 py-3">
                    Status
                  </th>

                </tr>

              </thead>

              <tbody>

                <tr
                  v-for="event in dashboard.today_events"
                  :key="event.id"
                  class="border-b hover:bg-gray-50"
                >

                  <td class="px-4 py-3">
                    {{ event.organization }}
                  </td>

                  <td class="px-4 py-3">
                    {{ event.event_name }}
                  </td>

                  <td class="px-4 py-3">
                    {{
                      event.facility?.name ||
                      '-'
                    }}
                  </td>

                  <td class="px-4 py-3">
                    {{ event.start_time }} - {{ event.end_time }}
                  </td>

                  <td class="px-4 py-3">

                    <span
                      class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-700"
                    >
                      {{ event.status }}
                    </span>

                  </td>

                </tr>

              </tbody>

            </table>

          </div>

        </div>

        <!-- upcoming -->

        <div
          class="bg-white rounded-2xl shadow-sm p-6"
        >

          <div
            class="flex items-center gap-2 mb-5"
          >

            <ClockIcon
              class="w-5 h-5 text-gray-600"
            />

            <h2
              class="text-lg font-semibold"
            >
              Upcoming Events This Month
            </h2>

          </div>

          <table
            class="w-full text-sm"
          >

            <thead
              class="bg-gray-50"
            >

              <tr>

                <th class="text-left px-4 py-3">
                  Event
                </th>

                <th class="text-left px-4 py-3">
                  Facility
                </th>

                <th class="text-left px-4 py-3">
                  Date
                </th>

              </tr>

            </thead>

            <tbody>

              <tr
                v-for="event in dashboard.upcoming_events"
                :key="event.id"
                class="border-b hover:bg-gray-50"
              >

                <td class="px-4 py-3">
                  {{ event.event_name }}
                </td>

                <td class="px-4 py-3">
                  {{
                    event.facility?.name ||
                    '-'
                  }}
                </td>

                <td class="px-4 py-3">
                  {{ event.event_date }}
                </td>

              </tr>

            </tbody>

          </table>

        </div>

      </template>

    </div>

  </AppLayout>

</template>