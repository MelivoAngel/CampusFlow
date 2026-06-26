<script setup lang="ts">
import {
  ref,
  onMounted,
  computed
} from 'vue'

import { useAuthStore } from '../../../stores/authStore'

import AppLayout from '../../../shared/layouts/AppLayout.vue'
import UserTable from '../components/UserTable.vue'
import CreateUserModal from '../components/CreateUserModal.vue'
import EditUserModal from '../components/EditUserModal.vue'
import DeleteUserModal from '../components/DeleteUserModal.vue'

import { getUsersRequest } from '../services/userApi'

import { adminRoles } from '../../../constants/roles'
import type { User } from '../../../types/user'

const authStore =
  useAuthStore()

const users =
  ref<User[]>([])

const loading =
  ref(false)

const search =
  ref('')

const roleFilter =
  ref('')

const campusFilter =
  ref('')

const showCreateModal =
  ref(false)

const showEditModal =
  ref(false)

const showDeleteModal =
  ref(false)

const selectedUser =
  ref<User | null>(null)

const isSuperAdmin =
  computed(() => {

    return (
      authStore.user?.role ===
      'super_admin'
    )
  })

const canCreate =
  computed(() => {

    return adminRoles.includes(
      authStore.user?.role || ''
    )
  })

const availableRoles =
  computed(() => {

    if (
      authStore.user?.role ===
      'super_admin'
    ) {
      return [
        {
          value: 'campus_admin',
          label: 'Campus Admin'
        },
        {
          value: 'staff',
          label: 'Staff'
        },
        {
          value: 'field_technician',
          label: 'Field Technician'
        },
        {
          value: 'calendar_admin',
          label: 'Calendar Admin'
        }
      ]
    }

    if (
      authStore.user?.role ===
      'campus_admin'
    ) {
      return [
        {
          value: 'staff',
          label: 'Staff'
        },
        {
          value: 'field_technician',
          label: 'Field Technician'
        },
        {
          value: 'calendar_admin',
          label: 'Calendar Admin'
        }
      ]
    }

    return [
      {
        value: 'field_technician',
        label: 'Field Technician'
      },
      {
        value: 'calendar_admin',
        label: 'Calendar Admin'
      }
    ]
  })

const campuses =
  computed(() => {

    const uniqueCampuses =
      users.value
        .map(
          user => user.campus?.name
        )
        .filter(Boolean)

    return [
      ...new Set(
        uniqueCampuses
      )
    ]
  })

const filteredUsers =
  computed(() => {

    return users.value.filter(
      user => {

        const matchesSearch =

          user.name
            .toLowerCase()
            .includes(
              search.value
                .toLowerCase()
            ) ||

          user.email
            .toLowerCase()
            .includes(
              search.value
                .toLowerCase()
            )

        const matchesRole =

          !roleFilter.value ||

          user.role ===
          roleFilter.value

        const matchesCampus =

          !campusFilter.value ||

          user.campus?.name ===
          campusFilter.value

        return (

          matchesSearch &&

          matchesRole &&

          matchesCampus
        )
      }
    )
  })

const fetchUsers =
  async () => {

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

const handleEdit =
  (user: User) => {

    selectedUser.value =
      user

    showEditModal.value =
      true
  }

const handleDelete =
  (user: User) => {

    selectedUser.value =
      user

    showDeleteModal.value =
      true
  }

onMounted(() => {
  fetchUsers()
})
</script>

<template>

  <AppLayout>

    <div
      class="bg-white p-6 rounded-xl shadow"
    >

      <div
        class="flex justify-between mb-6"
      >

        <div>

          <h1
            class="text-2xl font-semibold"
          >
            Users
          </h1>

          <p
            class="text-sm text-gray-500"
          >
            {{ filteredUsers.length }}
            registered users
          </p>

        </div>

        <button
          v-if="canCreate"
          @click="
            showCreateModal = true
          "
          class="bg-emerald-700 text-white px-4 py-2 rounded-md"
        >
          Add User
        </button>

      </div>

      <div
        class="flex gap-3 mb-6"
      >

        <input
          v-model="search"
          placeholder="Search user..."
          class="border border-gray-300 rounded-md px-3 py-2 flex-1"
        >

        <select
          v-if="isSuperAdmin"
          v-model="campusFilter"
          class="border border-gray-300 rounded-md px-3 py-2"
        >

          <option value="">
            All Campuses
          </option>

          <option
            v-for="campus in campuses"
            :key="campus"
            :value="campus"
          >
            {{ campus }}
          </option>

        </select>

        <select
          v-model="roleFilter"
          class="border border-gray-300 rounded-md px-3 py-2"
        >

          <option value="">
            All Roles
          </option>

          <option
            v-for="role in availableRoles"
            :key="role.value"
            :value="role.value"
          >
            {{ role.label }}
          </option>

        </select>

      </div>

      <p
        v-if="loading"
        class="text-gray-500"
      >
        Loading users...
      </p>

      <p
        v-else-if="
          !filteredUsers.length
        "
        class="text-gray-500"
      >
        No users found
      </p>

      <UserTable
        v-else
        :users="filteredUsers"
        @edit="handleEdit"
        @delete="handleDelete"
      />

      <CreateUserModal
        :show="showCreateModal"
        @close="
          showCreateModal = false
        "
        @created="fetchUsers"
      />

      <EditUserModal
        :show="showEditModal"
        :user="selectedUser"
        @close="
          showEditModal = false
        "
        @updated="fetchUsers"
      />

      <DeleteUserModal
        :show="showDeleteModal"
        :user="selectedUser"
        @close="
          showDeleteModal = false
        "
        @deleted="fetchUsers"
      />

    </div>

  </AppLayout>

</template>