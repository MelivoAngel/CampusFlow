<script setup lang="ts">
import { ref,computed,onMounted } from 'vue'
import { UserPlusIcon } from '@heroicons/vue/24/outline'
import { useAuthStore } from '../../../stores/authStore'

import {
  createUserRequest,
  getCampusesRequest
} from '../services/userApi'

import type { Campus } from '../../../types/campus'

import type {
  CreateUserPayload
} from '../../../types/requests'

import {
  superAdminCreateRoles,
  campusAdminCreateRoles,
  staffCreateRoles
} from '../../../constants/roles'

import { formatLabel } from '../../../utils/formatters'

defineProps({
  show: Boolean
})

const emit =
  defineEmits([
    'close',
    'created'
  ])

const authStore =
  useAuthStore()

const campuses =
  ref<Campus[]>([])

const name =
  ref('')

const email =
  ref('')

const password =
  ref('')

const role =
  ref('')

const campusId =
  ref('')

const errors =
  ref<Record<string,string[]>>({})

const firstError =
  ref('')

const successMessage =
  ref('')

const isSuperAdmin = computed(() => {

  return (
    authStore.user?.role ===
    'super_admin'
  )
})

const roles = computed(() => {

  if (isSuperAdmin.value) {
    return superAdminCreateRoles
  }

  if (
    authStore.user?.role ===
    'campus_admin'
  ) {
    return campusAdminCreateRoles
  }

  return staffCreateRoles
})

const fetchCampuses = async () => {

  if (!isSuperAdmin.value) {
    return
  }

  try {
    const response =
      await getCampusesRequest()

    campuses.value =
      response.data.data
  }

  catch (error) {
    console.log(error)
  }
}

const resetForm = () => {

  name.value = ''
  email.value = ''
  password.value = ''
  role.value = ''
  campusId.value = ''

  errors.value = {}
  firstError.value = ''
  successMessage.value = ''
}

const handleCreate = async () => {

  errors.value = {}
  firstError.value = ''
  successMessage.value = ''

  try {

    const payload:
      CreateUserPayload = {

      name: name.value,
      email: email.value,
      password: password.value,
      role: role.value
    }

    if (isSuperAdmin.value) {
      payload.campus_id =
        campusId.value
    }

    await createUserRequest(
      payload
    )

    successMessage.value =
      'User created successfully'

    emit('created')

    setTimeout(() => {

      emit('close')

      resetForm()

    },1000)
  }

  catch (error: any) {

    const backendErrors:
      Record<string,string[]> =

      error.response?.data?.errors

    if (backendErrors) {

      errors.value =
        backendErrors

      firstError.value =
        Object.values(
          backendErrors
        )[0][0]
    }

    else {

      firstError.value =
        error.response?.data?.message ||
        'Something went wrong'
    }
  }
}

onMounted(() => {
  fetchCampuses()
})
</script>

<template>

  <div
    v-if="show"
    class="fixed inset-0 bg-black/40 flex items-center justify-center"
  >

    <div
      class="bg-white p-6 rounded-2xl w-[500px] shadow-xl"
    >

      <div
        class="flex justify-between items-center mb-5"
      >

        <div class="flex items-center gap-3">

          <div
            class="w-11 h-11 rounded-full bg-emerald-50 flex items-center justify-center"
          >
            <UserPlusIcon
              class="w-5 h-5 text-emerald-700"
            />
          </div>

          <div>

            <h2 class="text-lg font-semibold">
              Create User
            </h2>

            <p class="text-sm text-gray-500">
              Add a new system user
            </p>

          </div>

        </div>

        <button
          @click="emit('close')"
          class="text-gray-500 hover:text-black"
        >
          ✕
        </button>

      </div>

      <div class="space-y-4">

        <div>

          <label
            class="block text-sm font-medium mb-2"
          >
            Full Name
          </label>

          <input
            v-model="name"
            :class="[
              'w-full px-3 py-3 rounded-lg border',
              errors.name
                ? 'border-red-500'
                : 'border-gray-300'
            ]"
          >

        </div>

        <div>

          <label
            class="block text-sm font-medium mb-2"
          >
            Email Address
          </label>

          <input
            v-model="email"
            :class="[
              'w-full px-3 py-3 rounded-lg border',
              errors.email
                ? 'border-red-500'
                : 'border-gray-300'
            ]"
          >

        </div>

        <div>

          <label
            class="block text-sm font-medium mb-2"
          >
            Password
          </label>

          <input
            v-model="password"
            type="password"
            :class="[
              'w-full px-3 py-3 rounded-lg border',
              errors.password
                ? 'border-red-500'
                : 'border-gray-300'
            ]"
          >

        </div>

        <div>

          <label
            class="block text-sm font-medium mb-2"
          >
            Role
          </label>

          <select
            v-model="role"
            :class="[
              'w-full px-3 py-3 rounded-lg border',
              errors.role
                ? 'border-red-500'
                : 'border-gray-300'
            ]"
          >

            <option value="">
              Select Role
            </option>

            <option
              v-for="item in roles"
              :key="item"
              :value="item"
            >
              {{ formatLabel(item) }}
            </option>

          </select>

        </div>

        <div
          v-if="isSuperAdmin"
        >

          <label
            class="block text-sm font-medium mb-2"
          >
            Campus
          </label>

          <select
            v-model="campusId"
            :class="[
              'w-full px-3 py-3 rounded-lg border',
              errors.campus_id
                ? 'border-red-500'
                : 'border-gray-300'
            ]"
          >

            <option value="">
              Select Campus
            </option>

            <option
              v-for="campus in campuses"
              :key="campus.id"
              :value="campus.id"
            >
              {{ campus.name }}
            </option>

          </select>

        </div>

        <p
          v-if="successMessage"
          class="text-green-600 text-sm"
        >
          {{ successMessage }}
        </p>

        <p
          v-if="firstError"
          class="text-red-600 text-sm"
        >
          {{ firstError }}
        </p>

      </div>

      <div
        class="flex justify-end gap-3 mt-6 pt-4 border-t"
      >

        <button
          @click="emit('close')"
          class="px-4 py-2 text-gray-600"
        >
          Cancel
        </button>

        <button
          @click="handleCreate"
          class="bg-emerald-700 text-white px-5 py-2 rounded-lg"
        >
          Create User
        </button>

      </div>

    </div>

  </div>

</template>