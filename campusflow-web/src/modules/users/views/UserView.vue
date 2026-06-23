<script setup lang="ts">
import AppLayout from '../../../shared/layouts/AppLayout.vue'

const users = [

  {
    id: 1,
    name: 'System Administrator',
    email: 'admin@campusflow.com',
    role: 'super_admin',
    campus_id: null
  },

  {
    id: 2,
    name: 'Alangilan Admin',
    email: 'alangilan.admin@campusflow.com',
    role: 'campus_admin',
    campus_id: 1
  },

  {
    id: 3,
    name: 'Alangilan Staff',
    email: 'alangilan.staff@campusflow.com',
    role: 'staff',
    campus_id: 1
  },

  {
    id: 4,
    name: 'Gym Scheduler',
    email: 'gym.calendar@campusflow.com',
    role: 'calendar_admin',
    campus_id: 1
  }
]
</script>

<template>
  <AppLayout>

    <div class="bg-white p-6 rounded-xl shadow">

      <div class="flex justify-between mb-6">

        <h1 class="text-2xl font-semibold">
          Users
        </h1>

        <button class="bg-emerald-700 text-white px-4 py-2 rounded-md">
          Add User
        </button>

      </div>

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

            <th class="text-left py-3">
              Campus
            </th>

            <th class="text-left py-3">
              Actions
            </th>

          </tr>

        </thead>

        <tbody>

          <tr v-for="user in users" :key="user.id" class="border-b">

            <td class="py-4">
              {{ user.name }}
            </td>

            <td class="py-4">
              {{ user.email }}
            </td>

            <td class="py-4">
              {{ user.role }}
            </td>

            <td class="py-4">
              {{ user.campus_id }}
            </td>

            <td class="py-4">

              <button class="text-blue-600 mr-3">
                Edit
              </button>

              <button class="text-red-600">
                Delete
              </button>

            </td>

          </tr>

        </tbody>

      </table>

    </div>

  </AppLayout>
</template>