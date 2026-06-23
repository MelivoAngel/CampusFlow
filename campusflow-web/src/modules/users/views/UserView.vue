<script setup lang="ts">
import { ref,onMounted,computed } from 'vue'
import { useAuthStore } from '../../../stores/authStore'

import AppLayout from '../../../shared/layouts/AppLayout.vue'
import UserTable from '../components/UserTable.vue'
import CreateUserModal from '../components/CreateUserModal.vue'
import EditUserModal from '../components/EditUserModal.vue'

import { getUsersRequest } from '../services/userApi'
import { adminRoles } from '../../../constants/roles'
import type { User } from '../../../types/user'

const authStore =
  useAuthStore()

const users =
  ref<User[]>([])

const loading =
  ref(false)

const showModal =
  ref(false)

const showEditModal =
  ref(false)

const selectedUser =
  ref<User | null>(null)

const canCreate = computed(() => {

  return adminRoles.includes(

    authStore.user?.role || ''
  )
})

const fetchUsers = async () => {

  loading.value =
    true

  try {
    const response =
      await getUsersRequest()

    users.value =
      response.data.data
  }

  catch (error) {
    console.log(error)
  }

  loading.value =
    false
}

const handleEdit = (
  user: User
) => {

  selectedUser.value =
    user

  showEditModal.value =
    true
}

onMounted(() => {
  fetchUsers()
})
</script>

<template>
  <AppLayout>

    <div class="bg-white p-6 rounded-xl shadow">

      <div class="flex justify-between mb-6">

        <h1 class="text-2xl font-semibold">
          Users
        </h1>

        <button
          v-if="canCreate"
          @click="showModal = true"
          class="bg-emerald-700 text-white px-4 py-2 rounded-md"
        >
          Add User
        </button>

      </div>

      <p
        v-if="loading"
        class="text-gray-500"
      >
        Loading users...
      </p>

      <p
        v-else-if="!users.length"
        class="text-gray-500"
      >
        No users found
      </p>

      <UserTable
        v-else
        :users="users"
        @edit="handleEdit"
      />

      <CreateUserModal
        :show="showModal"
        @close="showModal = false"
        @created="fetchUsers"
      />

      <EditUserModal
        :show="showEditModal"
        :user="selectedUser"
        @close="showEditModal = false"
        @updated="fetchUsers"
      />

    </div>

  </AppLayout>
</template>