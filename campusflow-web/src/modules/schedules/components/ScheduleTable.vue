<script setup lang="ts">
import { computed } from 'vue'

import {
  PencilSquareIcon,
  TrashIcon
} from '@heroicons/vue/24/outline'

import { useAuthStore } from '../../../stores/authStore'

import type {
  Schedule
} from '../../../types/schedule'

defineProps<{
  schedules: Schedule[]
}>()

const emit =
  defineEmits([

    'edit',

    'delete'
  ])

const authStore =
  useAuthStore()

const showCampus = computed(() => {

  return (
    authStore.user?.role ===
    'super_admin'
  )
})
</script>

<template>

  <div class="overflow-x-auto">

    <table class="w-full table-fixed">

      <thead>

        <tr class="border-b">

          <th class="text-left py-3 font-medium w-48">
            Facility
          </th>

          <th class="text-left py-3 font-medium w-48">
            Organization
          </th>

          <th class="text-left py-3 font-medium w-56">
            Event
          </th>

          <th class="text-center py-3 font-medium w-36">
            Date
          </th>

          <th class="text-center py-3 font-medium w-40">
            Time
          </th>

          <th
            v-if="showCampus"
            class="text-center py-3 font-medium w-40"
          >
            Campus
          </th>

          <th class="text-center py-3 font-medium w-32">
            Actions
          </th>

        </tr>

      </thead>

      <tbody>

        <tr
          v-for="schedule in schedules"
          :key="schedule.id"
          class="border-b hover:bg-gray-50 transition-colors"
        >

          <td
            class="py-4 truncate"
            :title="schedule.facility?.name"
          >
            {{
              schedule.facility?.name ||
              '-'
            }}
          </td>

          <td
            class="py-4 truncate"
            :title="schedule.organization"
          >
            {{ schedule.organization }}
          </td>

          <td
            class="py-4 truncate"
            :title="schedule.event_name"
          >
            {{ schedule.event_name }}
          </td>

          <td class="py-4 text-center">
            {{ schedule.event_date }}
          </td>

          <td class="py-4 text-center">

            <span
              class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-700"
            >
              {{ schedule.start_time }}
              -
              {{ schedule.end_time }}
            </span>

          </td>

          <td
            v-if="showCampus"
            class="py-4 text-center"
          >
            {{
              schedule.campus?.name ||
              '-'
            }}
          </td>

          <td class="py-4">

            <div
              class="flex justify-center gap-3"
            >

              <button
                @click="
                  emit(
                    'edit',
                    schedule
                  )
                "
                class="w-9 h-9 flex items-center justify-center rounded-md bg-blue-50 text-blue-600"
              >
                <PencilSquareIcon
                  class="w-4 h-4"
                />
              </button>

              <button
                @click="
                  emit(
                    'delete',
                    schedule
                  )
                "
                class="w-9 h-9 flex items-center justify-center rounded-md bg-red-600 text-white"
              >
                <TrashIcon
                  class="w-4 h-4"
                />
              </button>

            </div>

          </td>

        </tr>

      </tbody>

    </table>

  </div>

</template>