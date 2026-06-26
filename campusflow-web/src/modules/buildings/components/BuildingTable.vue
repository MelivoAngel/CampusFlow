<script setup lang="ts">
import { computed } from 'vue'

import {
  PencilSquareIcon,
  TrashIcon
} from '@heroicons/vue/24/outline'

import { useAuthStore } from '../../../stores/authStore'

import type { Building } from '../../../types/building'
import { adminRoles } from '../../../constants/roles'

defineProps<{
  buildings: Building[]
}>()

const emit = defineEmits([
  'edit',
  'assign',
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

          <th class="text-left py-3 font-medium w-1/4">
            Name
          </th>

          <th class="text-center py-3 font-medium w-32">
            Code
          </th>

          <th class="text-center py-3 font-medium w-40">
            Assigned Meters
          </th>

          <th
            v-if="showCampus"
            class="text-center py-3 font-medium w-40"
          >
            Campus
          </th>

          <th class="text-center py-3 font-medium w-32">
            Status
          </th>

          <th class="text-center py-3 font-medium w-72">
            Actions
          </th>

        </tr>

      </thead>

      <tbody>

        <tr
          v-for="building in buildings"
          :key="building.id"
          class="border-b hover:bg-gray-50 transition-colors"
        >

          <td
            class="py-4 truncate"
            :title="building.name"
          >
            {{ building.name }}
          </td>

          <td
            class="py-4 text-center font-mono text-sm"
          >
            {{ building.code }}
          </td>

          <td class="py-4 text-center">

            <span
              class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-700"
            >
              {{ building.meters_count }}
              meters
            </span>

          </td>

          <td
            v-if="showCampus"
            class="py-4 text-center"
          >
            {{
              building.campus?.name ||
              '-'
            }}
          </td>

          <td class="py-4 text-center">

            <span
              :class="
                building.meters_count > 0
                  ? 'bg-green-100 text-green-700'
                  : 'bg-red-100 text-red-700'
              "
              class="px-2 py-1 rounded-full text-xs"
            >
              {{
                building.meters_count > 0
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
                    building
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
                    building
                  )
                "
                class="px-3 py-2 text-sm rounded-md bg-amber-50 text-amber-600"
              >
                Assign Meters
              </button>

              <button
                v-if="canEdit"
                @click="
                  emit(
                    'delete',
                    building
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