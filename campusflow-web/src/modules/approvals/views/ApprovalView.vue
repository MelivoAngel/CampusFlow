<script setup lang="ts">
import { ref,onMounted } from 'vue'

import AppLayout from '../../../shared/layouts/AppLayout.vue'
import ApprovalTable from '../components/ApprovalTable.vue'
import CorrectApprovalModal from '../components/CorrectApprovalModal.vue'
import ConfirmModal from '../../../shared/components/ConfirmModal.vue'

import {
  getApprovalsRequest,
  approveRequest
} from '../services/approvalApi'

import type { Approval } from '../types/approval'

const approvals = ref<Approval[]>([])
const loading = ref(false)

const showCorrectModal = ref(false)
const showConfirmModal = ref(false)

const selectedId = ref<number | null>(null)
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

const openApproveConfirm = (
  id: number
) => {
  selectedId.value = id
  showConfirmModal.value = true
}

const confirmApprove = async () => {
  if (!selectedId.value) {
    return
  }

  try {
    await approveRequest(
      selectedId.value
    )

    fetchApprovals()
  }

  catch (error) {
    console.log(error)
  }

  showConfirmModal.value = false
  selectedId.value = null
}

const handleCorrect = (
  approval: Approval
) => {
  selectedApproval.value = approval
  showCorrectModal.value = true
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
        @approve="openApproveConfirm"
        @correct="handleCorrect"
      />

      <CorrectApprovalModal
        :show="showCorrectModal"
        :approval="selectedApproval"
        @close="showCorrectModal = false"
        @updated="fetchApprovals"
      />

      <ConfirmModal
        :show="showConfirmModal"
        title="Approve Reading"
        message="Are you sure you want to approve this reading?"
        @close="showConfirmModal = false"
        @confirm="confirmApprove"
      />

    </div>

  </AppLayout>
</template>