<script setup lang="ts">
import { ref,onMounted } from 'vue'

import AppLayout from '../../../shared/layouts/AppLayout.vue'
import ApprovalTable from '../components/ApprovalTable.vue'
import CorrectApprovalModal from '../components/CorrectApprovalModal.vue'

import {
  getApprovalsRequest,
  approveRequest
} from '../services/approvalApi'

import type { Approval } from '../types/approval'

const approvals = ref<Approval[]>([])
const loading = ref(false)

const showModal = ref(false)
const selectedApproval = ref<Approval | null>(null)

const fetchApprovals = async () => {
  loading.value = true

  try {
    const response = await getApprovalsRequest()
    approvals.value = response.data.data
  }

  catch (error) {
    console.log(error)
  }

  loading.value = false
}

const handleApprove = async (
  id: number
) => {
  try {
    await approveRequest(id)
    fetchApprovals()
  }

  catch (error) {
    console.log(error)
  }
}

const handleCorrect = (
  approval: Approval
) => {
  selectedApproval.value = approval
  showModal.value = true
}

onMounted(() => fetchApprovals())
</script>

<template>
  <AppLayout>

    <div class="bg-white p-6 rounded-xl shadow">

      <div class="mb-6">

        <h1 class="text-2xl font-semibold">
          Approvals
        </h1>

      </div>

      <p
        v-if="loading"
        class="text-gray-500"
      >
        Loading approvals...
      </p>

      <p
        v-else-if="!approvals.length"
        class="text-gray-500"
      >
        No pending approvals found
      </p>

      <ApprovalTable
        v-else
        :approvals="approvals"
        @approve="handleApprove"
        @correct="handleCorrect"
      />

      <CorrectApprovalModal
        :show="showModal"
        :approval="selectedApproval"
        @close="showModal = false"
        @updated="fetchApprovals"
      />

    </div>

  </AppLayout>
</template>