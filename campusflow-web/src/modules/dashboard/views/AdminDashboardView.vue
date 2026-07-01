<script setup lang="ts">
import { ref,onMounted } from 'vue'
import { useRouter } from 'vue-router'

import {
  BoltIcon,
  BeakerIcon,
  TrashIcon,
  ClipboardDocumentCheckIcon,
  ExclamationTriangleIcon,
  CalendarDaysIcon,
  CheckCircleIcon,
  ClockIcon
} from '@heroicons/vue/24/outline'

import AppLayout from '../../../shared/layouts/AppLayout.vue'

import {
  getAdminDashboardRequest
} from '../services/dashboardApi'

const router =
  useRouter()

const loading =
  ref(false)

const dashboard =
  ref<any>(null)

const formatNumber = (
  value:number
) => {

  return Number(

    value

  ).toLocaleString(
    undefined,
    {
      maximumFractionDigits: 2
    }
  )
}

const fetchDashboard = async () => {

  loading.value =
    true

  try {

    const response =
      await getAdminDashboardRequest()

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

    <div class="space-y-6">

      <p
        v-if="loading"
        class="text-gray-500"
      >
        Loading dashboard...
      </p>

      <template
        v-else-if="dashboard"
      >

        <!-- resources -->

        <div
          class="grid grid-cols-3 gap-5"
        >

          <div
            class="bg-cyan-600 text-white rounded-2xl p-8 min-h-[170px] shadow-md"
          >

            <div
              class="flex justify-between items-center"
            >

              <div>

                <p class="text-sm opacity-80">
                  Water This Month
                </p>

                <h2
                  class="text-4xl font-bold mt-4"
                >
                  {{
                    formatNumber(
                      dashboard.monthly_water_consumption
                    )
                  }}
                </h2>

                <p class="text-sm mt-2">m<sup>3</sup></p>

              </div>

              <BeakerIcon
                class="w-10 h-10 opacity-70"
              />

            </div>

          </div>

          <div
            class="bg-yellow-500 text-white rounded-2xl p-8 min-h-[170px] shadow-md"
          >

            <div
              class="flex justify-between items-center"
            >

              <div>

                <p class="text-sm opacity-80">
                  Electricity This Month
                </p>

                <h2
                  class="text-4xl font-bold mt-4"
                >
                  {{
                    formatNumber(
                      dashboard.monthly_electricity_consumption
                    )
                  }}
                </h2>

                <p class="text-sm mt-2">
                  kWh
                </p>

              </div>

              <BoltIcon
                class="w-10 h-10 opacity-70"
              />

            </div>

          </div>

          <div
            class="bg-gray-500 text-white rounded-2xl p-8 min-h-[170px] shadow-md"
          >

            <div
              class="flex justify-between items-center"
            >

              <div>

                <p class="text-sm opacity-80">
                  Waste Management
                </p>

                <h2
                  class="text-2xl font-bold mt-6"
                >
                  Coming Soon
                </h2>

              </div>

              <TrashIcon
                class="w-10 h-10 opacity-70"
              />

            </div>

          </div>

        </div>

        <!-- monitoring -->

        <div
          class="grid grid-cols-4 gap-4"
        >

          <div
            class="bg-white rounded-xl p-4 shadow-sm border"
          >

            <div
              class="flex justify-between items-center"
            >

              <div>

                <p class="text-gray-500 text-sm">
                  Readings Today
                </p>

                <h2
                  class="text-2xl font-bold mt-2"
                >
                  {{ dashboard.readings_today }}
                </h2>

              </div>

              <ClockIcon
                class="w-6 h-6 text-blue-500"
              />

            </div>

          </div>

          <div
            @click="router.push('/approvals')"
            class="bg-white rounded-xl p-4 shadow-sm border cursor-pointer hover:shadow-md transition"
          >

            <div
              class="flex justify-between items-center"
            >

              <div>

                <p class="text-gray-500 text-sm">
                  Pending Readings
                </p>

                <h2
                  class="text-2xl font-bold mt-2 text-yellow-600"
                >
                  {{ dashboard.pending_readings }}
                </h2>

              </div>

              <ClipboardDocumentCheckIcon
                class="w-6 h-6 text-yellow-500"
              />

            </div>

          </div>

          <div
            class="bg-white rounded-xl p-4 shadow-sm border"
          >

            <div
              class="flex justify-between items-center"
            >

              <div>

                <p class="text-gray-500 text-sm">
                  Approved Readings
                </p>

                <h2
                  class="text-2xl font-bold mt-2 text-green-600"
                >
                  {{ dashboard.approved_readings }}
                </h2>

              </div>

              <CheckCircleIcon
                class="w-6 h-6 text-green-500"
              />

            </div>

          </div>

          <div
            @click="router.push('/anomalies')"
            class="bg-white rounded-xl p-4 shadow-sm border cursor-pointer hover:shadow-md transition"
          >

            <div
              class="flex justify-between items-center"
            >

              <div>

                <p class="text-gray-500 text-sm">
                  Active Anomalies
                </p>

                <h2
                  class="text-2xl font-bold mt-2 text-red-600"
                >
                  {{ dashboard.active_anomalies }}
                </h2>

              </div>

              <ExclamationTriangleIcon
                class="w-6 h-6 text-red-500"
              />

            </div>

          </div>

        </div>

        <!-- calendar -->

        <div
          class="bg-white rounded-2xl p-5 shadow-sm border"
        >

          <div
            class="flex justify-between items-center"
          >

            <h2
              class="font-semibold"
            >
              Calendar Activity
            </h2>

            <div
              class="flex gap-12"
            >

              <div>

                <p class="text-sm text-gray-500">
                  Events Today
                </p>

                <p class="font-bold text-indigo-600">
                  {{ dashboard.events_today }}
                </p>

              </div>

              <div>

                <p class="text-sm text-gray-500">
                  Upcoming Events
                </p>

                <p class="font-bold text-purple-600">
                  {{ dashboard.upcoming_events }}
                </p>

              </div>

              <CalendarDaysIcon
                class="w-7 h-7 text-indigo-500"
              />

            </div>

          </div>

        </div>

        <!-- tables -->

        <div
          class="grid grid-cols-2 gap-6"
        >

          <div
            class="bg-white p-6 rounded-2xl shadow-sm"
          >

            <h2
              class="text-lg font-semibold mb-4"
            >
              Recent Anomalies
            </h2>

            <div
              v-if="!dashboard.recent_anomalies.length"
              class="py-8 text-center text-gray-400"
            >
              No anomalies detected
            </div>

            <table
              v-else
              class="w-full text-sm"
            >

              <tbody>

                <tr
                  v-for="anomaly in dashboard.recent_anomalies"
                  :key="anomaly.id"
                  class="border-b hover:bg-gray-50"
                >

                  <td class="py-3">
                    {{ anomaly.meter?.name }}
                  </td>

                  <td class="py-3">
                    {{ anomaly.type }}
                  </td>

                  <td class="py-3 text-red-600">
                    {{ anomaly.severity }}
                  </td>

                </tr>

              </tbody>

            </table>

          </div>

          <div
            class="bg-white p-6 rounded-2xl shadow-sm"
          >

            <h2
              class="text-lg font-semibold mb-4"
            >
              Pending Approvals
            </h2>

            <div
              v-if="!dashboard.pending_approvals.length"
              class="py-8 text-center text-gray-400"
            >
              No pending approvals
            </div>

            <table
              v-else
              class="w-full text-sm"
            >

              <tbody>

                <tr
                  v-for="reading in dashboard.pending_approvals"
                  :key="reading.id"
                  class="border-b hover:bg-gray-50"
                >

                  <td class="py-3">
                    {{ reading.technician?.name }}
                  </td>

                  <td class="py-3">
                    {{ reading.meter?.name }}
                  </td>

                  <td class="py-3 text-yellow-600">
                    {{ reading.recorded_date }}
                  </td>

                </tr>

              </tbody>

            </table>

          </div>

        </div>

      </template>

    </div>

  </AppLayout>

</template>