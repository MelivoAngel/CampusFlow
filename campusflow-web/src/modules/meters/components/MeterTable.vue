<script setup lang="ts">
import { computed } from 'vue'
import { useAuthStore } from '../../../stores/authStore'

import type { Meter } from '../../../types/meter'
import { adminRoles } from '../../../constants/roles'
import { formatLabel } from '../../../utils/formatters'

defineProps<{
  meters: Meter[]
}>()

const emit =
  defineEmits([
    'edit'
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

  <table class="w-full">

    <thead>

      <tr class="border-b">

        <th class="text-left py-3">
          Name
        </th>

        <th class="text-left py-3">
          Meter Code
        </th>

        <th class="text-left py-3">
          Resource Type
        </th>

        <th
          v-if="showCampus"
          class="text-left py-3"
        >
          Campus
        </th>

        <th class="text-left py-3">
          Status
        </th>

        <th class="text-left py-3">
          Actions
        </th>

      </tr>

    </thead>

    <tbody>

      <tr
        v-for="meter in meters"
        :key="meter.id"
        class="border-b"
      >

        <td class="py-4">
          {{ meter.name }}
        </td>

        <td class="py-4">
          {{ meter.meter_code }}
        </td>

        <td class="py-4">
          {{ formatLabel(meter.resource_type) }}
        </td>

        <td
          v-if="showCampus"
          class="py-4"
        >
          {{ meter.campus?.name || '-' }}
        </td>

        <td class="py-4">
          {{ meter.is_active ? 'Active' : 'Inactive' }}
        </td>

        <td class="py-4">

          <button
            v-if="canEdit"
            @click="emit('edit',meter)"
            class="text-blue-600"
          >
            Edit
          </button>

        </td>

      </tr>

    </tbody>

  </table>

</template>