<script setup lang="ts">
import type { Anomaly } from '../types/anomaly'

defineProps<{
  anomalies: Anomaly[]
}>()

const emit = defineEmits([
  'resolve'
])

const formatType = (
  type: string
) => {
  return type
    .replaceAll('_',' ')
    .replace(
      /\b\w/g,
      letter => letter.toUpperCase()
    )
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
          Type
        </th>

        <th class="text-left py-3">
          Severity
        </th>

        <th class="text-left py-3">
          Message
        </th>

        <th class="text-left py-3">
          Actions
        </th>

      </tr>

    </thead>

    <tbody>

      <tr
        v-for="anomaly in anomalies"
        :key="anomaly.id"
        class="border-b"
      >

        <td class="py-4">
          {{ anomaly.meter.name }}
        </td>

        <td class="py-4">
          {{ formatType(anomaly.type) }}
        </td>

        <td class="py-4">

          <span
            :class="[
              'px-2 py-1 rounded text-sm',
              anomaly.severity === 'critical'
                ? 'bg-red-100 text-red-600'
                : 'bg-orange-100 text-orange-600'
            ]"
          >
            {{ anomaly.severity }}
          </span>

        </td>

        <td class="py-4">
          {{ anomaly.message }}
        </td>

        <td class="py-4">

          <button
            @click="emit('resolve',anomaly)"
            class="text-blue-600"
          >
            Resolve
          </button>

        </td>

      </tr>

    </tbody>

  </table>

</template>