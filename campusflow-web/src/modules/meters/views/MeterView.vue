<script setup lang="ts">
import { ref,onMounted,computed } from 'vue'
import { useAuthStore } from '../../../stores/authStore'

import AppLayout from '../../../shared/layouts/AppLayout.vue'
import MeterTable from '../components/MeterTable.vue'
import CreateMeterModal from '../components/CreateMeterModal.vue'
import EditMeterModal from '../components/EditMeterModal.vue'
import AssignMeterModal from '../components/AssignMeterModal.vue'

import { getMetersRequest } from '../services/meterApi'

import { adminRoles } from '../../../constants/roles'
import type { Meter } from '../../../types/meter'

const authStore = useAuthStore()

const meters = ref<Meter[]>([])
const loading = ref(false)

const search = ref('')
const filterType = ref('')

const showCreateModal = ref(false)
const showEditModal = ref(false)
const showAssignModal = ref(false)

const selectedMeter = ref<Meter | null>(null)

const canCreate = computed(() => {
  return adminRoles.includes(
    authStore.user?.role || ''
  )
})

const filteredMeters = computed(() => {

  return meters.value.filter(
    meter => {

      const matchesSearch =

        meter.name
          .toLowerCase()
          .includes(
            search.value
              .toLowerCase()
          ) ||

        meter.meter_code
          .toLowerCase()
          .includes(
            search.value
              .toLowerCase()
          )

      const matchesType =

        !filterType.value ||

        meter.resource_type ===
        filterType.value

      return (
        matchesSearch &&
        matchesType
      )
    }
  )
})

const fetchMeters = async () => {
  loading.value = true

  try {
    const response = await getMetersRequest()
    meters.value = response.data.data
  }

  catch (error) {
    console.log(error)
  }

  loading.value = false
}

const handleEdit = (
  meter: Meter
) => {
  selectedMeter.value = meter
  showEditModal.value = true
}

const handleAssign = (
  meter: Meter
) => {
  selectedMeter.value = meter
  showAssignModal.value = true
}

onMounted(() => fetchMeters())
</script>

<template>
  <AppLayout>

    <div class="bg-white p-6 rounded-xl shadow">

      <div class="flex justify-between mb-6">

        <div>

          <h1 class="text-2xl font-semibold">
            Meters
          </h1>

          <p class="text-sm text-gray-500">
            {{ filteredMeters.length }}
            registered meters
          </p>

        </div>

        <button
          v-if="canCreate"
          @click="showCreateModal = true"
          class="bg-emerald-700 text-white px-4 py-2 rounded-md"
        >
          Add Meter
        </button>

      </div>

      <div class="flex gap-3 mb-6">

        <input
          v-model="search"
          placeholder="Search meter..."
          class="border border-gray-300 rounded-md px-3 py-2 flex-1"
        >

        <select
          v-model="filterType"
          class="border border-gray-300 rounded-md px-3 py-2"
        >

          <option value="">
            All Types
          </option>

          <option value="electricity">
            Electricity
          </option>

          <option value="water">
            Water
          </option>

        </select>

      </div>

      <p
        v-if="loading"
        class="text-gray-500"
      >
        Loading meters...
      </p>

      <p
        v-else-if="!filteredMeters.length"
        class="text-gray-500"
      >
        No meters found
      </p>

      <MeterTable
        v-else
        :meters="filteredMeters"
        @edit="handleEdit"
        @assign="handleAssign"
      />

      <CreateMeterModal
        :show="showCreateModal"
        @close="showCreateModal = false"
        @created="fetchMeters"
      />

      <EditMeterModal
        :show="showEditModal"
        :meter="selectedMeter"
        @close="showEditModal = false"
        @updated="fetchMeters"
      />

      <AssignMeterModal
        :show="showAssignModal"
        :meter="selectedMeter"
        @close="showAssignModal = false"
        @updated="fetchMeters"
      />

    </div>

  </AppLayout>
</template>