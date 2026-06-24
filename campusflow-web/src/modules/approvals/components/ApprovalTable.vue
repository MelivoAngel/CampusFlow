<script setup lang="ts">
import type { Approval } from '../types/approval'

defineProps<{
  approvals: Approval[]
}>()

const emit = defineEmits([
  'approve',
  'correct'
])

const getStatus = (
  approval: Approval
) => {
  if (approval.anomaly) {
    return 'Anomaly'
  }

  return 'Pending'
}
</script>

<template>

  <table class="w-full">

    <thead>

      <tr class="border-b">

        <th class="text-left py-3">
          Meter
        </th>

        <th class="text-left py-3">
          Technician
        </th>

        <th class="text-left py-3">
          Current Reading
        </th>

        <th class="text-left py-3">
          Consumption Used
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
        v-for="approval in approvals"
        :key="approval.id"
        class="border-b"
      >

        <td class="py-4">
          {{ approval.meter.name }}
        </td>

        <td class="py-4">
          {{ approval.technician.name }}
        </td>

        <td class="py-4">
          {{ approval.current_reading }}
        </td>

        <td class="py-4">
          {{ approval.consumption }}
        </td>

        <td class="py-4">

          <span
            :class="[
              'px-2 py-1 rounded text-sm',
              approval.anomaly
                ? 'bg-red-100 text-red-600'
                : 'bg-yellow-100 text-yellow-700'
            ]"
          >
            {{ getStatus(approval) }}
          </span>

        </td>

        <td class="py-4">

          <button
            v-if="!approval.anomaly"
            @click="emit('approve',approval.id)"
            class="text-emerald-600"
          >
            Approve
          </button>

          <button
            v-else
            @click="emit('correct',approval)"
            class="text-orange-600"
          >
            Correct
          </button>

        </td>

      </tr>

    </tbody>

  </table>

</template>