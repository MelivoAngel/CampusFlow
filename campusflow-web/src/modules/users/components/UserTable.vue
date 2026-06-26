<script setup lang="ts">
import { computed } from 'vue'

import {
  PencilSquareIcon,
  TrashIcon
} from '@heroicons/vue/24/outline'

import { useAuthStore } from '../../../stores/authStore'

import type { User } from '../../../types/user'

import {
  campusManageRoles,
  staffManageRoles
} from '../../../constants/roles'

defineProps<{
  users: User[]
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

const canEdit = (
  role: string
) => {

  if (
    authStore.user?.role ===
    'super_admin'
  ) {
    return true
  }

  if (
    authStore.user?.role ===
    'campus_admin'
  ) {
    return campusManageRoles.includes(
      role
    )
  }

  if (
    authStore.user?.role ===
    'staff'
  ) {
    return staffManageRoles.includes(
      role
    )
  }

  return false
}
</script>

<template>

  <div class="overflow-x-auto">

    <table class="w-full table-fixed">

      <thead>

        <tr class="border-b">

          <th class="text-left py-3 font-medium w-48">
            Name
          </th>

          <th class="text-left py-3 font-medium w-64">
            Email
          </th>

          <th class="text-center py-3 font-medium">
            Role
          </th>

          <th
            v-if="showCampus"
            class="text-left py-3 font-medium"
          >
            Campus
          </th>

          <th
            class="text-center py-3 font-medium w-32"
          >
            Actions
          </th>

        </tr>

      </thead>

      <tbody>

        <tr
          v-for="user in users"
          :key="user.id"
          class="border-b hover:bg-gray-50 transition-colors"
        >

          <td
            class="py-4 truncate"
            :title="user.name"
          >
            {{ user.name }}
          </td>

          <td
            class="py-4 truncate"
            :title="user.email"
          >
            {{ user.email }}
          </td>

          <td class="py-4 text-center">

            <span
              v-if="
                user.role ===
                'super_admin'
              "
              class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-700"
            >
              Super Admin
            </span>

            <span
              v-else-if="
                user.role ===
                'campus_admin'
              "
              class="px-2 py-1 text-xs rounded-full bg-emerald-100 text-emerald-700"
            >
              Campus Admin
            </span>

            <span
              v-else-if="
                user.role ===
                'staff'
              "
              class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700"
            >
              Staff
            </span>

            <span
              v-else-if="
                user.role ===
                'calendar_admin'
              "
              class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-700"
            >
              Calendar Admin
            </span>

            <span
              v-else
              class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700"
            >
              Field Technician
            </span>

          </td>

          <td
            v-if="showCampus"
            class="py-4"
          >
            {{
              user.campus?.name ||
              '-'
            }}
          </td>

          <td class="py-4">

            <div
              class="flex justify-center gap-3"
            >

              <button
                v-if="canEdit(user.role)"
                @click="
                  emit(
                    'edit',
                    user
                  )
                "
                class="w-9 h-9 flex items-center justify-center rounded-md bg-blue-50 text-blue-600"
              >
                <PencilSquareIcon
                  class="w-4 h-4"
                />
              </button>

              <button
                v-if="canEdit(user.role)"
                @click="
                  emit(
                    'delete',
                    user
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