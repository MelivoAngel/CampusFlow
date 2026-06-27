<script setup lang="ts">
import { computed } from 'vue'

import {
  PencilSquareIcon,
  TrashIcon
} from '@heroicons/vue/24/outline'

import { useAuthStore } from '../../../stores/authStore'

import type {
  Facility
} from '../../../types/facility'

import {
  adminRoles
} from '../../../constants/roles'

defineProps<{
  facilities: Facility[]
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

const canEdit = computed(() => {

  return adminRoles.includes(

    authStore.user?.role || ''
  ) ||

  authStore.user?.role ===
  'calendar_admin'
})
</script>

<template>

  <div class="overflow-x-auto">

    <table class="w-full table-fixed">

      <thead>

        <tr class="border-b">

          <th class="text-left py-3 font-medium w-72">
            Name
          </th>

          <th
            v-if="showCampus"
            class="text-center py-3 font-medium w-48"
          >
            Campus
          </th>

          <th class="text-center py-3 font-medium w-32">
            Status
          </th>

          <th class="text-center py-3 font-medium w-32">
            Actions
          </th>

        </tr>

      </thead>

      <tbody>

        <tr
          v-for="facility in facilities"
          :key="facility.id"
          class="border-b hover:bg-gray-50 transition-colors"
        >

          <td
            class="py-4 truncate"
            :title="facility.name"
          >
            {{ facility.name }}
          </td>

          <td
            v-if="showCampus"
            class="py-4 text-center"
          >
            {{
              facility.campus?.name ||
              '-'
            }}
          </td>

          <td class="py-4 text-center">

            <span
              :class="
                facility.is_active
                  ? 'bg-green-100 text-green-700'
                  : 'bg-red-100 text-red-700'
              "
              class="px-2 py-1 rounded-full text-xs"
            >
              {{
                facility.is_active
                  ? 'Active'
                  : 'Inactive'
              }}
            </span>

          </td>

          <td class="py-4">

            <div
              class="flex justify-center gap-3"
            >

              <button
                v-if="canEdit"
                @click="
                  emit(
                    'edit',
                    facility
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
                    'delete',
                    facility
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