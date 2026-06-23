<script setup lang="ts">
import { computed } from 'vue'
import { useAuthStore } from '../../../stores/authStore'

interface User {

  id: number

  name: string

  email: string

  role: string

  campus: {

    id: number

    name: string
  } | null
}

defineProps<{

  users: User[]
}>()

const emit =
  defineEmits([
    'edit'
  ])

const authStore =
  useAuthStore()

const showCampus = computed(() => {

  return (
    authStore.user?.role ===
    'super_admin'
  )
})

const canEdit = (
  role: string
) => {

  if (
    authStore.user?.role ===
    'super_admin'
  ) {
    return true
  }

  if (
    authStore.user?.role ===
    'campus_admin'
  ) {
    return [

      'staff',

      'field_technician',

      'calendar_admin'

    ].includes(role)
  }

  if (
    authStore.user?.role ===
    'staff'
  ) {
    return [

      'field_technician',

      'calendar_admin'

    ].includes(role)
  }

  return false
}

const formatRole = (
  role: string
) => {

  return role

    .replaceAll(
      '_',
      ' '
    )

    .replace(
      /\b\w/g,

      letter =>

        letter.toUpperCase()
    )
}
</script>

<template>

  <table class="w-full">

    <thead>

      <tr class="border-b">

        <th class="text-left py-3">
          Name
        </th>

        <th class="text-left py-3">
          Email
        </th>

        <th class="text-left py-3">
          Role
        </th>

        <th
          v-if="showCampus"
          class="text-left py-3"
        >
          Campus
        </th>

        <th class="text-left py-3">
          Actions
        </th>

      </tr>

    </thead>

    <tbody>

      <tr
        v-for="user in users"
        :key="user.id"
        class="border-b"
      >

        <td class="py-4">
          {{ user.name }}
        </td>

        <td class="py-4">
          {{ user.email }}
        </td>

        <td class="py-4">
          {{ formatRole(user.role) }}
        </td>

        <td
          v-if="showCampus"
          class="py-4"
        >
          {{ user.campus?.name || '-' }}
        </td>

        <td class="py-4">

          <button
            v-if="canEdit(user.role)"
            @click="emit('edit',user)"
            class="text-blue-600"
          >
            Edit
          </button>

        </td>

      </tr>

    </tbody>

  </table>

</template>