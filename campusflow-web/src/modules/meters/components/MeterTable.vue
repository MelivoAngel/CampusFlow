<script setup lang="ts">
import { computed } from 'vue'
import { PencilSquareIcon } from '@heroicons/vue/24/outline'
import { useAuthStore } from '../../../stores/authStore'

import type { Meter } from '../../../types/meter'
import { adminRoles } from '../../../constants/roles'

defineProps<{
  meters: Meter[]
}>()

const emit = defineEmits([
  'edit',
  'assign'
])

const authStore =
  useAuthStore()

const showCampus = computed(() => {

  return (
    authStore.user?.role ===
    'super_admin'
  )
})

const canEdit = computed(() => {

  return adminRoles.includes(

    authStore.user?.role || ''
  )
})
</script>

<template>

  <div class="overflow-x-auto">

    <table class="w-full table-fixed">

      <thead>

        <tr class="border-b">

          <th class="text-left py-3 font-medium w-56">
            Name
          </th>

          <th class="text-center py-3 font-medium w-32">
            Meter Code
          </th>

          <th class="text-center py-3 font-medium">
            Resource Type
          </th>

          <th class="text-center py-3 font-medium">
            Assigned Technician
          </th>

          <th
            v-if="showCampus"
            class="text-left py-3 font-medium"
          >
            Campus
          </th>

          <th class="text-center py-3 font-medium w-28">
            Status
          </th>

          <th class="text-center py-3 font-medium w-60">
            Actions
          </th>

        </tr>

      </thead>

      <tbody>

        <tr
          v-for="meter in meters"
          :key="meter.id"
          class="border-b hover:bg-gray-50 transition-colors"
        >

          <td
            class="py-4 truncate"
            :title="meter.name"
          >
            {{ meter.name }}
          </td>

          <td
            class="py-4 font-mono text-sm text-center"
          >
            {{ meter.meter_code }}
          </td>

          <td class="py-4 text-center">

            <span
              v-if="
                meter.resource_type ===
                'electricity'
              "
              class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700"
            >
              Electricity
            </span>

            <span
              v-else
              class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700"
            >
              Water
            </span>

          </td>

          <td class="py-4 text-center">

            <span
              v-if="meter.assignment?.technician"
            >
              {{
                meter.assignment
                  .technician
                  .name
              }}
            </span>

            <span
              v-else
              class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600"
            >
              Unassigned
            </span>

          </td>

          <td
            v-if="showCampus"
            class="py-4"
          >
            {{
              meter.campus?.name ||
              '-'
            }}
          </td>

          <td class="py-4 text-center">

            <span
              :class="
                meter.is_active
                  ? 'bg-green-100 text-green-700'
                  : 'bg-red-100 text-red-700'
              "
              class="px-2 py-1 rounded-full text-xs"
            >
              {{
                meter.is_active
                  ? 'Active'
                  : 'Inactive'
              }}
            </span>

          </td>

          <td class="py-4">

            <div
              class="flex justify-center items-center gap-3"
            >

              <button
                v-if="canEdit"
                @click="
                  emit(
                    'edit',
                    meter
                  )
                "
                class="w-9 h-9 flex items-center justify-center rounded-md bg-blue-50 text-blue-600"
              >
                <PencilSquareIcon
                  class="w-4 h-4"
                />
              </button>

              <button
                v-if="canEdit"
                @click="
                  emit(
                    'assign',
                    meter
                  )
                "
                class="px-3 py-2 text-sm rounded-md bg-red-50 text-red-600"
              >
                Assign Technician
              </button>

            </div>

          </td>

        </tr>

      </tbody>

    </table>

  </div>

</template>