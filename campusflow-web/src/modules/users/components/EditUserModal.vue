<script setup lang="ts">
import { ref,watch } from 'vue'
import { PencilSquareIcon } from '@heroicons/vue/24/outline'

import { updateUserRequest } from '../services/userApi'
import type { User } from '../../../types/user'

const props =
  defineProps<{
    show: boolean
    user: User | null
}>()

const emit =
  defineEmits([
    'close',
    'updated'
  ])

const name =
  ref('')

const email =
  ref('')

const password =
  ref('')

const errors =
  ref<Record<string,string[]>>({})

const firstError =
  ref('')

const successMessage =
  ref('')

watch(

  () => props.user,

  (user) => {

    if (!user) {
      return
    }

    name.value =
      user.name

    email.value =
      user.email

    password.value =
      ''
  },

  { immediate: true }
)

const handleUpdate = async () => {

  errors.value = {}
  firstError.value = ''
  successMessage.value = ''

  try {

    await updateUserRequest(

      props.user!.id,

      {
        name: name.value,
        email: email.value,
        password: password.value
      }
    )

    successMessage.value =
      'User updated successfully'

    emit('updated')

    setTimeout(() => {

      emit('close')

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
            class="w-11 h-11 rounded-full bg-blue-50 flex items-center justify-center"
          >
            <PencilSquareIcon
              class="w-5 h-5 text-blue-600"
            />
          </div>

          <div>

            <h2 class="text-lg font-semibold">
              Edit User
            </h2>

            <p class="text-sm text-gray-500">
              Update user account information
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

      <div
        v-if="user"
        class="mb-5 p-3 rounded-lg bg-gray-50 border"
      >

        <p
          class="text-xs text-gray-500 mb-1"
        >
          Current User
        </p>

        <p class="font-medium">
          {{ user.name }}
        </p>

        <p
          class="text-sm text-gray-500"
        >
          {{ user.email }}
        </p>

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
            New Password
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

          <p
            class="text-xs text-gray-500 mt-1"
          >
            Leave blank to keep current password
          </p>

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
          @click="handleUpdate"
          class="bg-blue-600 text-white px-5 py-2 rounded-lg"
        >
          Save Changes
        </button>

      </div>

    </div>

  </div>

</template>