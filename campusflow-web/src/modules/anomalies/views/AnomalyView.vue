<script setup lang="ts">
import { ref,onMounted } from 'vue'

import AppLayout from '../../../shared/layouts/AppLayout.vue'
import AnomalyTable from '../components/AnomalyTable.vue'
import ResolveAnomalyModal from '../components/ResolveAnomalyModal.vue'
import ConfirmModal from '../../../shared/components/ConfirmModal.vue'

import { getAnomaliesRequest } from '../services/anomalyApi'
import type { Anomaly } from '../types/anomaly'

const anomalies = ref<Anomaly[]>([])
const loading = ref(false)

const showResolveModal = ref(false)
const showConfirmModal = ref(false)

const selectedAnomaly = ref<Anomaly | null>(null)

const fetchAnomalies = async () => {
  loading.value = true

  try {
    const response = await getAnomaliesRequest()
    anomalies.value = response.data.data
  }

  catch (error) {
    console.log(error)
  }

  loading.value = false
}

const openResolveConfirm = (
  anomaly: Anomaly
) => {
  selectedAnomaly.value = anomaly
  showConfirmModal.value = true
}

const confirmResolve = () => {
  showConfirmModal.value = false
  showResolveModal.value = true
}

onMounted(() => fetchAnomalies())
</script>

<template>
  <AppLayout>

    <div class="bg-white p-6 rounded-xl shadow">

      <div class="mb-6">

        <h1 class="text-2xl font-semibold">
          Anomalies
        </h1>

      </div>

      <p
        v-if="loading"
        class="text-gray-500"
      >
        Loading anomalies...
      </p>

      <p
        v-else-if="!anomalies.length"
        class="text-gray-500"
      >
        No unresolved anomalies found
      </p>

      <AnomalyTable
        v-else
        :anomalies="anomalies"
        @resolve="openResolveConfirm"
      />

      <ResolveAnomalyModal
        :show="showResolveModal"
        :anomaly="selectedAnomaly"
        @close="showResolveModal = false"
        @updated="fetchAnomalies"
      />

      <ConfirmModal
        :show="showConfirmModal"
        title="Resolve Anomaly"
        message="Are you sure you want to continue resolving this anomaly?"
        @close="showConfirmModal = false"
        @confirm="confirmResolve"
      />

    </div>

  </AppLayout>
</template>